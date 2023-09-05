<?php

namespace Ghiffariaq\Stripe\Util;

use GuzzleHttp\Client;

trait StripeClient
{

    public function apiRequest($url, $method, $options = [])
    {
        $client = new Client();
        try {
            $response = $client->request($method, $url, [
                'auth' => [config('stripe.secret'), ''],
                'form_params' => $options
            ]);
            return json_decode($response->getBody()->getContents());
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function createCheckout($data)
    {
        $options = [
            'mode' => 'payment',
            'success_url' => route('stripe.callback', $data['order']['uuid']) . "?gtw=stripe&status=success",
            'cancel_url' => route('stripe.callback', $data['order']['uuid']) . "?gtw=stripe&status=failure",
            'line_items' => $this->generateStripePrice($data['products']),
            'customer_email' => $data['order']->user->email
        ];
        return $this->apiRequest($this->getBaseApiUrl() . "/checkout/sessions", "POST", $options);
    }

    public function createStripePrice($data)
    {
        $options = [
            'unit_amount' => $data['price'] * 100,
            'currency' => config('stripe.currency'),
            'product_data' => [
                'name' => $data['title']
            ]
        ];
        return $this->apiRequest($this->getBaseApiUrl() . "/prices", "POST", $options);
    }

    private function generateStripePrice($products)
    {
        $stripeProducts = [];
        foreach($products as $product){
            $price = $this->createStripePrice($product);
            $stripeProducts[] = [
                'price' => $price->id,
                'quantity' => $product['quantity']
            ];
        }

        return $stripeProducts;
    }

    private function getBaseApiUrl()
    {
        return "https://api.stripe.com/v1";
    }
}
