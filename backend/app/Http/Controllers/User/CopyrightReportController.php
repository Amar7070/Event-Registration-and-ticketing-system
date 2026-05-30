<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\CopyrightReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CopyrightReportController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'evidence_url' => 'nullable|url|max:2000',
        ]);

        CopyrightReport::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'evidence_url' => $validated['evidence_url'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Thank you. Your security/copyright report has been registered. Our ecosystem stewards will audit this architecture immediately.');
    }
}
