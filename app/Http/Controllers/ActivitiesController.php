<?php

namespace App\Http\Controllers;

use App\Models\Activity\Activity;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = (int)$request->input('pageSize', 10);
        $list = Activity::query()->paginate($pageSize);
        return response()->json($list);
    }

    public function show(Activity $activity)
    {
        return response()->json($activity);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'interval_minutes' => 'required',
            'available_time_from' => 'required',
            'available_time_to' => 'required',
        ]);

        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        $data['status'] = Activity::STATUS_INACTIVE;

        $activity = Activity::create($data);

        return response()->json($activity);
    }

    public function update(Request $request, Activity $activity)
    {
        $activity->update($request->all());

        return response()->json($activity);
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return response()->json('OK');
    }
}
