<?php

namespace App\Http\Controllers\API;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BorrowingController extends Controller
{
    // GET /api/borrowings/my — PIC lihat pengajuan sendiri
    public function my(Request $request)
    {
        $user = auth()->user();

        return response()->json(
            Borrowing::with([
                'asset.branch',
                'asset.room',
                'asset.category',
                'destinationBranch',
                'destinationRoom',
            ])
            ->where('user_id', $user->id)   // ← filter by user, bukan branch
            ->latest()
            ->get()
            ->map(fn($b) => $this->formatItem($b))
        );
    }

    // GET /api/borrowings — Admin GA lihat semua per company
    public function index()
    {
        $user = auth()->user();

        return response()->json(
            Borrowing::with([
                'asset.branch',
                'asset.room',
                'asset.category',
                'requestBranch',
                'destinationBranch',
                'destinationRoom',
                'user',
            ])
            ->whereHas('asset', fn($q) => $q->where('company_id', $user->company_id))
            ->latest()
            ->get()
            ->map(fn($b) => $this->formatItem($b))
        );
    }

    // POST /api/borrowings — PIC ajukan peminjaman
    public function store(Request $request)
    {
        $request->validate([
            'asset_id'   => 'required|exists:assets,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'qty'        => 'nullable|integer|min:1',
            'reason'     => 'required|string',
            'destination_branch_id' => 'nullable|exists:branches,id',
            'destination_room_id'   => 'nullable|exists:rooms,id',
        ]);

        $user  = auth()->user();
        $asset = \App\Models\Asset::findOrFail($request->asset_id);

        // Cek asset sedang dipinjam
        $isUsed = Borrowing::where('asset_id', $request->asset_id)
            ->whereIn('status', ['approved', 'picked'])
            ->exists();

        if ($isUsed) {
            return response()->json(['message' => 'Asset sedang dipinjam!'], 422);
        }

        $borrowing = Borrowing::create([
            'asset_id'              => $request->asset_id,
            'user_id'               => $user->id,
            'request_branch_id'     => $user->branch_id,
            'source_branch_id'      => $asset->branch_id,
            'destination_branch_id' => $request->destination_branch_id,
            'destination_room_id'   => $request->destination_room_id,
            'start_date'            => $request->start_date,
            'end_date'              => $request->end_date,
            'qty'                   => $request->qty ?? 1,
            'reason'                => $request->reason,
            'notes'                 => $request->notes,
        ]);

        return response()->json([
            'message' => 'Pengajuan peminjaman berhasil dikirim',
            'data'    => $this->formatItem($borrowing->load(['asset.branch', 'destinationBranch', 'destinationRoom'])),
        ], 201);
    }

    // PUT /api/borrowings/{id}/approve — Admin approve
    public function approve(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'requested') {
            return response()->json(['message' => 'Status tidak valid'], 422);
        }

        $borrowing->update(['status' => 'approved']);

        return response()->json(['message' => 'Peminjaman disetujui']);
    }

    // PUT /api/borrowings/{id}/reject — Admin reject
    public function reject($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'requested') {
            return response()->json(['message' => 'Status tidak valid'], 422);
        }

        $borrowing->update(['status' => 'rejected']);

        return response()->json(['message' => 'Peminjaman ditolak']);
    }

    // PUT /api/borrowings/{id}/pick — Tandai sudah diambil
    public function markPicked($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'approved') {
            return response()->json(['message' => 'Harus disetujui dulu'], 422);
        }

        $borrowing->update(['status' => 'picked']);

        return response()->json(['message' => 'Asset sudah diambil']);
    }

    // PUT /api/borrowings/{id}/return — Tandai sudah dikembalikan
    public function markReturned($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'picked') {
            return response()->json(['message' => 'Asset belum diambil'], 422);
        }

        $borrowing->update(['status' => 'returned']);

        return response()->json(['message' => 'Asset berhasil dikembalikan']);
    }

    // HELPER — format response
    private function formatItem(Borrowing $b): array
    {
        return [
            'id'                   => $b->id,
            'status'               => $b->status,
            'qty'                  => $b->qty ?? 1,
            'reason'               => $b->reason,
            'notes'                => $b->notes,
            'start_date'           => $b->start_date,
            'end_date'             => $b->end_date,
            'created_at'           => $b->created_at?->toDateTimeString(),
            'asset'                => $b->asset ? [
                'id'     => $b->asset->id,
                'name'   => $b->asset->name,
                'branch' => $b->asset->branch ? ['id' => $b->asset->branch->id, 'name' => $b->asset->branch->name] : null,
                'room'   => $b->asset->room   ? ['id' => $b->asset->room->id,   'name' => $b->asset->room->name]   : null,
            ] : null,
            'destination_branch'   => $b->destinationBranch ? ['id' => $b->destinationBranch->id, 'name' => $b->destinationBranch->name] : null,
            'destination_room'     => $b->destinationRoom   ? ['id' => $b->destinationRoom->id,   'name' => $b->destinationRoom->name]   : null,
            'user'                 => $b->user ? ['id' => $b->user->id, 'name' => $b->user->name] : null,
        ];
    }
}