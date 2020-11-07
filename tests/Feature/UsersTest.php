<?php

namespace Tests\Feature;

use App\Api\v1\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
     * @test
     *
     * Test: GET /api/auth/login.
     */
    public function it_authenticate_a_user()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->post('/api/auth/login', ['email' => $user->email, 'password' => 'password'])
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in',
            ]);
    }
    
    /**
     * @test
     *
     * Test: GET /api/users.
     */
    public function it_fetches_users()
    {
        $this->seed('UsersTableSeeder');
        $user = User::factory()->create(['password' => bcrypt('password')]);
        
        $response = $this->withHeaders($this->headers($user))
            ->json('GET', '/api/users');
        $response
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type', 'id', 'attributes', 'attributes' => ['name', 'email']
                    ]
                ]
            ]);
    }

    /**
     * @test
     *
     * Test: GET /api/fruit/1.
     */
    public function it_fetches_a_single_user()
    {
        $this->seed('UsersTableSeeder');
        $user = User::factory()->create(['password' => bcrypt('password')]);
        
        $response = $this->withHeaders($this->headers($user))
            ->json('GET', '/api/users/1');
        $response
            ->assertJsonStructure([
                'data' => [
                    'type', 'id', 'attributes', 'attributes' => ['name', 'email']
                ]
            ]);
    }

    /**
     * @test
     *
     * Test: POST /api/users.
     */
    public function it_401s_when_not_authorized()
    {
        User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->json('GET', '/api/users');
        $response->assertStatus(401);
    }

    /**
     * @test
     *
     * Test: POST /api/users.
     */
    public function it_422_when_validation_fails()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $data = [
            'name' => 'John Doe',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this
            ->withHeaders($this->headers($user))
            ->json('POST', '/api/users', $data);
        $response->assertStatus(422);
        
        $data['email'] = 'test@test.org';

        $response = $this
            ->withHeaders($this->headers($user))
            ->json('POST', '/api/users', $data);
        $response->assertStatus(201);
    }

    /**
     * @test
     *
     * Test: DELETE /api/users/$id.
     */
    public function it_updates_a_user()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        
        $data = [
            'name' => 'John Doe',
        ];

        $response = $this
            ->withHeaders($this->headers($user))
            ->json('PUT', "/api/users", $data);
        $response
            ->assertJson([
                'data' => [
                    'attributes' => ['name' => $data['name']]
                ]
            ])
            ->assertStatus(200);
    }

    /**
     * @test
     *
     * Test: DELETE /api/users/$id.
     */
    public function it_deletes_a_user()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        $deletedUser = User::factory()->create(['password' => bcrypt('password')]);
        
        $response = $this
            ->withHeaders($this->headers($user))
            ->json('DELETE', "/api/users/{$deletedUser->getKey()}");
        $response->assertStatus(204);
    }
}
