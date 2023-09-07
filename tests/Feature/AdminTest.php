<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Response;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /**
     * Get All User Data By Admin.
     */

    public function test_get_all_users(): void
    {
        $user = User::factory()->create([
            'is_admin' => 1
        ]);
        $service = new JwtService();
        $this->withToken($service->generateToken($user)->toString())
            ->json('GET', '/api/v1/admin/user-listing')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }
    /**
     * Get All User Data By User.
     */

    public function test_get_all_users_invalid(): void
    {
        $user = User::factory()->create();
        $service = new JwtService();
        $this->withToken($service->generateToken($user)->toString())
            ->json('GET', '/api/v1/admin/user-listing')
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }
}
