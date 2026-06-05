<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ScheduledMaintenance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Helpers\SpkHelper;

class ScheduledMaintenanceController extends Controller
{
    /* ═══════════════════════════════════════════
     *  ADMIN GA — Daftar semua jadwal (dengan filter)
     *  GET /api/scheduled-maintenances
     * ═══════════════════════════════════════════ */
    public function index(Request $request)
    {
        $schedules = ScheduledMaintenance::with(['worker:id,name', 'creator:id,name'])
            ->where('company_id', auth()->user()->company_id)
            ->byStatus($request->status)
            ->byWorker($request->worker_id)
            ->byMonth($request->month)
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->scheduled_sub_category_id, fn($q) => $q->where('scheduled_sub_category_id', $request->scheduled_sub_category_id))
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
            ->where('company_id', auth()->user()->company_id)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($this->formatItem($item));
    }

    /* ═══════════════════════════════════════════
     *  ADMIN GA — Buat jadwal baru
     *  POST /api/scheduled-maintenances
     * ═══════════════════════════════════════════ */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'                     => 'required|string|max:255',
            'category_id'               => 'required|exists:categories,id',
            'scheduled_sub_category_id' => 'nullable|exists:scheduled_sub_categories,id',
            'period'                    => 'required|in:weekly,monthly,quarterly,yearly',
            'scheduled_date'            => 'required|date',
            'worker_id'                 => 'required|exists:users,id',
            'note'                      => 'nullable|string',
            'is_auto'                   => 'boolean', // ← baru: toggle automasi
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $schedule = ScheduledMaintenance::create([
            'title'                     => $request->title,
            'category_id'               => $request->category_id,
            'scheduled_sub_category_id' => $request->scheduled_sub_category_id,
            'period'                    => $request->period,
            'scheduled_date'            => $request->scheduled_date,
            'worker_id'                 => $request->worker_id,
            'note'                      => $request->note,
            'created_by'                => $request->user()->id,
            'status'                    => 'pending',
            'company_id'                => $request->user()->company_id,
            'is_auto'                   => $request->boolean('is_auto', false),
        ]);

        return response()->json([
            'message' => 'Jadwal berhasil dibuat.',
            'data'    => $this->formatItem($schedule->load('worker:id,name')),
        ], 201);
    }

    /* ═══════════════════════════════════════════
     *  ADMIN GA — Toggle automasi jadwal
     *  PUT /api/scheduled-maintenances/{id}/toggle-auto
     * ═══════════════════════════════════════════ */
    public function toggleAuto($id)
    {
        $schedule = ScheduledMaintenance::where('company_id', auth()->user()->company_id)
            ->where('id', $id)
            ->firstOrFail();

        $schedule->update(['is_auto' => !$schedule->is_auto]);

        $status = $schedule->is_auto ? 'diaktifkan' : 'dimatikan';

        return response()->json([
            'message' => "Automasi jadwal berhasil {$status}.",
            'is_auto' => $schedule->is_auto,
        ]);
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

        $schedule = ScheduledMaintenance::where('company_id', auth()->user()->company_id)
            ->where('id', $id)
            ->firstOrFail();

        if ($schedule->status === 'done') {
            return response()->json(['message' => 'Jadwal sudah selesai, tidak bisa diubah.'], 422);
        }

        $schedule->update([
            'worker_id'           => $request->worker_id,
            'status'              => 'pending',
            'worker_confirmed_at' => null,
        ]);

        return response()->json(['message' => 'Tukang berhasil diperbarui.']);
    }

    /* ═══════════════════════════════════════════
     *  TUKANG — Konfirmasi jadwal
     *  PUT /api/scheduled-maintenances/{id}/confirm
     * ═══════════════════════════════════════════ */
    public function confirm(Request $request, $id)
    {
        $schedule = ScheduledMaintenance::findOrFail($id);

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

        return response()->json(['message' => 'Jadwal berhasil dikonfirmasi.']);
    }

    /* ═══════════════════════════════════════════
     *  TUKANG — Tandai selesai + upload foto
     *  POST /api/scheduled-maintenances/{id}/complete
     * ═══════════════════════════════════════════ */
    public function complete(Request $request, $id)
    {
        $schedule = ScheduledMaintenance::findOrFail($id);

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

        // ── AUTO SCHEDULING ─────────────────────────────────────────
        // Jika is_auto = true, buat jadwal berikutnya otomatis
        if ($schedule->is_auto) {
            $this->createNextSchedule($schedule);
        }
        // ────────────────────────────────────────────────────────────

        return response()->json(['message' => 'Pekerjaan berhasil dilaporkan selesai.']);
    }

    /* ═══════════════════════════════════════════
     *  HELPER PRIVAT — Buat jadwal berikutnya
     * ═══════════════════════════════════════════ */
    private function createNextSchedule(ScheduledMaintenance $prev): void
    {
        $nextDate = $this->calcNextDate($prev->scheduled_date, $prev->period);

        // Cegah duplikat: cek apakah jadwal berikutnya sudah ada
        $alreadyExists = ScheduledMaintenance::where('company_id', $prev->company_id)
            ->where('title', $prev->title)
            ->where('category_id', $prev->category_id)
            ->where('scheduled_date', $nextDate->toDateString())
            ->exists();

        if ($alreadyExists) return;

        ScheduledMaintenance::create([
            'company_id'                => $prev->company_id,
            'title'                     => $prev->title,
            'category_id'               => $prev->category_id,
            'scheduled_sub_category_id' => $prev->scheduled_sub_category_id,
            'period'                    => $prev->period,
            'scheduled_date'            => $nextDate->toDateString(),
            'worker_id'                 => $prev->worker_id,
            'note'                      => $prev->note,
            'created_by'                => $prev->created_by,
            'status'                    => 'pending',
            'is_auto'                   => true,          // tetap auto
            'parent_id'                 => $prev->id,     // lacak asal jadwal
        ]);
    }

    /* ═══════════════════════════════════════════
     *  HELPER PRIVAT — Hitung tanggal berikutnya
     * ═══════════════════════════════════════════ */
    private function calcNextDate($date, string $period): Carbon
    {
        $base = Carbon::parse($date);

        return match ($period) {
            'weekly'    => $base->addWeek(),
            'monthly'   => $base->addMonth(),
            'quarterly' => $base->addMonths(3),
            'yearly'    => $base->addYear(),
            default     => $base->addMonth(),
        };
    }

    /* ═══════════════════════════════════════════
     *  ADMIN GA — Hapus jadwal
     *  DELETE /api/scheduled-maintenances/{id}
     * ═══════════════════════════════════════════ */
    public function destroy($id)
    {
        $schedule = ScheduledMaintenance::where('company_id', auth()->user()->company_id)
            ->where('id', $id)
            ->firstOrFail();

        if ($schedule->status !== 'pending') {
            return response()->json(['message' => 'Hanya jadwal dengan status pending yang bisa dihapus.'], 422);
        }

        $schedule->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus.']);
    }

    /* ═══════════════════════════════════════════
     *  POST /api/scheduled-maintenances/{id}/send-spk
     * ═══════════════════════════════════════════ */
    public function sendSpk($id)
    {
        $scheduled = ScheduledMaintenance::where('company_id', auth()->user()->company_id)
            ->where('id', $id)
            ->firstOrFail();

        if (!$scheduled->worker_confirmed_at) {
            return response()->json(['message' => 'Tukang belum konfirmasi tugas'], 422);
        }

        if (!$scheduled->spk_number) {
            $scheduled->spk_number = SpkHelper::generate('scheduled', auth()->user()->company_id);
        }

        $scheduled->spk_sent_at = Carbon::now();
        $scheduled->spk_sent_by = auth()->id();
        $scheduled->save();

        $wo = \App\Models\WorkOrder::updateOrCreate(
            ['scheduled_maintenance_id' => $scheduled->id],
            [
                'wo_number'                 => $scheduled->spk_number,
                'type'                      => 'scheduled',
                'title'                     => $scheduled->title,
                'note'                      => $scheduled->note,
                'status'                    => 'issued',
                'company_id'                => auth()->user()->company_id,
                'category_id'               => $scheduled->category_id,
                'sub_category_id'           => $scheduled->scheduled_sub_category_id ?? null,
                'worker_id'                 => $scheduled->worker_id,
                'created_by'                => auth()->id(),
                'schedule_date'             => $scheduled->scheduled_date,
                'period'                    => $scheduled->period,
            ]
        );

        return response()->json([
            'message'    => 'SPK berhasil dikirim',
            'spk_number' => $scheduled->spk_number,
            'wo_id'      => $wo->id,
        ]);
    }

    /* ═══════════════════════════════════════════
     *  PUT /api/scheduled-maintenances/{id}/start
     * ═══════════════════════════════════════════ */
    public function start($id)
    {
        $maintenance = ScheduledMaintenance::findOrFail($id);

        if ($maintenance->status !== 'confirmed') {
            return response()->json(['message' => 'Status harus confirmed terlebih dahulu'], 422);
        }

        $maintenance->update([
            'status'     => 'in_progress',
            'started_at' => now(),
        ]);

        return response()->json(['message' => 'Pekerjaan berhasil dimulai']);
    }

    /* ═══════════════════════════════════════════
     *  HELPER — Format item untuk response JSON
     * ═══════════════════════════════════════════ */
    private function formatItem(ScheduledMaintenance $item): array
    {
        $woId = null;
        try {
            $wo   = \App\Models\WorkOrder::where('scheduled_maintenance_id', $item->id)->first();
            $woId = $wo?->id;
        } catch (\Exception $e) {
            $woId = null;
        }

        return [
            'id'                        => $item->id,
            'wo_id'                     => $woId,
            'title'                     => $item->title,
            'category_id'               => $item->category_id,
            'category_name'             => $item->category?->name ?? '-',
            'scheduled_sub_category_id' => $item->scheduled_sub_category_id,
            'sub_category_name'         => $item->scheduledSubCategory?->name ?? null,
            'note'                      => $item->note,
            'period'                    => $item->period,
            'scheduled_date'            => $item->scheduled_date?->toDateString(),
            'status'                    => $item->status,
            'is_auto'                   => (bool) $item->is_auto,   // ← baru
            'parent_id'                 => $item->parent_id,        // ← baru
            'worker_id'                 => $item->worker_id,
            'worker_name'               => $item->worker?->name,
            'created_by'                => $item->created_by,
            'created_by_name'           => $item->creator?->name,
            'worker_confirmed_at'       => $item->worker_confirmed_at?->toDateTimeString(),
            'completed_at'              => $item->completed_at?->toDateTimeString(),
            'completion_note'           => $item->completion_note,
            'completion_photo'          => $item->completion_photo,
            'created_at'                => $item->created_at?->toDateTimeString(),
            'spk_sent_at'               => $item->spk_sent_at?->toDateTimeString(),
            'spk_number'                => $item->spk_number,
        ];
    }
}