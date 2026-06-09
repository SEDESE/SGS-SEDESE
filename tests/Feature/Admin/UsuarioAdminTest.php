<?php

namespace Tests\Feature\Admin;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuarioAdminTest extends TestCase
{
    use RefreshDatabase;

    // ─── Listagem — RF-02.1 ────────────────────────────────────────────────

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->get(route('admin.usuarios.index'))
             ->assertOk();
    }

    public function test_operator_cannot_list_users(): void
    {
        $operator = User::factory()->operador()->create();

        $this->actingAs($operator)
             ->get(route('admin.usuarios.index'))
             ->assertForbidden();
    }

    // ─── Criação — RF-02.2 ────────────────────────────────────────────────

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->post(route('admin.usuarios.store'), [
                 'name'                  => 'Novo Usuário',
                 'email'                 => 'novo@exemplo.com',
                 'password'              => 'Senha123!',
                 'password_confirmation' => 'Senha123!',
                 'role'                  => Role::Operador->value,
             ])
             ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'novo@exemplo.com',
            'role'  => Role::Operador->value,
        ]);
    }

    public function test_operator_cannot_create_user(): void
    {
        $operator = User::factory()->operador()->create();

        $this->actingAs($operator)
             ->post(route('admin.usuarios.store'), [
                 'name'                  => 'Tentativa',
                 'email'                 => 'tentativa@exemplo.com',
                 'password'              => 'Senha123!',
                 'password_confirmation' => 'Senha123!',
                 'role'                  => Role::Operador->value,
             ])
             ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'tentativa@exemplo.com']);
    }

    // ─── Edição — RF-02.3 ─────────────────────────────────────────────────

    public function test_admin_can_edit_user(): void
    {
        $admin  = User::factory()->admin()->create();
        $target = User::factory()->operador()->create(['name' => 'Nome Antigo']);

        $this->actingAs($admin)
             ->put(route('admin.usuarios.update', $target->id), [
                 'name'  => 'Nome Novo',
                 'email' => $target->email,
                 'role'  => Role::Operador->value,
             ])
             ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('users', ['id' => $target->id, 'name' => 'Nome Novo']);
    }

    public function test_operator_cannot_edit_users(): void
    {
        $operator = User::factory()->operador()->create();
        $target   = User::factory()->create(['name' => 'Nome Original']);

        $this->actingAs($operator)
             ->put(route('admin.usuarios.update', $target->id), [
                 'name'  => 'Nome Alterado',
                 'email' => $target->email,
                 'role'  => Role::Operador->value,
             ])
             ->assertForbidden();

        $this->assertDatabaseHas('users', ['name' => 'Nome Original']);
    }

    // ─── Desativação — RF-02.4 ────────────────────────────────────────────

    public function test_admin_can_deactivate_user(): void
    {
        $admin  = User::factory()->admin()->create();
        $target = User::factory()->operador()->create(['ativo' => true]);

        $this->actingAs($admin)
             ->patch(route('admin.usuarios.desativar', $target->id))
             ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('users', ['id' => $target->id, 'ativo' => false]);
    }

    public function test_operator_cannot_deactivate_users(): void
    {
        $operator = User::factory()->operador()->create();
        $target   = User::factory()->create(['ativo' => true]);

        $this->actingAs($operator)
             ->patch(route('admin.usuarios.desativar', $target->id))
             ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $target->id, 'ativo' => true]);
    }

    // ─── Exclusão — RF-02.4 ───────────────────────────────────────────────

    public function test_admin_can_delete_user(): void
    {
        $admin  = User::factory()->admin()->create();
        $target = User::factory()->operador()->create();

        $this->actingAs($admin)
             ->delete(route('admin.usuarios.destroy', $target->id))
             ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    public function test_operator_cannot_delete_users(): void
    {
        $operator = User::factory()->operador()->create();
        $target   = User::factory()->create();

        $this->actingAs($operator)
             ->delete(route('admin.usuarios.destroy', $target->id))
             ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $target->id]);
    }

    // ─── Sistemas Operacionais — RF-07.3 ──────────────────────────────────

    public function test_admin_can_access_sistemas_operacionais(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->get(route('admin.sistemas-operacionais.index'))
             ->assertOk();
    }

    public function test_operator_cannot_access_sistemas_operacionais(): void
    {
        $operator = User::factory()->operador()->create();

        $this->actingAs($operator)
             ->get(route('admin.sistemas-operacionais.index'))
             ->assertForbidden();
    }
}
