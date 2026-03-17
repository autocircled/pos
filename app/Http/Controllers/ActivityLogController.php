<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('user')) {
            $query->where('user_id', $request->user());
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('subject')) {
            $query->where('subject_type', $request->subject);
        }

        $logs = $query->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('activity-log.index', compact('logs', 'users'));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        return view('activity-log.show', compact('activityLog'));
    }
}
