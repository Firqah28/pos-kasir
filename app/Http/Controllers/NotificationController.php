<?php

namespace App\Http\Controllers;

use App\Models\StoreNotification;

class NotificationController extends Controller
{
    public function markAllRead()
    {
        StoreNotification::whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
