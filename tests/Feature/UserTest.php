<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    public function test_api_users_index_api() {
        $response = $this->json('GET', '/api/users');
        $response->assertStatus(200);
    }
}
