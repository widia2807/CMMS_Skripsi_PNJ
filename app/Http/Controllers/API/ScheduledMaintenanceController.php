<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScheduledMaintenance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ScheduledMaintenanceController extends Controller
{
    /* ═══════════════════════════════════════════
     *  ADMIN GA — Daftar semua jadwal (dengan filter)
     *  GET /api/scheduled-maintenances
     * ═══════════════════════════════════════════ */
    public function index(Request $request)
    {
        $schedules = ScheduledMaintenance::with(['worker:id,name', 'creator:id,name'])
            ->byStatus($request->status)
            ->byWorker($request->worker_id)
            ->byMonth($request->month)
            ->orderBy('scheduled_date', 'asc')
            ->get()
            ->map(fn($item) => $this->formatItem($item));

        return response()->json($schedules);
    }

    /* ═══════════════════════════════════════════
     *  TUKANG — Tugas milik tukang yang login
     *  GET /api/scheduled-maintenances/my-tasks
     * ═══════════════════════════════════════════ */
    public function myTasks(Request $request)
    {
        $schedules = ScheduledMaintenance::with(['creator:id,name'])
            ->where('worker_id', $request->user()->id)
            ->byMonth($request->month ?? Carbon::now()->format('Y-m'))
            ->orderByRaw("FIELD(status, 'pending', 'confirmed', 'in_progress', 'done')")
            ->orderBy('scheduled_date', 'asc')
            ->get()
            ->map(fn($item) => $this->formatItem($item));

        return response()->json($schedules);
    }

    /* ═══════════════════════════════════════════
     *  ADMIN GA — Detail satu jadwal
     *  GET /api/scheduled-maintenances/{id}
     * ═══════════════════════════════════════════ */
    public function show($id)
    {
        $item = ScheduledMaintenance::with(['worker:id,name', 'creator:id,name'])
            ->findOrFail($id);

        return response()->json($this->formatItem($item));
    }

    /* ═══════════════════════════════════════════
     *  ADMIN GA — Buat jadwal baru
     *  POST /api/scheduled-maintenances
     * ═══════════════════════════════════════════ */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'period'         => 'required|in:weekly,monthly,quarterly,yearly',
            'scheduled_date' => 'required|date',
            'worker_id'      => 'required|exists:users,id',
            'note'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $schedule = ScheduledMaintenance::create([
            'title'          => $request->title,
            'category_id' => $request->category_id,
            'period'         => $request->period,
            'scheduled_date' => $request->scheduled_date,
            'worker_id'      => $request->worker_id,
            'note'           => $request->note,
            'created_by'     => $request->user()->id,
            'status'         => 'pending',
        ]);

        // TODO: kirim notifikasi ke tukang (push notification / email)
        // Notification::send($schedule->worker, new MaintenanceAssigned($schedule));

        return response()->json([
            'message' => 'Jadwal berhasil dibuat.',
            'data'    => $this->formatItem($schedule->load('worker:id,name')),
        ], 201);
    }

    /* ═══════════════════════════════════════════
     *  ADMIN GA — Ubah tukang (reassign)
     *  PUT /api/scheduled-maintenances/{id}/assign
     * ═══════════════════════════════════════════ */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'worker_id' => 'required|exists:users,id',
        ]);

        $schedule = ScheduledMaintenance::findOrFail($id);

        // Tidak bisa ubah tukang jika sudah selesai
        if ($schedule->status === 'done') {
            return response()->json(['message' => 'Jadwal sudah selesai, tidak bisa diubah.'], 422);
        }

        $schedule->update([
            'worker_id'           => $request->worker_id,
            'status'              => 'pending', // reset ke pending agar tukang baru bisa konfirmasi
            'worker_confirmed_at' => null,
        ]);

        // TODO: notifikasi ke tukang baru
        // Notification::send($schedule->fresh()->worker, new MaintenanceAssigned($schedule));

        return response()->json(['message' => 'Tukang berhasil diperbarui.']);
    }

    /* ═══════════════════════════════════════════
     *  TUKANG — Konfirmasi jadwal (step 3 alur)
     *  PUT /api/scheduled-maintenances/{id}/confirm
     * ═══════════════════════════════════════════ */
    public function confirm(Request $request, $id)
    {
        $schedule = ScheduledMaintenance::findOrFail($id);

        // Pastikan hanya tukang yang ditugaskan yang bisa konfirmasi
        if ($schedule->worker_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($schedule->status !== 'pending') {
            return response()->json(['message' => 'Jadwal sudah dikonfirmasi sebelumnya.'], 422);
        }

        $schedule->update([
            'status'              => 'confirmed',
            'worker_confirmed_at' => Carbon::now(),
        ]);

        // TODO: notifikasi ke Admin GA bahwa tukang sudah konfirmasi
        // Notification::send($schedule->creator, new MaintenanceConfirmed($schedule));

        return response()->json(['message' => 'Jadwal berhasil dikonfirmasi.']);
    }

    /* ═══════════════════════════════════════════
     *  TUKANG — Tandai selesai + upload foto (step 4 alur)
     *  POST /api/scheduled-maintenances/{id}/complete
     * ═══════════════════════════════════════════ */
    public function complete(Request $request, $id)
    {
        $schedule = ScheduledMaintenance::findOrFail($id);

        // Pastikan hanya tukang yang ditugaskan
        if ($schedule->worker_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($schedule->status === 'done') {
            return response()->json(['message' => 'Pekerjaan sudah dilaporkan selesai.'], 422);
        }

        $validator = Validator::make($request->all(), [
            'completion_note'  => 'required|string',
            'completion_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = null;
        if ($request->hasFile('completion_photo')) {
            $photoPath = $request->file('completion_photo')
                ->store('maintenance/completion', 'public');
        }

        $schedule->update([
            'status'           => 'done',
            'completed_at'     => Carbon::now(),
            'completion_note'  => $request->completion_note,
            'completion_photo' => $photoPath,
        ]);

        // TODO: notifikasi ke Admin GA bahwa pekerjaan selesai
        // Notification::send($schedule->creator, new MaintenanceDone($schedule));

        return response()->json(['message' => 'Pekerjaan berhasil dilaporkan selesai.']);
    }

    /* ═══════════════════════════════════════════
     *  ADMIN GA — Hapus jadwal
     *  DELETE /api/scheduled-maintenances/{id}
     * ═══════════════════════════════════════════ */
    public function destroy($id)
    {
        $schedule = ScheduledMaintenance::findOrFail($id);

        if ($schedule->status !== 'pending') {
            return response()->json(['message' => 'Hanya jadwal dengan status pending yang bisa dihapus.'], 422);
        }

        $schedule->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus.']);
    }

    /* ═══════════════════════════════════════════
     *  HELPER — Format item untuk response JSON
     * ═══════════════════════════════════════════ */
    private function formatItem(ScheduledMaintenance $item): array
    {
        return [
            'id'                  => $item->id,
            'title'               => $item->title,
            'category'            => $item->category,
            'note'                => $item->note,
            'period'              => $item->period,
            'scheduled_date'      => $item->scheduled_date?->toDateString(),
            'status'              => $item->status,
            'worker_id'           => $item->worker_id,
            'worker_name'         => $item->worker?->name,
            'created_by'          => $item->created_by,
            'created_by_name'     => $item->creator?->name,
            'worker_confirmed_at' => $item->worker_confirmed_at?->toDateTimeString(),
            'completed_at'        => $item->completed_at?->toDateTimeString(),
            'completion_note'     => $item->completion_note,
            'completion_photo'    => $item->completion_photo,
            'created_at'          => $item->created_at?->toDateTimeString(),
        ];
    }
}