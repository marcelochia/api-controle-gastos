<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;
    
    public function testApiCanDoLogin()
    {
        // Prepare
        User::factory()->create([
            'email' => 'admin@email.com',
            'password' => bcrypt('123456')
        ]);

        $payload = [
            'email' => 'admin@email.com',
            'password' => '123456'
        ];

        // Act
        $response = $this->postJson('api/login', $payload);

        // Assert
        $response->assertOk();
    }


    public function testRequestMustBeReturnUnauthorized()
    {
        // Act
        $response = $this->getJson('api/receitas');

        // Assert
        $response->assertUnauthorized();
    }
}
