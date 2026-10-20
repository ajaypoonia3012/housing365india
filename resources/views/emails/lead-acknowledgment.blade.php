<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Received — Housing365 India</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 0; line-height: 1.6; }
        .wrapper { width: 100%; max-width: 620px; margin: 24px auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .header { background: linear-gradient(135deg, #091e42 0%, #0052cc 100%); padding: 32px 36px; text-align: left; }
        .logo-text { color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; margin: 0; }
        .logo-cyan { color: #00aeef; }
        .header-tagline { color: #cbd5e1; font-size: 13px; margin-top: 6px; }
        .body-content { padding: 36px; }
        .greeting { font-size: 18px; font-weight: 700; color: #091e42; margin-bottom: 16px; }
        .badge { display: inline-block; background-color: #e0f2fe; color: #0369a1; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; margin-bottom: 16px; }
        .status-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .status-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .status-label { color: #64748b; font-weight: 600; }
        .status-val { color: #0f172a; font-weight: 700; }
        .steps { margin: 24px 0; padding-left: 20px; color: #475569; font-size: 14px; }
        .steps li { margin-bottom: 8px; }
        .cta-btn { display: inline-block; background-color: #00aeef; color: #ffffff; text-decoration: none; padding: 12px 28px; font-size: 14px; font-weight: 700; border-radius: 8px; margin: 16px 0; }
        .footer { background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 36px; text-align: center; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1 class="logo-text">Housing<span class="logo-cyan">365</span> India</h1>
            <div class="header-tagline">Everything Property. One Platform. Verified Owners & Legal Leasing.</div>
        </div>

        <div class="body-content">
            <span class="badge">Inquiry Acknowledged #{{ $lead->id }}</span>
            <div class="greeting">Hi {{ $lead->contact_name ?: ($lead->name ?: 'Customer') }},</div>

            <p>Thank you for reaching out to <strong>Housing365 India</strong>! We have received your inquiry and our property advisory desk is already reviewing your requirement.</p>

            <div class="status-box">
                <div class="status-row">
                    <span class="status-label">Requirement Type:</span>
                    <span class="status-val">{{ ucfirst(str_replace('_', ' ', $lead->lead_type)) }}</span>
                </div>
                @if($lead->property)
                <div class="status-row">
                    <span class="status-label">Property:</span>
                    <span class="status-val">{{ $lead->property->title }}</span>
                </div>
                <div class="status-row">
                    <span class="status-label">Location:</span>
                    <span class="status-val">{{ $lead->property->city }}</span>
                </div>
                @elseif($lead->city || $lead->preferred_city)
                <div class="status-row">
                    <span class="status-label">Target City:</span>
                    <span class="status-val">{{ $lead->city ?: $lead->preferred_city }}</span>
                </div>
                @endif
                <div class="status-row">
                    <span class="status-label">Expected Response:</span>
                    <span class="status-val" style="color: #059669;">Within 2 Hours</span>
                </div>
            </div>

            <p><strong>What happens next?</strong></p>
            <ol class="steps">
                <li>A dedicated Housing365 property specialist will review verified owner availability.</li>
                <li>We will reach out via WhatsApp or phone call to confirm your viewing schedule or requirement.</li>
                <li>Zero brokerage, fully digital e-stamped legal agreements, and biometric verification will be handled seamlessly.</li>
            </ol>

            <p style="font-size: 14px; color: #475569; margin-top: 24px;">
                Need urgent assistance? Call our direct helpline: <strong>+91 8000 365 365</strong> or visit <a href="https://housing365.in" style="color: #0052cc; text-decoration: none;">housing365.in</a>.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0 0 6px 0;">&copy; {{ date('Y') }} Housing365 India. All rights reserved.</p>
            <p style="margin: 0;">India's unified property platform for leasing, verification, property care, and management.</p>
        </div>
    </div>
</body>
</html>
