<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_can_log_in_and_view_dashboard_and_sales_recap(): void
    {
        $this->createStaff('kasir');

        $this->post(route('login.process'), [
            'username' => 'kasir',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.rekap'))->assertOk();
    }

    public function test_admin_can_manage_menu_prices_and_process_orders(): void
    {
        $admin = $this->createStaff('admin');
        $category = Category::create(['nama_kategori' => 'Minuman']);

        $this->actingAs($admin);
        $this->post(route('admin.menus.store'), [
            'category_id' => $category->id,
            'nama_menu' => 'Kopi Susu',
            'deskripsi' => 'Kopi dengan susu',
            'harga' => 18000,
            'is_available' => 1,
        ])->assertRedirect(route('admin.menus.index'));

        $menu = Menu::where('nama_menu', 'Kopi Susu')->firstOrFail();
        $this->put(route('admin.menus.update', $menu), [
            'category_id' => $category->id,
            'nama_menu' => 'Kopi Susu',
            'deskripsi' => 'Kopi dengan susu',
            'harga' => 20000,
            'is_available' => 1,
        ])->assertRedirect(route('admin.menus.index'));
        $this->assertDatabaseHas('menus', ['id' => $menu->id, 'harga' => 20000]);

        $order = Order::create([
            'metode_pembayaran' => 'tunai',
            'total_harga' => 10000,
            'status' => 'pending',
        ]);

        $this->post(route('admin.order.bayarTunai', $order->id), ['jumlah_bayar' => 15000])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status_verifikasi' => 'verified',
            'jumlah_bayar' => 15000,
            'kembalian' => 5000,
        ]);

        $this->post(route('admin.order.status', $order->id), ['status' => 'selesai'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'selesai']);
    }

    public function test_kasir_cannot_manage_menus(): void
    {
        $this->actingAs($this->createStaff('kasir'))
            ->get(route('admin.menus.index'))
            ->assertForbidden();
    }

    private function createStaff(string $role): User
    {
        return User::create([
            'name' => ucfirst($role) . ' Test',
            'email' => $role . '@coffeeshop.com',
            'password' => 'password',
            'role' => $role,
        ]);
    }
}
