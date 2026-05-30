<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;

class TicketDownloadController extends Controller
{
    public function download(Ticket $ticket)
    {
        // Gate: only owner can download
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $pdf = Pdf::loadView('tickets.pdf', compact('ticket'));
        return $pdf->download("ticket-{$ticket->booking_reference}.pdf");
    }
}
