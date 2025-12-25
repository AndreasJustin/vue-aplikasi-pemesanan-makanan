<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'table_no',
        'order_date',
        'status',
        'total_price',
        'waitress_id',
    ];
    public function sumOrderPrice()
    {
        $orderDetail = OrderDetail::where('order_id', $this->id)->get()->pluck('price');
        $sum = collect($orderDetail)->sum();
        return $sum;
    }
    /**
     * Get all of the comments for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderDetail(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
    /**
     * Get the waitress that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function waitress(): BelongsTo
    {
        return $this->belongsTo(User::class, 'waitress_id', 'id');
    }
    /**
     * Get the cashier that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id', 'id');
    }
}
