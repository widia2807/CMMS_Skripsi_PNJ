<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::when($request->branch_id, fn($q) =>
            $q->where('branch_id', $request->branch_id)
        )->get();

        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name'      => 'required|string',
        ]);

        $room = Room::create($request->only('branch_id', 'name'));
        return response()->json($room, 201);
    }
}