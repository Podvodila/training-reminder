<?php

namespace App\Http\Controllers;

use App\Models\Activity\Activity;
use Carbon\Carbon;
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
        $activity->load('exercises');
        return response()->json($activity);
    }

    public function store(Request $request)
    {
        $this->validateRequiredFileds($request);

        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        $data['status'] = Activity::STATUS_INACTIVE;

        $activity = Activity::create($data);
        $activity = $this->syncExercises($activity, $request->exercises, [
            'created_at' => Carbon::now(),
        ]);

        return response()->json($activity);
    }

    public function update(Request $request, Activity $activity)
    {
        $this->validateRequiredFileds($request);

        $activity->update($request->all());
        $activity = $this->syncExercises($activity, $request->exercises, [
            'updated_at' => Carbon::now(),
        ]);

        return response()->json($activity);
    }

    public function toggleStatus(Activity $activity)
    {
        $activity->status = $activity->status === Activity::STATUS_ACTIVE ? Activity::STATUS_INACTIVE : Activity::STATUS_ACTIVE;
        $activity->save();

        return response()->json($activity);
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return response()->json('OK');
    }

    private function validateRequiredFileds(Request $request)
    {
        $exerciseRules = [];

        if (!empty($request->exercises)) {
            foreach ($request->exercises as $key => $exercise) {
                $exerciseRules = array_merge($exerciseRules, [
                    'exercises.' . $key . '.exercise_id' => 'required',
                    'exercises.' . $key . '.progression_type' => 'required',
                ]);
            }
        }

        $this->validate($request, array_merge([
            'name' => 'required',
            'interval_minutes' => 'required',
            'available_time_from' => 'required',
            'available_time_to' => 'required',
            'exercises_per_time' => 'required',
            'exercises' => 'required|array',
        ], $exerciseRules), [
            'required' => 'This field is required'
        ]);
    }

    private function syncExercises(Activity $activity, $exercises, $additionalFields = [])
    {
        $dataToSync = [];
        foreach ($exercises as $exercise) {
            $dataToSync[$exercise['exercise_id']] = array_merge($exercise, $additionalFields);
        }
        $activity
            ->exercises()
            ->syncWithoutDetaching($dataToSync);
        $activity
            ->exercises()
            ->whereNotIn('exercise_id', array_keys($dataToSync))
            ->update(['activity_id' => null]); //Mark null instead of deleting to remain history of user exercises

        return $activity;
    }
}
