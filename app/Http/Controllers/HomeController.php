<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{


public function index() {


    if (Auth::check()) {
        return view('dashboard.index');
    } else {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }
}


    public function switchLanguage($locale)
{
    app()->setLocale($locale);
    config(['app.locale' => $locale]);
    // You can store the chosen locale in session for persistence
    session(['locale' => $locale]);

    return redirect()->back(); // or any other redirect you want
}
}



