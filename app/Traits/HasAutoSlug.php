<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasAutoSlug
{
    protected static function bootHasAutoSlug(): void
    {
        static::creating(function ($model) {
            $model->ensureAutoSlug();
        });

        static::updating(function ($model) {
            if (empty($model->slug)) {
                $model->ensureAutoSlug();
            }
        });
    }

    public function ensureAutoSlug(): void
    {
        $sourceField = $this->getSlugSourceField();
        $sourceValue = $this->{$sourceField} ?? null;

        if (empty($this->slug) && ! empty($sourceValue)) {
            $this->slug = $this->generateUniqueSlug($sourceValue);
        } elseif (! empty($this->slug)) {
            $this->slug = Str::slug($this->slug);
        }
    }

    public function getSlugSourceField(): string
    {
        if (property_exists($this, 'slugSource') && ! empty($this->slugSource)) {
            return $this->slugSource;
        }

        return (isset($this->title) || array_key_exists('title', $this->attributes)) ? 'title' : 'name';
    }

    public function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        if (empty($base)) {
            $base = 'item';
        }

        $slug = $base;
        $counter = 1;

        $checkQuery = function (string $candidate) {
            $q = static::where('slug', $candidate);
            if ($this->exists) {
                $q->where($this->getKeyName(), '!=', $this->getKey());
            }

            return $q->exists();
        };

        while ($checkQuery($slug)) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
