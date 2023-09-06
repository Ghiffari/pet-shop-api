<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Constants\OrderConstant;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use App\Util\OrderHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{

    use OrderHelper;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = $this->generateProductdata(rand(2, 10));
        $amount = $this->calculateOrderAmount($products);
        return [
            "address" => [
                "shipping" => $this->generateAddressData(),
                "billing" => $this->generateAddressData()
            ],
            "products" => $products,
            "user_id" => User::inRandomOrder()->first()->id,
            "order_status_id" => OrderStatus::inRandomOrder()->first()->id,
            "uuid" => Str::uuid(),
            "amount" => $amount,
            "delivery_fee" => $amount > 500 ? 15 : 0
        ];
    }

    private function generateProductData(int $num)
    {
        $products = [];
        for ($i=0; $i < $num ; $i++) {
            $products[] = [
                'product' => Product::inRandomOrder()->first()->uuid,
                'quantity' => rand(1,10)
            ];
        }

        return $products;
    }

    private function generateAddressData()
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'line1' => fake()->streetAddress(),
            'line2' => fake()->streetAddress(),
            'zip_code' => fake()->word(),
            'country' => fake()->countryCode()
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Order $order){
            if(in_array($order->status->title, [OrderConstant::STATUS_PAID, OrderConstant::STATUS_SHIPPED])){
                $payment = Payment::create($this->generatePaymentData());
                $order->update([
                    'payment_id' => $payment->id
                ]);
                if($order->status->title === OrderConstant::STATUS_SHIPPED){
                    $order->update([
                        'shipped_at' => Carbon::now()
                    ]);
                }
            }
        });
    }

    private function generatePaymentData()
    {
        $payments = OrderConstant::LIST_OF_PAYMENTS;
        $type = $payments[rand(0,2)];
        switch ($type) {
            case OrderConstant::PAYMENT_CREDIT_CARD:
                $details = [
                    "holder_name" => fake()->name(),
                    "number" => fake()->creditCardNumber(),
                    "ccv" => fake()->randomNumber(3),
                    "expire_date" => fake()->creditCardExpirationDateString()
                ];
                break;
            case OrderConstant::PAYMENT_BANK_TRANSFER:
                $details = [
                    "swift" => fake()->swiftBicNumber(),
                    "iban" => fake()->iban(),
                    "name" => fake()->word()
                ];
                break;
            case OrderConstant::PAYMENT_CASH_ON_DELIVERY:
                $details = [
                    "first_name" => fake()->firstName(),
                    "last_name" => fake()->lastName(),
                    "address" => fake()->address()
                ];
                break;
        }

        return [
            'uuid' => Str::uuid(),
            'type' => $type,
            'details' => $details
        ];

    }

}
