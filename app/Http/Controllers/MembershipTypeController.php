<?php

namespace App\Http\Controllers;

use App\Models\MembershipType;

class MembershipTypeController extends Controller
{
    public function list()
    {
        return MembershipType::all()->pluck('name', 'id');
    }
}
