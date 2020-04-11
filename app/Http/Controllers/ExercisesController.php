<?php

namespace App\Http\Controllers;

use App\Models\Exercise\Exercise;
use Illuminate\Http\Request;

class ExercisesController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = (int)$request->input('pageSize', 10);
        $list = Exercise::query()->paginate($pageSize);
        return response()->json($list);
    }

    public function show(Exercise $exercise)
    {
        return response()->json($exercise);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
        ]);

        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        $exercise = Exercise::create($data);

        return response()->json($exercise);
    }

    public function update(Request $request, Exercise $exercise)
    {
        $exercise->update($request->all());

        return response()->json($exercise);
    }

    public function destroy(Exercise $exercise)
    {
        $exercise->delete();

        return response()->json('OK');
    }
}
