<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AuthService::class);
    }

    /** @test */
    public function it_registers_a_user_and_returns_token()
    {
        $result = $this->service->register([
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'secret123',
        ]);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals('john@example.com', $result['user']->email);
    }

    /** @test */
    public function it_logs_in_with_valid_credentials()
    {
        $user = User::factory()->create(['password' => bcrypt('secret123')]);

        $result = $this->service->login([
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $this->assertArrayHasKey('token', $result);
    }

    /** @test */
    public function it_throws_error_for_invalid_login()
    {
        $this->expectException(ValidationException::class);

        $this->service->login([
            'email' => 'invalid@example.com',
            'password' => 'wrong',
        ]);
    }
}
