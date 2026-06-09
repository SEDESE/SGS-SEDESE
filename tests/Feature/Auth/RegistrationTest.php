<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * O registro público foi desabilitado — RF-01.5.
 * Novos usuários só podem ser criados por Administradores (RF-02.2).
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_public_registration_is_blocked(): void
    {
        $this->post('/register', [
            'name'                  => 'Tentativa',
            'email'                 => 'tentativa@exemplo.com',
            'password'              => 'Password1',
            'password_confirmation' => 'Password1',
        ])->assertNotFound();

        $this->assertDatabaseMissing('users', ['email' => 'tentativa@exemplo.com']);
    }
}
