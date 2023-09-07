<?php

namespace Ghiffariaq\Stripe\Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StripeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_callback(): void
    {
        $order = Order::factory()->create();
        $response = $this->get("payment/$order->uuid");
        $response->assertStatus(200);
    }
    /**
     * A basic feature test example.
     */
    public function test_callback_not_found(): void
    {
        $response = $this->get("payment/some-incorrect-id");
        $response->assertStatus(404);
    }
}
