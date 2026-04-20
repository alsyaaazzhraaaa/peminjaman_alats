<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Run seeders to set up roles/data
    }

    // --- Admin Tests ---
    public function test_admin_can_access_everything()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);

        // Check Policies
        $this->assertTrue($admin->can('viewAny', User::class));
        $this->assertTrue($admin->can('viewAny', Alat::class));
        $this->assertTrue($admin->can('create', Alat::class));
        $this->assertTrue($admin->can('viewAny', Kategori::class));
        $this->assertTrue($admin->can('viewAny', Peminjaman::class));
        $this->assertTrue($admin->can('viewAny', Pengembalian::class));
        $this->assertTrue($admin->can('viewAny', \App\Models\LogAktivitas::class));
    }

    // --- Petugas Tests ---
    public function test_petugas_access_restrictions()
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $this->actingAs($petugas);

        // Should NOT access User Management
        $this->assertFalse($petugas->can('viewAny', User::class));

        // Should NOT access Master Data (Alat/Kategori) in Menu (viewAny checks this)
        // Note: Policy for Alat viewAny returns false for Petugas in strict table
        $this->assertFalse($petugas->can('viewAny', Kategori::class));
        $this->assertFalse($petugas->can('viewAny', Alat::class));

        // Should access Transactions
        $this->assertTrue($petugas->can('viewAny', Peminjaman::class));
        $this->assertTrue($petugas->can('viewAny', Pengembalian::class));

        // Should NOT access Logs
        $this->assertFalse($petugas->can('viewAny', \App\Models\LogAktivitas::class));

        // Should be able to Approve
        $peminjaman = Peminjaman::factory()->create(['status' => 'menunggu']);
        $this->assertTrue($petugas->can('approve', $peminjaman));
    }

    // --- Peminjam Tests ---
    public function test_peminjam_access_restrictions()
    {
        $peminjam = User::factory()->create(['role' => 'peminjam']);
        $this->actingAs($peminjam);

        // Should NOT access User Management
        $this->assertFalse($peminjam->can('viewAny', User::class));

        // Should access Alat List
        $this->assertTrue($peminjam->can('viewAny', Alat::class));

        // Should NOT create/edit Alat
        $this->assertFalse($peminjam->can('create', Alat::class));

        // Should access Own Peminjaman List
        $this->assertTrue($peminjam->can('viewAny', Peminjaman::class));

        // Should Access Own Peminjaman Data (getEloquentQuery logic simulation)
        // Helper method to check query scope
        $ownLoan = Peminjaman::factory()->create(['id_user' => $peminjam->id]);
        $otherLoan = Peminjaman::factory()->create(['id_user' => User::factory()->create()->id]);

        // Verify policy allows view own
        $this->assertTrue($peminjam->can('view', $ownLoan));
        // Verify policy denies view other
        $this->assertFalse($peminjam->can('view', $otherLoan));
    }
}
