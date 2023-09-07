<?php

namespace Ghiffariaq\Stripe\Util;

use GuzzleHttp\Client;

trait StripeClient
{
    public function apiRequest($url, $method, $options = []): mixed
    {
        $client = new Client();
        $body = [
            'auth' => [config('stripe.secret'), ''],
        ];
        if ($method === "POST") {
            $body['form_params'] = $options;
        }
        $response = $client->request($method, $url, $body);
        return json_decode($response->getBody()->getContents());
    }

    public function createCheckout($data): mixed
    {
        $options = [
            'mode' => 'payment',
            'success_url' => route('stripe.callback', $data['order']['uuid']) . "?gtw=stripe&status=success",
            'cancel_url' => route('stripe.callback', $data['order']['uuid']) . "?gtw=stripe&status=failure",
            'line_items' => $this->generateStripePrice($data['products']),
            'customer_email' => $data['order']->user->email,
        ];
        return $this->apiRequest($this->getBaseApiUrl() . "/checkout/sessions", "POST", $options);
    }

    public function retrieveCheckout(string $id): mixed
    {
        return $this->apiRequest($this->getBaseApiUrl() . "/checkout/sessions/{$id}", "GET");
    }

    public function createStripePrice($data): mixed
    {
        $options = [
            'unit_amount' => $data['price'] * 100,
            'currency' => config('stripe.currency'),
            'product_data' => [
                'name' => $data['title'],
            ],
        ];
        return $this->apiRequest($this->getBaseApiUrl() . "/prices", "POST", $options);
    }

    private function generateStripePrice($products): array
    {
        $stripeProducts = [];
        foreach ($products as $product) {
            $price = $this->createStripePrice($product);
            $stripeProducts[] = [
                'price' => $price->id,
                'quantity' => $product['quantity'],
            ];
        }

        return $stripeProducts;
    }

    private function getBaseApiUrl(): string
    {
        return "https://api.stripe.com/v1";
    }
}
