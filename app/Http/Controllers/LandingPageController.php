<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;



class LandingPageController extends Controller
{
    public function index() {
        $user = Auth::user();
        $event = Event::latest()->get();
        $post = Documentation::latest()->get();
        return view('user.landing-page', compact('event','post','user'));
    }
}
