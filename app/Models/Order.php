<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'outlet_id',
        'created_by',
        'customer_id',
        'order_date',
        'amount',
        'mode_of_payment',
        'total_amount',
        'status',
        'tax_rate',
        'tax_amount',
        'discount_rate',
        'discount_type',
        'discount_amount',
        'reference',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($order) {
            $order->reference = 'ORD-' . now()->format('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $order->save();
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments()
    {
        return $this->morphMany(IncomingPayment::class, 'payable');
    }

    public function menuItemOrders()
    {
        return $this->hasMany(MenuItemOrder::class);
    }

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_orders')
            ->withPivot(['qty', 'amount', 'tax_rate', 'tax_amount', 'discount_rate', 'discount_amount', 'total_amount'])
            ->withTimestamps();
    }

    public function settlements()
    {
        return $this->hasManyThrough(IncomingPayment::class, Settlement::class, 'payable_id', 'payable_id')
            ->where('settlements.payable_type', Order::class)
            ->where('incoming_payments.payable_type', Settlement::class);
    }

    public function getItems()
    {
        $itemsString = '';
        $items = $this->menuItemOrders->pluck('menuItem.name')->toArray();

        $itemsString = implode(', ', $items);
        return $itemsString;
    }

    public function getItemsStringAttribute()
    {
        return $this->getItems();
    }

    public function getPaymentDetailsAttribute()
    {
        // Get all payments belonging to the invoice
        $payments = $this->payments;

        // Pluck out the payment methods
        $paymentMethods = $this->payments->pluck('payment_method');

        // Create a string showing each payment and the payment method used
        $payment_detail = '';
        foreach ($payments as $key => $payment) {
            $payment_detail .= 'Payment ' . ($key + 1) . ': ' . formatCurrency($payment->amount) . ' (' . $paymentMethods[$key] . ')' . PHP_EOL;
        }
        return $payment_detail;
    }

    public function getPaymentStatus()
    {
        //return an array with the payment status and total amount paid
        $totalPaid = $this->payments->sum('amount');
        $totalAmount = $this->total_amount;
        $amountDue = $totalAmount - $totalPaid;
        $status = 'Unpaid';
        if ($totalPaid >= $totalAmount) {
            $status = 'Paid';
        } elseif ($totalPaid > 0) {
            $status = 'Partially Paid';
        }
        return [
            'status' => $status,
            'total_payments' => $totalPaid,
            'total_amount' => $totalAmount,
            'amount_due' => $amountDue
        ];
    }
}
