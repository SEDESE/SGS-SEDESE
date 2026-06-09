<?php

namespace Tests\Feature;

use App\Models\Alteracao;
use App\Models\Aplicacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class AplicacaoTest extends TestCase
{
    use RefreshDatabase;

    // ─── Listagem ──────────────────────────────────────────────────────────

    public function test_admin_can_list_applications(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->get(route('aplicacoes.index'))
             ->assertOk();
    }

    public function test_operator_can_list_applications(): void
    {
        $operator = User::factory()->operador()->create();

        $this->actingAs($operator)
             ->get(route('aplicacoes.index'))
             ->assertOk();
    }

    public function test_guest_cannot_list_applications(): void
    {
        $this->get(route('aplicacoes.index'))
             ->assertRedirect(route('login'));
    }

    // ─── Criação ───────────────────────────────────────────────────────────

    public function test_admin_can_create_application(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->post(route('aplicacoes.store'), [
                 'nome_aplicacao' => 'Sistema de Testes',
             ])
             ->assertRedirect(route('aplicacoes.index'))
             ->assertSessionHas('success');

        $this->assertDatabaseHas('aplicacoes', ['nome_aplicacao' => 'Sistema de Testes']);
    }

    public function test_operator_can_create_application(): void
    {
        $operator = User::factory()->operador()->create();

        $this->actingAs($operator)
             ->post(route('aplicacoes.store'), [
                 'nome_aplicacao' => 'App do Operador',
             ])
             ->assertRedirect(route('aplicacoes.index'));

        $this->assertDatabaseHas('aplicacoes', ['nome_aplicacao' => 'App do Operador']);
    }

    public function test_nome_aplicacao_is_required(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->post(route('aplicacoes.store'), [])
             ->assertSessionHasErrors('nome_aplicacao');

        $this->assertDatabaseCount('aplicacoes', 0);
    }

    // ─── Edição ────────────────────────────────────────────────────────────

    public function test_admin_can_edit_application(): void
    {
        $admin = User::factory()->admin()->create();
        $app   = Aplicacao::factory()->create(['nome_aplicacao' => 'App Original']);

        $this->actingAs($admin)
             ->put(route('aplicacoes.update', $app->id), [
                 'nome_aplicacao' => 'App Editada',
             ])
             ->assertRedirect(route('aplicacoes.index'))
             ->assertSessionHas('success');

        $this->assertDatabaseHas('aplicacoes', [
            'id'             => $app->id,
            'nome_aplicacao' => 'App Editada',
        ]);
    }

    public function test_operator_can_edit_application(): void
    {
        $operator = User::factory()->operador()->create();
        $app      = Aplicacao::factory()->create(['nome_aplicacao' => 'App Original']);

        $this->actingAs($operator)
             ->put(route('aplicacoes.update', $app->id), [
                 'nome_aplicacao' => 'App Editada pelo Operador',
             ])
             ->assertRedirect(route('aplicacoes.index'));

        $this->assertDatabaseHas('aplicacoes', ['nome_aplicacao' => 'App Editada pelo Operador']);
    }

    public function test_admin_can_view_application_details(): void
    {
        $admin = User::factory()->admin()->create();
        $app   = Aplicacao::factory()->create(['nome_aplicacao' => 'App Visível']);

        $this->actingAs($admin)
             ->get(route('aplicacoes.show', $app->id))
             ->assertOk()
             ->assertSee('App Visível');
    }

    // ─── Exclusão — RF-01.4 ────────────────────────────────────────────────

    public function test_admin_can_delete_application(): void
    {
        $admin = User::factory()->admin()->create();
        $app   = Aplicacao::factory()->create();

        $this->actingAs($admin)
             ->delete(route('aplicacoes.destroy', $app->id))
             ->assertRedirect(route('aplicacoes.index'))
             ->assertSessionHas('success');

        $this->assertDatabaseMissing('aplicacoes', ['id' => $app->id]);
    }

    public function test_operator_cannot_delete_application(): void
    {
        $operator = User::factory()->operador()->create();
        $app      = Aplicacao::factory()->create();

        $this->actingAs($operator)
             ->delete(route('aplicacoes.destroy', $app->id))
             ->assertForbidden();

        $this->assertDatabaseHas('aplicacoes', ['id' => $app->id]);
    }

    // ─── Histórico automático — RF-04.8 ────────────────────────────────────

    public function test_creating_application_generates_history(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->post(route('aplicacoes.store'), ['nome_aplicacao' => 'App Histórico']);

        $this->assertDatabaseCount('alteracoes', 1);

        $alteracao = Alteracao::first();
        $this->assertSame($admin->id, $alteracao->user_id);
        $this->assertStringContainsString('criada', $alteracao->descricao);
        $this->assertStringContainsString('App Histórico', $alteracao->descricao);
    }

    public function test_editing_application_generates_history(): void
    {
        $admin = User::factory()->admin()->create();
        // Factory não usa o Service — nenhum Alteracao criado ainda
        $app   = Aplicacao::factory()->create(['nome_aplicacao' => 'App Original']);
        $this->assertDatabaseCount('alteracoes', 0);

        $this->actingAs($admin)
             ->put(route('aplicacoes.update', $app->id), [
                 'nome_aplicacao' => 'App Modificada',
             ]);

        $this->assertDatabaseCount('alteracoes', 1);

        $alteracao = Alteracao::first();
        $this->assertSame($admin->id, $alteracao->user_id);
        $this->assertSame($app->id, $alteracao->aplicacao_id);
        $this->assertStringContainsString('Campos alterados', $alteracao->descricao);
    }

    public function test_deleting_application_generates_history(): void
    {
        $admin = User::factory()->admin()->create();
        $app   = Aplicacao::factory()->create(['nome_aplicacao' => 'App Para Excluir']);
        $this->assertDatabaseCount('alteracoes', 0);

        $this->actingAs($admin)
             ->delete(route('aplicacoes.destroy', $app->id));

        $this->assertDatabaseCount('aplicacoes', 0);
        $this->assertDatabaseCount('alteracoes', 1);

        $alteracao = Alteracao::first();
        $this->assertSame($admin->id, $alteracao->user_id);
        $this->assertStringContainsString('excluída', $alteracao->descricao);
        $this->assertStringContainsString('App Para Excluir', $alteracao->descricao);
    }

    public function test_history_preserves_app_name_after_deletion(): void
    {
        $admin = User::factory()->admin()->create();
        $app   = Aplicacao::factory()->create(['nome_aplicacao' => 'App Deletada']);

        $this->actingAs($admin)
             ->delete(route('aplicacoes.destroy', $app->id));

        $this->assertDatabaseHas('alteracoes', [
            'descricao' => 'Aplicação "App Deletada" excluída.',
        ]);
    }

    // ─── Credenciais — RNF-01.1 / RF-01.4 ──────────────────────────────────

    public function test_admin_sees_password_toggle_in_detail(): void
    {
        $admin = User::factory()->admin()->create();
        $app   = Aplicacao::factory()->withCredentials()->create();

        $this->actingAs($admin)
             ->get(route('aplicacoes.show', $app->id))
             ->assertOk()
             ->assertSee('data-senha="', false); // atributo HTML só existe no botão admin
    }

    public function test_operator_does_not_see_password_toggle(): void
    {
        $operator = User::factory()->operador()->create();
        $app      = Aplicacao::factory()->withCredentials()->create();

        $this->actingAs($operator)
             ->get(route('aplicacoes.show', $app->id))
             ->assertOk()
             ->assertDontSee('data-senha="', false); // 'toggle-senha' aparece no <script>, não no botão
    }
}
