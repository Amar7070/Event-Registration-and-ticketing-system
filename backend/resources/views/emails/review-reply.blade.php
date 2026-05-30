<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reply from {{ $organizerName }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #0f172a;
            padding: 30px;
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
        }
        .logo span {
            color: #f43f5e;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 20px;
            color: #0f172a;
        }
        .event-badge {
            display: inline-block;
            background-color: #f1f5f9;
            color: #64748b;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 24px;
        }
        .message-box {
            background-color: #f8fafc;
            border-left: 4px solid #f43f5e;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 24px;
            font-size: 16px;
            line-height: 1.6;
            color: #334155;
        }
        .custom-note {
            background-color: #fdf2f8;
            border: 1px solid #fbcfe8;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 15px;
            line-height: 1.6;
            color: #831843;
            font-style: italic;
        }
        .custom-note-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: #db2777;
            margin-bottom: 8px;
            display: block;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 13px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Smart<span>Event</span></div>
        </div>
        <div class="content">
            <span class="event-badge">Re: {{ $eventTitle }}</span>
            <h1 class="greeting">Hi {{ $attendeeName }},</h1>
            
            <p style="margin-bottom: 16px; line-height: 1.6;"><strong>{{ $organizerName }}</strong> has replied to the review you recently left for their event:</p>

            <div class="message-box">
                {{ $replyMessage }}
            </div>

            @if($customNote)
            <div class="custom-note">
                <span class="custom-note-label">Personal Note from Organizer</span>
                "{{ $customNote }}"
            </div>
            @endif

            <p style="line-height: 1.6; margin-top: 30px;">
                Thank you for using our platform to discover and attend incredible events. Your feedback helps organizers improve their future experiences!
            </p>
            <p style="color: #64748b; margin: 0;">
                Best regards,<br>
                <strong>The {{ config('app.name', 'SmartEvent') }} Team</strong>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'SmartEvent') }}. All rights reserved.<br>
            If you have any questions, contact us at support@smartevent.com
        </div>
    </div>
</body>
</html>
