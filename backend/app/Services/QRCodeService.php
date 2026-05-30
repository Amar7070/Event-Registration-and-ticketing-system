<?php

namespace App\Services;

use App\Models\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate a QR code for a ticket.
     *
     * @param Ticket $ticket
     * @return string
     */
    public function generate(Ticket $ticket): string
    {
        // Payload: encrypted token with ticket ID, event ID, timestamp
        $payload = encrypt([
            'ticket_id' => $ticket->id,
            'event_id' => $ticket->event_id,
            'token' => $ticket->qr_token,
            'issued_at' => now()->timestamp,
        ]);

        $disk = config('qrcode.disk', 'public');
        $directory = config('qrcode.directory', 'qr-codes');
        $format = config('qrcode.format', 'svg');
        $size = config('qrcode.size', 300);
        $errorCorrection = config('qrcode.error_correction', 'H');
        $margin = config('qrcode.margin', 2);

        $filename = "{$directory}/{$ticket->booking_reference}.{$format}";
        
        if (!Storage::disk($disk)->exists($directory)) {
            Storage::disk($disk)->makeDirectory($directory);
        }

        $filePath = Storage::disk($disk)->path($filename);

        QrCode::format($format)
            ->size($size)
            ->errorCorrection($errorCorrection)
            ->margin($margin)
            ->generate($payload, $filePath);

        $ticket->update(['qr_code_path' => $filename]);
        return $filename;
    }

    /**
     * Verify a QR code data and mark ticket as checked in.
     *
     * @param string $qrData
     * @param int $eventId
     * @param int $scannedByUserId
     * @return array
     */
    public function verify(string $qrData, int $eventId, int $scannedByUserId): array
    {
        try {
            $payload = decrypt($qrData);
            
            // Check if ticket exists
            $ticket = Ticket::with(['user', 'ticketType', 'event'])->find($payload['ticket_id']);

            if (!$ticket) {
                return ['valid' => false, 'message' => 'Ticket not found'];
            }

            // Validation logic
            if ($ticket->event_id !== $payload['event_id'] || $ticket->event_id !== $eventId) {
                return ['valid' => false, 'message' => 'QR code does not belong to this event'];
            }
            
            if ($ticket->qr_token !== $payload['token']) {
                return ['valid' => false, 'message' => 'Invalid QR token'];
            }
            
            if ($ticket->status !== 'confirmed' && $ticket->status !== 'used') {
                return ['valid' => false, 'message' => "Ticket status: {$ticket->status}"];
            }
            
            if ($ticket->checked_in_at) {
                return ['valid' => false, 'message' => 'Already checked in at ' . $ticket->checked_in_at->format('H:i')];
            }

            // Mark as used
            $ticket->update([
                'checked_in_at' => now(),
                'status' => 'used'
            ]);

            // Track in attendance logs
            \App\Models\AttendanceLog::create([
                'ticket_id' => $ticket->id,
                'event_id' => $ticket->event_id,
                'user_id' => $ticket->user_id,
                'scanned_by' => $scannedByUserId,
                'scanned_at' => now(),
            ]);

            return [
                'valid' => true,
                'message' => 'Check-in successful!',
                'attendee' => [
                    'name' => $ticket->user->name,
                    'ticket_type' => $ticket->ticketType->name,
                ]
            ];
        } catch (\Exception $e) {
            return ['valid' => false, 'message' => 'Invalid or unreadable QR code'];
        }
    }
}
