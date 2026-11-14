<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_login_with_admin_eksentra_username(): void
    {
        $admin = User::where('role', 'admin')->first();
        $admin->password = 'smpsmi_admin';
        $admin->save();

        $response = $this->post('/login', [
            'login_type' => 'admin',
            'username' => 'admin_eksentra',
            'password' => 'smpsmi_admin',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_old_admin_username_fails_login(): void
    {
        $response = $this->post('/login', [
            'login_type' => 'admin',
            'username' => 'admin',
            'password' => 'smpsmi_admin',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_admin_can_update_username_and_records_activity_log(): void
    {
        $admin = User::where('role', 'admin')->first();
        $newUsername = 'admin_baru_' . rand(100, 999);

        $response = $this->actingAs($admin)->put(route('admin.profile.update'), [
            'username' => $newUsername,
        ]);

        $response->assertSessionHas('success');
        $admin->refresh();
        $this->assertEquals($newUsername, $admin->username);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'ADMIN_USERNAME_CHANGED',
        ]);
    }
}
