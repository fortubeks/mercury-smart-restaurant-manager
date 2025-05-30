<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'restaurant_id',
        'purchase_category_id',
        'description',
        'purchase_date',
        'sub_total',
        'note',
        'store_id',
        'status',
        'total_amount',
        'tax_amount',
        'tax_rate',
        'discount',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($purchase) {
            foreach ($purchase->outgoingPayments as $payment) {
                $payment->delete(); // This will trigger the deleting event on PurchasePayment
            }
        });
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\ItemCategory', 'item_category_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseStoreItem::class);
    }

    public function getItems()
    {
        $itemsString = '';
        $itemNames = $this->items->map(function ($purchaseItem) {
            if (!$purchaseItem->storeItem) {
                \Log::warning('Missing storeItem for PurchaseItem ID: ' . $purchaseItem->id);
                return null; // Skip this one
            }
            return $purchaseItem->storeItem->name;
        })->filter()->toArray(); // Remove null values
        $itemsString = implode(', ', $itemNames);
        return $itemsString;
    }

    public function outgoingPayments()
    {
        return $this->morphMany(OutgoingPayment::class, 'payable');
    }
}
