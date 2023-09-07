<?php

namespace Tests\Feature;

use App\Models\User;
use App\Util\JwtHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use JwtHelper;

    public function test_post_login(): void
    {
        $user = User::factory()->create([
            'password' => 'userpassword'
        ]);
        $this->json('POST', '/api/v1/user/login', [
                "email" => $user->email,
                "password" => 'userpassword'
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }


    public function test_post_login_invalid(): void
    {
        $user = User::factory()->create([
            'password' => 'userpassword'
        ]);
        $this->json('POST', '/api/v1/user/login', [
                "email" => $user->email,
                "password" => 'invalidpassword'
            ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    public function test_get_logout(): void
    {
        $user = User::factory()->create();
        $this->withToken($this->generateToken($user)->toString())
            ->json('GET', '/api/v1/user/logout')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    /**
     * Get All Orders Data By User.
     */


    public function test_get_all_orders_by_user(): void
    {
        $user = User::factory()->create();
        $this->withToken($this->generateToken($user)->toString())
            ->json('GET', '/api/v1/user/orders')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }
    /**
     * Get Logged In User Data
     */
    public function test_get_user_data(): void
    {
        $user = User::factory()->create();
        $this->withToken($this->generateToken($user)->toString())
            ->json('GET', '/api/v1/user')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    public function test_get_user_data_unauthenticated(): void
    {
        $this->json('GET', '/api/v1/user')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'message'
            ]);
    }


}
