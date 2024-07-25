<?php

namespace App\Http\Controllers\Admin\Master;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Buses\StoreBusRequest;
use App\Http\Requests\Buses\UpdateBusRequest;
use App\Models\Buses;
use App\Models\Seats;
use Illuminate\Http\Request;

class BusesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['bus_number', 'operator_name', 'class_name', 'is_active']);
            $limit = $request->query('limit', 10);
            $page = $request->query('page', 1);

            $buses = Buses::filterWithJoin($filters)->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'data' => $buses->items(),
                'current_page' => $buses->currentPage(),
                'total_pages' => $buses->lastPage(),
                'total_items' => $buses->total()
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch buses', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $bus = Buses::findOrFail($id);

            return response()->json($bus, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch bus', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreBusRequest $request)
{
    DB::beginTransaction();

    try {
        $bus = Buses::create([
            'bus_number' => $request->bus_number,
            'capacity' => $request->capacity,
            'operator_name' => $request->operator_name,
            'class_id' => $request->class_id,
            'is_active' => $request->is_active,
            'created_by_id' => $request->user()->id,
            'updated_by_id' => $request->user()->id,
        ]);

        for ($i = 1; $i <= $request->capacity; $i++) {
            Seats::create([
                'bus_id' => $bus->id,
                'seat_number' => $i,
                'created_by_id' => $request->user()->id,
                'updated_by_id' => $request->user()->id,
            ]);
        }

        DB::commit();

        return response()->json(['message' => 'Bus created successfully', 'bus' => $bus], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Failed to create bus', 'error' => $e->getMessage()], 500);
    }
}


public function update(UpdateBusRequest $request, $id)
{
    DB::beginTransaction();

    try {
        $bus = Buses::findOrFail($id);
        $bus->update([
            'bus_number' => $request->bus_number,
            'capacity' => $request->capacity,
            'operator_name' => $request->operator_name,
            'class_id' => $request->class_id,
            'is_active' => $request->is_active,
            'updated_by_id' => $request->user()->id,
        ]);

        // Hapus kursi lama
        Seats::where('bus_id', $bus->id)->delete();

        // Buat kursi baru berdasarkan kapasitas bus yang baru
        for ($i = 1; $i <= $request->capacity; $i++) {
            Seats::create([
                'bus_id' => $bus->id,
                'seat_number' => $i,
                'created_by_id' => $request->user()->id,
                'updated_by_id' => $request->user()->id,
            ]);
        }

        DB::commit();

        return response()->json(['message' => 'Bus updated successfully', 'bus' => $bus], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Failed to update bus', 'error' => $e->getMessage()], 500);
    }
}

public function destroy($id)
{
    DB::beginTransaction();

    try {
        $bus = Buses::findOrFail($id);

        // Hapus kursi yang terkait dengan bus
        Seats::where('bus_id', $bus->id)->delete();

        // Hapus bus
        $bus->delete();

        DB::commit();

        return response()->json(['message' => 'Bus deleted successfully'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Failed to delete bus', 'error' => $e->getMessage()], 500);
    }
}
}
