<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    public function printCart($orderCartId)
    {
        $cart = session()->get('restaurant-order-cart', []);
        //get the items and print them

        // Check if the restaurant cart order ID exists in the session
        if (!array_key_exists($orderCartId, $cart)) {
            logger('Cart not found in session');
            return back();
        }

        $orderDetails = $cart[$orderCartId];

        $customerName = 'Walk In Customer';

        if (empty($orderDetails['order_info']['customer_id']) && !empty($orderDetails['order_info']['table_id'])) {
            $room = Room::find($orderDetails['order_info']['room_id']);
            if ($room) {
                // Assuming you have a relationship like $room->currentGuest or a way to get current guest
                $currentGuest = $room->currentGuest() ?? null; // You might need to implement this logic
                $guestName = $currentGuest?->name() ?? 'Walk In Guest';
            }
        } elseif (!empty($orderDetails['order_info']['customer_id'])) {
            $customer = Customer::find($orderDetails['order_info']['customer_id']);
            $customerName = $customer?->name() ?? 'Walk in Customer ';
        }

        $tableName = !empty($orderDetails['order_info']['table_id'])
            ? optional(Room::find($orderDetails['order_info']['room_id']))->name ?? 'N/A'
            : 'N/A';

        return theme_view('printers.print-cart')->with(compact('orderDetails', 'customerName', 'tableName'));
    }

    public function printOrder(Order $order)
    {
        // Load related menu items and payments
        // Format print layout
        // Route to appropriate printer
    }

    public function printKitchenSlip($orderCartId)
    {
        $cart = session()->get('restaurant-order-cart', []);
        //get the items and print them

        // Check if the restaurant cart order ID exists in the session
        if (!array_key_exists($orderCartId, $cart)) {
            logger('Cart not found in session');
            return back();
        }

        $orderDetails = $cart[$orderCartId];

        $customerName = 'Walk In Customer';

        if (empty($orderDetails['order_info']['customer_id']) && !empty($orderDetails['order_info']['table_id'])) {
            $room = Room::find($orderDetails['order_info']['room_id']);
            if ($room) {
                // Assuming you have a relationship like $room->currentGuest or a way to get current guest
                $currentGuest = $room->currentGuest() ?? null; // You might need to implement this logic
                $guestName = $currentGuest?->name() ?? 'Walk In Guest';
            }
        } elseif (!empty($orderDetails['order_info']['customer_id'])) {
            $customer = Customer::find($orderDetails['order_info']['customer_id']);
            $customerName = $customer?->name() ?? 'Walk in Customer ';
        }

        $tableName = !empty($orderDetails['order_info']['table_id'])
            ? optional(Room::find($orderDetails['order_info']['room_id']))->name ?? 'N/A'
            : 'N/A';

        return theme_view('printers.print-kitchen-slip')->with(compact('orderDetails', 'customerName', 'tableName'));
    }

    public function printReceipt(Order $order)
    {
        // Format receipt with payment info
        // Send to receipt printer
    }

    public function testPrinterConnection($printerId)
    {
        // Ping or test print
    }
}
