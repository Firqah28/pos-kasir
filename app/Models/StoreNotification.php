<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class StoreNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'type',
        'message',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Membuat notifikasi "fee jatuh tempo" untuk cabang yang sudah aktif
     * lebih dari satu bulan, maksimal satu notifikasi per cabang per bulan.
     */
    public static function syncFeeDueNotifications(): void
    {
        $today = Carbon::today();
        $satuBulanLalu = $today->copy()->subMonth();
        $awalBulan = $today->startOfMonth()->toDateTimeString();

        $stores = Store::query()
            ->where('status', Store::STATUS_AKTIF)
            ->whereNotNull('activated_at')
            ->where('activated_at', '<=', $satuBulanLalu)
            ->get();

        foreach ($stores as $store) {
            $sudahAda = static::query()
                ->where('store_id', $store->id)
                ->where('type', 'fee_due')
                ->where('created_at', '>=', $awalBulan)
                ->exists();

            if ($sudahAda) {
                continue;
            }

            static::create([
                'store_id' => $store->id,
                'type' => 'fee_due',
                'message' => "Cabang {$store->nama_toko} sudah aktif lebih dari 1 bulan (sejak {$store->activated_at->translatedFormat('d M Y')}). Nonaktifkan sampai fee bulan berjalan dibayar.",
            ]);
        }
    }

    /**
     * Notifikasi fee_due yang belum dibaca untuk suatu cabang.
     */
    public static function pendingForStore(int $storeId): Collection
    {
        return static::query()
            ->where('store_id', $storeId)
            ->where('type', 'fee_due')
            ->whereNull('read_at')
            ->get();
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }
}
