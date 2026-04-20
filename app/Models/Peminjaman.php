<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $table = 'peminjaman';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_user',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'status',
        'disetujui_oleh',
    ];


    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_kembali_rencana' => 'date',
            'created_at' => 'datetime',
        ];
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
        });
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }


    public function peminjam(): BelongsTo
    {
        return $this->user();
    }


    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }


    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
    }


    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'id_peminjaman');
    }


    public function isPending(): bool
    {
        return $this->status === 'menunggu';
    }


    public function isApproved(): bool
    {
        return $this->status === 'disetujui';
    }


    public function isReturned(): bool
    {
        return $this->status === 'dikembalikan';
    }


    public function isRejected(): bool
    {
        return $this->status === 'ditolak';
    }


    public function approve(int $approverId): bool
    {
        if (!$this->isPending()) {
            return false;
        }


        foreach ($this->detailPeminjaman as $detail) {
            $alat = $detail->alat;
            $alat->jumlah -= $detail->jumlah_pinjam;

            if ($alat->jumlah <= 0) {
                $alat->status = 'dipinjam';
            } else {
                $alat->status = 'tersedia';
            }

            $alat->save();
        }

        $this->status = 'disetujui';
        $this->disetujui_oleh = $approverId;
        return $this->save();
    }


    public function reject(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->status = 'ditolak';
        return $this->save();
    }


    public function returnItems(string $tanggalKembali, array $kondisiItems, string $catatan = ''): ?Pengembalian
    {
        if (!$this->isApproved()) {
            return null;
        }

        $returnDate = \Carbon\Carbon::parse($tanggalKembali);
        $plannedDate = \Carbon\Carbon::parse($this->tanggal_kembali_rencana);
        $daysLate = $returnDate->diffInDays($plannedDate, false);
        $dendaTelat = $daysLate < 0 ? abs($daysLate) * 5000 : 0;
        
        $dendaKerusakan = 0;
        
        // Loop over item conditions
        foreach ($kondisiItems as $item) {
            // expected format: ['id_detail' => x, 'jumlah_baik' => y, 'jumlah_rusak' => z, 'jumlah_hilang' => w]
            $detail = DetailPeminjaman::find($item['id_detail']);
            if (!$detail) continue;
            
            $alat = $detail->alat;
            $harga = $alat->harga ?? 0;
            
            $jmlBaik = (int)($item['jumlah_baik'] ?? 0);
            $jmlRusak = (int)($item['jumlah_rusak'] ?? 0);
            $jmlHilang = (int)($item['jumlah_hilang'] ?? 0);
            
            // Calculate penalties
            $dendaKerusakan += ($jmlRusak * ($harga * 0.5));
            $dendaKerusakan += ($jmlHilang * $harga);
            
            // Revert stock (hilang means stock is permanently lost)
            $alat->jumlah += ($jmlBaik + $jmlRusak);
            if ($alat->jumlah > 0) {
                $alat->status = 'tersedia';
            }
            $alat->save();
        }

        $totalDenda = $dendaTelat + $dendaKerusakan;

        $pengembalian = Pengembalian::create([
            'id_peminjaman' => $this->id,
            'tanggal_kembali' => $tanggalKembali,
            'denda' => $totalDenda,
            'kondisi_kembali' => $catatan,
            'status_pembayaran' => $totalDenda > 0 ? 'belum_bayar' : 'tidak_ada'
        ]);

        $this->status = 'dikembalikan';
        $this->save();

        if ($totalDenda > 0) {
            $pesan = "Anda dikenakan denda sebesar Rp " . number_format($totalDenda, 0, ',', '.') . ". Rincian: Denda Keterlambatan Rp " . number_format($dendaTelat, 0, ',', '.') . ", Denda Hilang/Rusak Rp " . number_format($dendaKerusakan, 0, ',', '.') . ". Harap bayar tunai ke petugas.";
            \Filament\Notifications\Notification::make()
                ->title('Informasi Denda Peminjaman')
                ->body($pesan)
                ->danger()
                ->sendToDatabase($this->user);
        }

        return $pengembalian;
    }

    public function getHariTerlambatAttribute(): int
    {
        if (!$this->isApproved()) {
            return 0;
        }
        $plannedDate = \Carbon\Carbon::parse($this->tanggal_kembali_rencana);
        $daysLate = now()->diffInDays($plannedDate, false);
        return $daysLate < 0 ? abs($daysLate) : 0;
    }

    public function getEstimasiDendaAttribute(): int
    {
        return $this->hari_terlambat * 5000;
    }
}
