<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // ─── Tela de login ──────────────────────────────────────────────────────

    public function test_login_screen_renders(): void
    {
        $this->get('/login')->assertOk();
    }

    // ─── Login com credenciais válidas ──────────────────────────────────────

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $admin = User::factory()->admin()->create();

        $this->post('/login', [
            'email'    => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_operator_can_login_with_valid_credentials(): void
    {
        $operator = User::factory()->operador()->create();

        $this->post('/login', [
            'email'    => $operator->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    // ─── Bloqueio com credenciais inválidas ─────────────────────────────────

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'senha-errada',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $this->post('/login', [
            'email'    => 'nao@existe.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    // ─── Rate limiting — RF-01.7 ────────────────────────────────────────────

    public function test_login_is_throttled_after_5_failed_attempts(): void
    {
        $user = User::factory()->create();

        $key = strtolower($user->email) . '|127.0.0.1';
        RateLimiter::clear($key);

        foreach (range(1, 5) as $_) {
            $this->post('/login', [
                'email'    => $user->email,
                'password' => 'senha-errada',
            ]);
        }

        // Verifica diretamente no RateLimiter — assertStatus(429) causa
        // "Call to a member function all() on array" no PHPUnit 12 com SESSION_DRIVER=array
        $this->assertTrue(
            RateLimiter::tooManyAttempts($key, 5),
            'Esperava que o RateLimiter bloqueasse após 5 tentativas (RF-01.7)'
        );
        $this->assertGuest();
    }

    // ─── Redirecionamento de rotas protegidas ────────────────────────────────

    public function test_guest_is_redirected_to_login_on_protected_routes(): void
    {
        $urls = [
            route('dashboard'),
            route('aplicacoes.index'),
            route('historico.index'),
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_guest_cannot_access_admin_routes(): void
    {
        $urls = [
            route('admin.usuarios.index'),
            route('admin.sistemas-operacionais.index'),
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    // ─── Bloqueio de /register — RF-01.5 ────────────────────────────────────

    public function test_public_registration_route_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }
}
