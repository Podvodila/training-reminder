<?php

namespace App\Http\Controllers;

use App\Models\Activity\Activity;
use App\Models\Exercise\Exercise;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'date_from' => 'required',
            'date_to' => 'required',
        ]);

        $activity = Activity::when($request->activity_id, function ($query) use ($request) {
            return $query->where('id', '=', $request->activity_id);
        })->first();

        $data = $this->prepareStatisticData($activity, $request);

        return response()->json($data);
    }

    private function prepareStatisticData(Activity $activity, $request)
    {
        $result = [
            'labels' => [],
            'datasets' => [],
        ];
        $period = CarbonPeriod::create($request->date_from, '1 day', $request->date_to);
        foreach ($period as $date) {
            $result['labels'][] = $date->format('m-d');
        }

        $userExercises = $activity
            ->user_exercises()
            ->whereBetween('finished_at', [date($request->date_from), date($request->date_to)])
            ->get();

        foreach ($userExercises as $userExercise) {
            $exerciseMonthAndDay = Carbon::createFromDate($userExercise->finished_at)->format('m-d');
            if (!in_array($exerciseMonthAndDay, $result['labels'])) {
                Log::warning(
                    'prepareStatisticData(): Key not found in result array, key - '
                    . $exerciseMonthAndDay . '; userExerciseId - ' . $userExercise->id
                );
                continue;
            }
            $dayIndex = array_search($exerciseMonthAndDay, $result['labels']);
            $activityExercise = $userExercise->activity_exercise;
            $exercise = $activityExercise->exercise;
            if ($exercise->type !== Exercise::TYPE_SPORT) {
                continue;
            }
            if (!isset($result['datasets'][$activityExercise->id])) {
                $result['datasets'][$activityExercise->id] = [
                    'label' => $exercise->name,
                    'data' => array_fill(0, count($result['labels']), 0),
                    'borderWidth' => 2,
                ];
            }

            $result['datasets'][$activityExercise->id]['data'][$dayIndex] += $userExercise->repetitions * $userExercise->sets;
        }

        $result['datasets'] = array_values($result['datasets']);

        return $result;
    }
}
