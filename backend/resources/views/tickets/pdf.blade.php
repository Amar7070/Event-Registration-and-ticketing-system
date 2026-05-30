<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - {{ $ticket->booking_reference }}</title>
    <style>
        body { font-family: sans-serif; color: #333; margin: 0; padding: 20px; }
        .ticket-box { border: 2px dashed #ccc; padding: 20px; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .event-title { font-size: 24px; font-weight: bold; margin-bottom: 5px; color: #4F46E5; }
        .row { width: 100%; display: table; }
        .col-left { display: table-cell; width: 70%; vertical-align: top; }
        .col-right { display: table-cell; width: 30%; text-align: right; vertical-align: top; }
        .details p { margin: 5px 0; }
        .label { font-weight: bold; color: #666; font-size: 12px; text-transform: uppercase; }
        .value { font-size: 16px; margin-bottom: 15px; }
        .qr-code { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="ticket-box">
        <div class="header">
            <div class="event-title">{{ $ticket->event->title ?? 'Event Name' }}</div>
            <div>{{ $ticket->event->start_date ? $ticket->event->start_date->format('F d, Y h:i A') : '' }}</div>
        </div>
        
        <div class="row">
            <div class="col-left details">
                <p class="label">Attendee Name</p>
                <p class="value">{{ $ticket->user->name ?? 'Attendee' }}</p>
                
                <p class="label">Ticket Type</p>
                <p class="value">{{ $ticket->ticketType->name ?? 'Regular' }}</p>

                <p class="label">Venue</p>
                <p class="value">{{ $ticket->event->venue->name ?? 'Online / TBD' }}</p>
                
                <p class="label">Booking Reference</p>
                <p class="value">{{ $ticket->booking_reference }}</p>
            </div>
            <div class="col-right">
                <div class="qr-code">
                    <!-- Assuming QRCode facade or base64 generated QR code -->
                    <img src="data:image/svg+xml;base64, {!! base64_encode(QrCode::size(150)->generate($ticket->qr_token)) !!}" alt="QR Code">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
