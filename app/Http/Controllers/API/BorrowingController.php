<?php

namespace App\Http\Controllers\API;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class BorrowingController extends Controller
{
    // PIC → lihat data sendiri
    public function my(Request $request)
    {
        return Borrowing::with('asset')
            ->where('request_branch_id', auth()->user()->branch_id)
            ->latest()
            ->get();
    }

    // Admin → semua data
    public function index()
    {
        return Borrowing::with(['asset','requestBranch'])
            ->latest()
            ->get();
    }

    // PIC → ajukan
    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // 🔥 cek asset sedang dipinjam
        $isUsed = Borrowing::where('asset_id', $request->asset_id)
            ->whereIn('status', ['approved','picked'])
            ->exists();

        if ($isUsed) {
            return response()->json([
                'message' => 'Asset sedang dipinjam!'
            ], 422);
        }

        $data = Borrowing::create([
            'asset_id' => $request->asset_id,
            'request_branch_id' => auth()->user()->branch_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes
        ]);

        return response()->json($data);
    }

    // ADMIN → approve
    public function approve(Request $request, $id)
    {
        $request->validate([
            'source_branch_id' => 'required|exists:branches,id'
        ]);

        $borrowing = Borrowing::findOrFail($id);
        if ($borrowing->status !== 'requested') {
        return response()->json([
            'message' => 'Status tidak valid'
        ], 422);
    }
        $borrowing->update([
            'status' => 'approved',
            'source_branch_id' => $request->source_branch_id
        ]);

        return response()->json(['message'=>'Approved']);
    }

    // ADMIN → reject
    public function reject($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        if ($borrowing->status !== 'requested') {
        return response()->json([
            'message' => 'Status tidak valid'
        ], 422);
    }
        $borrowing->update([
            'status' => 'rejected'
        ]);

        return response()->json(['message'=>'Rejected']);
    }

    // tambahan (opsional tapi penting)
    public function markPicked($id)
    {
        $b = Borrowing::findOrFail($id);
        $b->update(['status'=>'picked']);
        return response()->json(['message'=>'Diambil']);
    }

    public function markReturned($id)
    {
        $b = Borrowing::findOrFail($id);
        $b->update(['status'=>'returned']);
        return response()->json(['message'=>'Dikembalikan']);
    }
}
