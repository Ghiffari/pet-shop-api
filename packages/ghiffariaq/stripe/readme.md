# Stripe Package

## Description

This package integrate the Stripe API.

## Configuration

Make sure you have setup the .env values required
```dotenv
STRIPE_KEY="your-stripe-key"
STRIPE_SECRET="your-stripe-secret"
```

## Usage

### Generate Checkout Object

```php
use Ghiffariaq\Stripe\Services\StripeService;
use App\Models\Order;

$stripeService = new StripeService();
$order = Order::find('some-id');
$checkout = $stripeService->generateCheckoutData([
    'order' => $order,
    'products' => [
        [
            'title' => 'title',
            'price' => 'price,
            'quantity' => 'qty',
        ];
    ],
]);
```
