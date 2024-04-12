<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class FriendController extends Controller
{
    public function index()
    {
        return Inertia::render('Friends/Index');
    }
}
