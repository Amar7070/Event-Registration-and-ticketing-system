<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QRScannerController extends Controller
{
    protected $qrCodeService;

    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Display the scanner page for a specific event.
     */
    public function scanner(Event $event)
    {
        // Ensure user is the organizer or admin
        if (Auth::user()->id !== $event->organizer_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        return view('organizer.scanner.index', compact('event'));
    }

    /**
     * Handle the QR scan request.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($request->event_id);

        // Authorization check
        if (Auth::user()->id !== $event->organizer_id && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'valid' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $result = $this->qrCodeService->verify(
            $request->qr_data,
            $request->event_id,
            Auth::id()
        );

        if ($result['valid']) {
            return response()->json($result);
        }

        return response()->json($result, 422);
    }
}
