<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Company;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //    dd("test");
        $logs = ActivityLog::with(['user', 'trainee', 'company'])
            ->whereNotNull('user_id')
            ->latest()
            ->get();
        //    dd($logs);
        return view('user_role.notification_page', compact('logs'));
        // return view('user_role.notification_page', compact('logs'));
    }

    public function markAsRead(ActivityLog $log)
    {
        // Toggle the read status
        $log->update(['read' => !$log->read]);

        return response()->json([
            'success' => true,
            'read' => $log->read,
            'unreadCount' => ActivityLog::where('read', 0)->count()
        ]);
    }

    public function markAllRead()
    {
        // Mark all as read and get count
        $updatedCount = ActivityLog::where('read', 0)->update(['read' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'All logs marked as read',
            'updated_count' => $updatedCount,
            'unreadCount' => 0
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //        $company = Company::create($request->all());
        //
        //        ActivityLog::create([
        //            'user_id'  => Auth::id(),
        //            'action'   => 'created',
        //            'model'    => 'Company',
        //            'model_id' => $company->id,
        //            'changes'  => json_encode($company->toArray()),
        //        ]);
        //
        //        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActivityLog  $activityLog
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActivityLog  $activityLog
     * @return \Illuminate\Http\Response
     */
    public function edit(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ActivityLog  $activityLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActivityLog $activityLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActivityLog  $activityLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivityLog $activityLog)
    {
        //
    }
}
