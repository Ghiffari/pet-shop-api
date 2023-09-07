<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ListTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_categories(): void
    {
        $this->json('GET', '/api/v1/categories')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }
    /**
     * A basic feature test example.
     */
    public function test_get_order_statuses(): void
    {
        $this->json('GET', '/api/v1/order-statuses')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }
    /**
     * A basic feature test example.
     */
    public function test_get_products(): void
    {
        $this->json('GET', '/api/v1/products')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }
}
