<?php

namespace App\Models;

use Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    /** @use HasFactory<StoreFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_toko',
        'nama_toko',
        'alamat',
        'telepon',
        'status',
        'activated_at',
        'fee_persen',
    ];

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const STATUSES = [
        self::STATUS_AKTIF,
        self::STATUS_MENUNGGU_PEMBAYARAN,
        self::STATUS_NONAKTIF,
    ];

    /**
     * Default values applied on creation.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'menunggu_pembayaran',
        'fee_persen' => 5,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activated_at' => 'datetime',
        ];
    }

    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function pembelians(): HasMany
    {
        return $this->hasMany(Pembelian::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(StoreFee::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(StoreNotification::class);
    }
}
