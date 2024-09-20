<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'amount', 'status', 'payment_gateway'];

    /**
     * Order that owns the payment history
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
