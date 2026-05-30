<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function about()
    {
        return view('static.about');
    }

    public function contact()
    {
        return view('static.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // In a full production app, this would send an email or store the message.
        session()->flash('success', 'Thank you for contacting us. We will respond within one business day.');

        return back();
    }

    public function help()
    {
        return view('static.help');
    }

    public function blog()
    {
        return view('static.blog');
    }
}
