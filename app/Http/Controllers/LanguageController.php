<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function change(Request $request)
    {
        $lang = $request->get('lang', 'en'); // <-- belangrijk!
        session()->put('locale', $lang);
        return redirect()->back();
    }

}
