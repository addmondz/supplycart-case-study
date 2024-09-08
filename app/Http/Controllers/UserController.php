<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function userDetails()
    {
        $user = User::with('membershipType')->find(Auth::id());

        return response()->json($user);
    }
}
