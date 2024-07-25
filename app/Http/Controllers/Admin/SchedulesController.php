<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['bus_id', 'route_id', 'departure_time', 'arrival_time', 'price', 'description', 'created_by_id', 'updated_by_id']);
            $limit = $request->query('limit', 10);
            $page = $request->query('page', 1);

            $schedules = Schedule::filter($filters)->paginate($limit, ['*'], 'page', $page);

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
            $schedule = Schedule::findOrFail($id);

            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch schedule', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreScheduleRequest $request)
    {
        try {
            $schedule = Schedule::create([
                'bus_id' => $request->bus_id,
                'route_id' => $request->route_id,
                'departure_time' => $request->departure_time,
                'arrival_time' => $request->arrival_time,
                'price' => $request->price,
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
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        try {
            $schedule->update($request->only(['bus_id', 'route_id', 'departure_time', 'arrival_time', 'price', 'description']));

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
            $schedule = Schedule::find($id);

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
