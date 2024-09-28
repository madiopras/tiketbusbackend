<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedules;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['bus_id', 'departure_time', 'arrival_time',  'description', 'created_by_id', 'updated_by_id']);
            $limit = $request->query('limit', 10);
            $page = $request->query('page', 1);

            $schedules = Schedules::filter($filters)->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'data' => $schedules->items(),
                'current_page' => $schedules->currentPage(),
                'total_pages' => $schedules->lastPage(),
                'total_items' => $schedules->total()
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch schedules', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $schedule = Schedules::findOrFail($id);

            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch schedule', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreScheduleRequest $request)
    {
        try {
            $schedule = Schedules::create([
                'bus_id' => $request->bus_id,
                'departure_time' => $request->departure_time,
                'arrival_time' => $request->arrival_time,
                'description' => $request->description,
                'created_by_id' => $request->user()->id,
                'updated_by_id' => $request->user()->id,
            ]);

            return response()->json(['message' => 'Schedule created successfully', 'schedule' => $schedule], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create schedule', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateScheduleRequest $request, $id)
    {
        $schedule = Schedules::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        try {
            $schedule->update($request->only(['bus_id', 'departure_time', 'arrival_time',  'description']));

            $schedule->updated_by_id = $request->user()->id;
            $schedule->save();

            return response()->json(['message' => 'Schedule updated successfully', 'schedule' => $schedule], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update schedule', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $schedule = Schedules::find($id);

            if (!$schedule) {
                return response()->json(['message' => 'Schedule not found'], 404);
            }

            $schedule->delete();

            return response()->json(['message' => 'Schedule deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete schedule', 'error' => $e->getMessage()], 500);
        }
    }
}
