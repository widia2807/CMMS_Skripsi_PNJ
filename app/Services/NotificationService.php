<?php

namespace App\Services;

use App\Models\User;

class NotificationService
{
    protected static function sendToUser($user, string $subject, string $body): void
{
    \Illuminate\Support\Facades\Log::info('DEBUG sendToUser dipanggil', [
        'user_id' => $user ? $user->id : 'NULL',
        'email'   => $user->email ?? 'NULL',
    ]);

    if (!$user || !$user->email) {
        \Illuminate\Support\Facades\Log::info('DEBUG sendToUser: SKIP karena user atau email kosong');
        return;
    }

    $result = ResendMailer::send($user->email, $subject, $body);

    \Illuminate\Support\Facades\Log::info('DEBUG hasil kirim email', [
        'to'      => $user->email,
        'success' => $result,
    ]);
}

protected static function sendToUsers($users, string $subject, string $body): void
{
    \Illuminate\Support\Facades\Log::info('DEBUG sendToUsers dipanggil', [
        'jumlah_user' => count($users),
    ]);

    foreach ($users as $user) {
        self::sendToUser($user, $subject, $body);
    }
}

    protected static function getGaUsers(int $companyId)
    {
        return User::where('company_id', $companyId)
            ->whereIn('role', ['admin', 'admin_ga', 'super_admin'])
            ->get();
    }

    public static function repairRequestSubmitted($req): void
    {
        $ga = self::getGaUsers($req->company_id);
        self::sendToUsers($ga,
            'Pengajuan Perbaikan Baru: ' . $req->title,
            "<h3>Pengajuan Perbaikan Baru</h3>
             <p><b>Judul:</b> {$req->title}</p>
             <p><b>Deskripsi:</b> {$req->description}</p>
             <p>Mohon segera ditindaklanjuti di sistem.</p>"
        );
    }

    public static function repairRequestApproved($req): void
    {
        self::sendToUser($req->user,
            'Pengajuan Disetujui: ' . $req->title,
            "<p>Pengajuan perbaikan '<b>{$req->title}</b>' telah disetujui dan akan diproses.</p>"
        );
    }

    public static function technicianAssigned($req): void
    {
        self::sendToUser($req->technician,
            'Penugasan Baru: ' . $req->title,
        "<p>Anda ditugaskan untuk pekerjaan '<b>{$req->title}</b>' di cabang <b>{$req->branch->name}</b>.</p>
         <p>Silakan cek detail dan jadwal pengerjaan melalui link berikut:</p>
         <p><a href='https://cmmsskripsipnj-production.up.railway.app/login'>https://cmmsskripsipnj-production.up.railway.app/login</a></p>"
    );
    }

    public static function spkSent($req): void
    {
        self::sendToUser($req->technician,
            'SPK Diterima: ' . $req->title,
            "<p>SPK No. <b>{$req->spk_number}</b> untuk pekerjaan '{$req->title}' telah dikirim.
             Jadwal: {$req->schedule_date}.</p>"
        );
    }

    public static function repairCompleted($req): void
    {
        $ga = self::getGaUsers($req->company_id);
        $recipients = $ga->push($req->user); // GA + PIC pengaju
        self::sendToUsers($recipients,
            'Pekerjaan Selesai: ' . $req->title,
            "<p>Pekerjaan '<b>{$req->title}</b>' telah selesai dikerjakan dan laporan sudah masuk.</p>"
        );
    }

    public static function scheduledTechnicianAssigned($sched): void
    {
        self::sendToUser($sched->worker,
            'Penugasan Maintenance: ' . $sched->title,
            "<p>Anda ditugaskan untuk maintenance '<b>{$sched->title}</b>' pada {$sched->scheduled_date}.</p>"
        );
    }

    public static function scheduledSpkSent($sched): void
    {
        self::sendToUser($sched->worker,
            'SPK Maintenance: ' . $sched->title,
            "<p>SPK No. <b>{$sched->spk_number}</b> untuk maintenance '{$sched->title}' telah dikirim.</p>"
        );
    }

    public static function scheduleConfirmed($sched): void
    {
        $ga = self::getGaUsers($sched->company_id);
        self::sendToUsers($ga,
            'Jadwal Dikonfirmasi: ' . $sched->title,
            "<p>{$sched->worker->name} telah mengkonfirmasi jadwal maintenance '<b>{$sched->title}</b>' 
            pada {$sched->scheduled_date}.</p>"
        );
    }

    public static function scheduledCompleted($sched): void
    {
        $ga = self::getGaUsers($sched->company_id);
        self::sendToUsers($ga,
            'Maintenance Selesai: ' . $sched->title,
            "<p>Maintenance '<b>{$sched->title}</b>' telah selesai dikerjakan.</p>"
        );
    }

    public static function borrowingSubmitted($borrowing, int $companyId): void
    {
        $ga = self::getGaUsers($companyId);
        self::sendToUsers($ga,
            'Pengajuan Peminjaman Baru',
            "<p>{$borrowing->user->name} mengajukan peminjaman aset '<b>{$borrowing->asset->name}</b>'.</p>"
        );
    }

    public static function borrowingApproved($borrowing): void
    {
        self::sendToUser($borrowing->user,
            'Peminjaman Disetujui',
            "<p>Pengajuan peminjaman aset '<b>{$borrowing->asset->name}</b>' telah disetujui.</p>"
        );
    }

    public static function borrowingRejected($borrowing): void
{
    self::sendToUser($borrowing->user,
        'Peminjaman Ditolak',
        "<p>Pengajuan peminjaman aset '<b>{$borrowing->asset->name}</b>' ditolak.</p>"
    );
}
}