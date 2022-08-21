<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $user;

    protected function createToken()
    {
        $this->user = User::factory()->create();

        $token = $this->user->createToken('token');

        return $token->plainTextToken;
    }
}
