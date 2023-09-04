<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $user_id
 * @property int $order_status_id
 * @property int $payment_id
 * @property string $uuid
 * @property mixed $products
 * @property mixed $address
 * @property float|null $delivery_fee
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $shipped_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUuid($value)
 * @property-read \App\Models\Payment $payment
 * @property-read \App\Models\OrderStatus $status
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_status_id',
        'payment_id',
        'uuid',
        'products',
        'address',
        'delivery_fee',
        'amount',
        'shipped_at'
    ];

    protected $casts = [
        'products' => 'array',
        'address' => 'array',
        'shipped_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
