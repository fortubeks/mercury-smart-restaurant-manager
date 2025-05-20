<?php

namespace App\Services;

class RestaurantCartService
{
    public function __construct()
    {
        // Initialize any dependencies here
    }

    public function getRestaurantCartOrders()
    {
        $restaurantCart = session()->get('restaurant-order-cart', []);
        $restaurantCartOrders = [];
        if (!empty($restaurantCart)) {
            foreach ($restaurantCart as $restaurantCartOrderId => $restaurantCartData) {
                // Extract total amount (assuming it exists within 'order_info')
                $totalAmount = $restaurantCartData['order_info']['total_amount'] ?? null;

                // Extract items from the cart data
                $items = $restaurantCartData['items'];

                // Create a new entry for the current order with details
                $restaurantCartOrders[] = [
                    'restaurantCartOrderId' => $restaurantCartOrderId,
                    'totalAmount' => $totalAmount,
                    'items' => $items,
                ];
            }
        }
        return $restaurantCartOrders;
    }
}
