<?php

namespace Tests\Feature;

use App\Models\Alteracao;
use App\Models\Aplicacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoricoTest extends TestCase
{
    use RefreshDatabase;

    // ─── Listagem ──────────────────────────────────────────────────────────

    public function test_admin_can_view_history_list(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->get(route('historico.index'))
             ->assertOk();
    }

    public function test_operator_can_view_history_list(): void
    {
        $operator = User::factory()->operador()->create();

        $this->actingAs($operator)
             ->get(route('historico.index'))
             ->assertOk();
    }

    public function test_guest_cannot_view_history_list(): void
    {
        $this->get(route('historico.index'))
             ->assertRedirect(route('login'));
    }

    // ─── Edição de descrição — RF-05.5 ─────────────────────────────────────

    public function test_author_can_edit_their_own_history_description(): void
    {
        $user      = User::factory()->operador()->create();
        $app       = Aplicacao::factory()->create();
        $alteracao = Alteracao::create([
            'user_id'      => $user->id,
            'aplicacao_id' => $app->id,
            'descricao'    => 'Descrição original',
        ]);

        $this->actingAs($user)
             ->put(route('historico.update', $alteracao->id), [
                 'descricao' => 'Descrição corrigida pelo autor',
             ])
             ->assertRedirect(route('historico.index'));

        $this->assertDatabaseHas('alteracoes', [
            'id'       => $alteracao->id,
            'descricao' => 'Descrição corrigida pelo autor',
        ]);
    }

    public function test_admin_can_edit_any_history_description(): void
    {
        $admin     = User::factory()->admin()->create();
        $author    = User::factory()->operador()->create();
        $app       = Aplicacao::factory()->create();
        $alteracao = Alteracao::create([
            'user_id'      => $author->id,
            'aplicacao_id' => $app->id,
            'descricao'    => 'Descrição original',
        ]);

        $this->actingAs($admin)
             ->put(route('historico.update', $alteracao->id), [
                 'descricao' => 'Corrigido pelo admin',
             ])
             ->assertRedirect(route('historico.index'));

        $this->assertDatabaseHas('alteracoes', ['descricao' => 'Corrigido pelo admin']);
    }

    public function test_operator_cannot_edit_another_users_history(): void
    {
        $owner     = User::factory()->operador()->create();
        $other     = User::factory()->operador()->create();
        $app       = Aplicacao::factory()->create();
        $alteracao = Alteracao::create([
            'user_id'      => $owner->id,
            'aplicacao_id' => $app->id,
            'descricao'    => 'Minha descrição',
        ]);

        $this->actingAs($other)
             ->put(route('historico.update', $alteracao->id), [
                 'descricao' => 'Tentativa de alteração',
             ])
             ->assertForbidden();

        $this->assertDatabaseHas('alteracoes', ['descricao' => 'Minha descrição']);
    }

    // ─── Exclusão — RF-05.6 ────────────────────────────────────────────────

    public function test_admin_can_delete_history_record(): void
    {
        $admin     = User::factory()->admin()->create();
        $app       = Aplicacao::factory()->create();
        $alteracao = Alteracao::create([
            'user_id'      => $admin->id,
            'aplicacao_id' => $app->id,
            'descricao'    => 'Registro para excluir',
        ]);

        $this->actingAs($admin)
             ->delete(route('historico.destroy', $alteracao->id))
             ->assertRedirect(route('historico.index'));

        $this->assertDatabaseMissing('alteracoes', ['id' => $alteracao->id]);
    }

    public function test_operator_cannot_delete_history_record(): void
    {
        $operator  = User::factory()->operador()->create();
        $app       = Aplicacao::factory()->create();
        $alteracao = Alteracao::create([
            'user_id'      => $operator->id,
            'aplicacao_id' => $app->id,
            'descricao'    => 'Registro do operador',
        ]);

        $this->actingAs($operator)
             ->delete(route('historico.destroy', $alteracao->id))
             ->assertForbidden();

        $this->assertDatabaseHas('alteracoes', ['id' => $alteracao->id]);
    }

    public function test_operator_cannot_delete_even_own_history_record(): void
    {
        $operator  = User::factory()->operador()->create();
        $app       = Aplicacao::factory()->create();
        $alteracao = Alteracao::create([
            'user_id'      => $operator->id,
            'aplicacao_id' => $app->id,
            'descricao'    => 'Meu próprio registro',
        ]);

        // Mesmo sendo o autor, operador não pode excluir — RF-05.6
        $this->actingAs($operator)
             ->delete(route('historico.destroy', $alteracao->id))
             ->assertForbidden();
    }
}
