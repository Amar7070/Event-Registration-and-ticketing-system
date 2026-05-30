<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        $supportedLocales = config('localization.supported_locales', ['en', 'hi']);

        if (in_array($lang, $supportedLocales, true)) {
            Session::put('locale', $lang);
            
            if (auth()->check()) {
                auth()->user()->update(['preferred_language' => $lang]);
            }
        }

        return back();
    }
}
