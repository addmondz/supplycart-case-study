<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function list(Request $request)
    {
        $perPage = request()->query('limit', 10);

        $data = ActivityLog::with('causer')->paginate($perPage);

        return response()->json($data);
    }
}
