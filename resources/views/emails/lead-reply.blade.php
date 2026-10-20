<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 0; line-height: 1.6; }
        .wrapper { width: 100%; max-width: 620px; margin: 24px auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .header { background: linear-gradient(135deg, #091e42 0%, #0052cc 100%); padding: 32px 36px; text-align: left; }
        .logo-text { color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; margin: 0; }
        .logo-cyan { color: #00aeef; }
        .header-tagline { color: #cbd5e1; font-size: 13px; margin-top: 6px; }
        .body-content { padding: 36px; }
        .greeting { font-size: 18px; font-weight: 700; color: #091e42; margin-bottom: 16px; }
        .message-box { background-color: #ffffff; color: #334155; font-size: 15px; margin: 20px 0; }
        .message-box p { margin-bottom: 14px; }
        .property-card { background-color: #f1f5f9; border-left: 4px solid #00aeef; border-radius: 8px; padding: 16px 20px; margin: 24px 0; }
        .property-title { font-weight: 700; font-size: 14px; color: #091e42; margin: 0 0 4px 0; }
        .property-meta { font-size: 13px; color: #64748b; margin: 0; }
        .attachment-notice { background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 14px 18px; color: #065f46; font-size: 13px; margin: 20px 0; display: flex; align-items: center; }
        .footer { background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 36px; text-align: center; font-size: 12px; color: #64748b; }
        .footer a { color: #0052cc; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1 class="logo-text">Housing<span class="logo-cyan">365</span> India</h1>
            <div class="header-tagline">Everything Property. One Platform. Verified Owners & Legal Leasing.</div>
        </div>

        <div class="body-content">
            <div class="greeting">Hello {{ $lead->contact_name ?: ($lead->name ?: 'Valued Customer') }},</div>

            <div class="message-box">
                {!! nl2br(e($emailMessage)) !!}
            </div>

            @if($lead->property)
            <div class="property-card">
                <div class="property-title">Referenced Property: {{ $lead->property->title }}</div>
                <div class="property-meta">
                    Location: {{ $lead->property->locality ? $lead->property->locality.', ' : '' }}{{ $lead->property->city }}
                    @if($lead->property->rent_amount)
                     | Expected Rent: ₹{{ number_format($lead->property->rent_amount) }}/month
                    @endif
                </div>
            </div>
            @endif

            @if($attachmentPath)
            <div class="attachment-notice">
                📎 <strong>Document Attached:</strong> An official document (e.g. Agreement Draft or Verification form) is attached to this email for your review.
            </div>
            @endif

            <p style="font-size: 14px; color: #475569; margin-top: 28px;">
                Warm regards,<br>
                <strong>{{ $senderName }}</strong><br>
                Housing365 India Property Specialist Team<br>
                <a href="https://housing365.in" style="color: #0052cc; text-decoration: none;">www.housing365.in</a> | Helpline: +91 8000 365 365
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0 0 6px 0;">This email was sent by Housing365 India CRM regarding your inquiry (Lead #{{ $lead->id }}).</p>
            <p style="margin: 0;">&copy; {{ date('Y') }} Housing365 India. All rights reserved. Pan-India Verified Real Estate Platform.</p>
        </div>
    </div>
</body>
</html>
