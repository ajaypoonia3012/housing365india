<?php

namespace App\Services;

class HtmlSanitizer
{
    /**
     * Allowed HTML tags for inline formatted text.
     */
    protected static array $allowedTags = ['span', 'br', 'strong', 'b', 'em', 'i'];

    /**
     * Allowed attributes per tag.
     */
    protected static array $allowedAttributes = [
        'span' => ['class'],
    ];

    /**
     * Sanitize inline HTML, preserving permitted formatting tags and safe attributes.
     * Drops script, iframe, svg, and arbitrary HTML tags and all event handlers.
     */
    public static function clean(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        // Fast path: if no tags present, return HTML-escaped string
        if (! str_contains($html, '<')) {
            return e($html);
        }

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);

        // Wrap in HTML skeleton to parse fragment with UTF-8 encoding
        $wrapped = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>'.$html.'</body></html>';
        $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);

        $body = $dom->getElementsByTagName('body')->item(0);
        if (! $body) {
            return e(strip_tags($html));
        }

        self::cleanNode($body);

        $cleanHtml = '';
        foreach ($body->childNodes as $child) {
            $cleanHtml .= $dom->saveHTML($child);
        }

        return $cleanHtml;
    }

    protected static function cleanNode(\DOMNode $node): void
    {
        $toRemove = [];
        $toUnwrap = [];

        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMElement) {
                $tag = strtolower($child->tagName);

                // Completely eliminate dangerous executable elements and their children
                if (in_array($tag, ['script', 'style', 'svg', 'iframe', 'object', 'embed', 'applet', 'meta', 'link', 'form', 'input', 'button'], true)) {
                    $toRemove[] = $child;
                    continue;
                }

                // If element is not an allowed tag, unwrap its children (keep text/permitted children)
                if (! in_array($tag, self::$allowedTags, true)) {
                    $toUnwrap[] = $child;
                    self::cleanNode($child);
                    continue;
                }

                // Element is allowed: inspect and sanitize its attributes
                if ($child->hasAttributes()) {
                    $attrsToRemove = [];
                    foreach ($child->attributes as $attr) {
                        $attrName = strtolower($attr->name);
                        $allowedAttrs = self::$allowedAttributes[$tag] ?? [];

                        if (! in_array($attrName, $allowedAttrs, true)) {
                            $attrsToRemove[] = $attr->name;
                        } elseif ($attrName === 'class') {
                            // Validate class attribute: permit only alphanumeric, dash, underscore, whitespace
                            if (! preg_match('/^[a-zA-Z0-9_\-\s]+$/', $attr->value)) {
                                $attrsToRemove[] = $attr->name;
                            }
                        }
                    }

                    foreach ($attrsToRemove as $attrName) {
                        $child->removeAttribute($attrName);
                    }
                }

                self::cleanNode($child);
            }
        }

        foreach ($toRemove as $child) {
            $node->removeChild($child);
        }

        foreach ($toUnwrap as $child) {
            while ($child->firstChild) {
                $node->insertBefore($child->firstChild, $child);
            }
            $node->removeChild($child);
        }
    }
}
