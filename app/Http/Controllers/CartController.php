<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function edit(Request $request)
    {
        // Retrieve the current cart items from the session
        $cart = session()->get('restaurant-order-cart', []);

        $user = auth()->user();

        $orderCartId = $request->id;
        // Check if the specified order ID exists in the cart
        if (!array_key_exists($orderCartId, $cart)) {
            // Cart doesn't exist, redirect to index with a message
            return redirect('orders')->with('error', 'Cart not found!');
        }

        // Cart exists, get the cart data for the order ID
        $cartData = $cart[$orderCartId];

        $outletId = $user->outlet_id;

        //get the outlet items
        $menuItems = MenuItem::where('outlet_id', $outletId)->where('is_available', true)->orderBy('name', 'asc')->get();

        //check if there is a sales record for the selected date. so that if there is, hide the submit buttons
        $dailySalesRecord = DailySale::where('restaurant_id', restaurantId())->where('shift_date', $user->current_shift)->first();

        // Return the edit page view for the cart
        return theme_view('orders.create')->with(compact('menuItems', 'outletId', 'orderCartId', 'dailySalesRecord', 'cartData'));
    }

    public function add(Request $request)
    {
        $itemId = $request->input('item_id');
        $quantity = $request->input('quantity', 1); // Default quantity is 1
        $cartOrderId = $request->input('order_cart_id'); // Get the order ID

        // Get the item details
        $item = MenuItem::find($itemId);
        if (!$item) {
            return response()->json(['status' => 'error', 'msg' => 'Item not found'], 404);
        }

        // Retrieve current cart from session
        $cart = session()->get('restaurant-order-cart', []);

        // Ensure order exists in cart
        if (!isset($cart[$cartOrderId])) {
            $cart[$cartOrderId] = [
                'items' => [],
                'order_info' => [],
            ];
        }

        // Current quantity in cart
        $currentQuantity = $cart[$cartOrderId]['items'][$itemId]['quantity'] ?? 0;
        $newTotalQuantity = $currentQuantity + $quantity;

        // Ensure stock is available
        if ($newTotalQuantity > $item->quantity) {
            return response()->json(['status' => 'error', 'msg' => 'Insufficient stock'], 422);
        }

        // Calculate amount and tax
        $amount = $newTotalQuantity * $item->price;
        $tax = calculateTaxAmount($amount);
        $totalAmount = calculateTotalBillAmount($amount);

        // Update cart item
        $cart[$cartOrderId]['items'][$itemId] = [
            'name' => $item->name,
            'quantity' => $newTotalQuantity,
            'price' => $item->price,
            'amount' => $amount,
            'tax' => $tax,
            'total' => $totalAmount
        ];

        // Calculate order totals
        $subTotal = array_sum(array_column($cart[$cartOrderId]['items'], 'amount'));
        $totalTax = array_sum(array_column($cart[$cartOrderId]['items'], 'tax'));
        $grandTotal = calculateTotalBillAmount($subTotal);

        // Update order info
        $cart[$cartOrderId]['order_info'] = [
            'sub_total' => $subTotal,
            'tax_amount' => $totalTax,
            'total_amount' => $grandTotal
        ];

        // Store updated cart in session
        session()->put('restaurant-order-cart', $cart);

        // Return the updated cart
        return response()->json(['status' => 'success', 'cart' => json_encode($cart[$cartOrderId])]);
    }

    public function update(Request $request)
    {
        $itemId = $request->input('item_id');
        $quantity = $request->input('quantity');
        $price = $request->input('price');
        $cartOrderId = $request->input('order_cart_id');

        // Get the cart from session
        $cart = session()->get('restaurant-order-cart', []);

        // Check if order exists in cart
        if (!isset($cart[$cartOrderId])) {
            return response()->json(['status' => 'error', 'msg' => 'Order not found'], 404);
        }

        // Check if item exists in the cart
        if (!isset($cart[$cartOrderId]['items'][$itemId])) {
            return response()->json(['status' => 'error', 'msg' => 'Item not found in cart'], 404);
        }

        // Get item details
        $item = MenuItem::find($itemId);
        if (!$item) {
            return response()->json(['status' => 'error', 'msg' => 'Item does not exist'], 404);
        }

        // Validate stock availability
        if ($quantity > $item->quantity) {
            return response()->json(['status' => 'error', 'msg' => 'Insufficient stock'], 422);
        }

        // Validate price (optional: ensure price does not exceed the actual price)
        if ($price > calculateTotalBillAmount($item->price)) {
            return response()->json(['status' => 'error', 'msg' => 'Invalid price'], 422);
        }

        // Update cart item
        $cart[$cartOrderId]['items'][$itemId]['quantity'] = $quantity;
        $cart[$cartOrderId]['items'][$itemId]['price'] = $price;
        $cart[$cartOrderId]['items'][$itemId]['amount'] = $price * $quantity;
        $cart[$cartOrderId]['items'][$itemId]['tax'] = calculateTaxAmount($cart[$cartOrderId]['items'][$itemId]['amount']);
        $cart[$cartOrderId]['items'][$itemId]['total'] = calculateTotalBillAmount($cart[$cartOrderId]['items'][$itemId]['amount']);

        // Recalculate order totals
        $subTotal = array_sum(array_column($cart[$cartOrderId]['items'], 'amount'));
        $totalTax = array_sum(array_column($cart[$cartOrderId]['items'], 'tax'));
        $grandTotal = calculateTotalBillAmount($subTotal);

        // Update order info
        $cart[$cartOrderId]['order_info'] = [
            'sub_total' => $subTotal,
            'tax_amount' => $totalTax,
            'total_amount' => $grandTotal
        ];

        // Store updated cart in session
        session()->put('restaurant-order-cart', $cart);

        // Return updated cart
        return response()->json(['status' => 'success', 'cart' => json_encode($cart[$cartOrderId])]);
    }

    public function remove(Request $request)
    {
        $itemId = $request->input('item_id');
        $cartOrderId = $request->input('order_cart_id');

        // Get the cart from session
        $cart = session()->get('restaurant-order-cart', []);

        // Check if order exists in cart
        if (!isset($cart[$cartOrderId])) {
            return response()->json(['status' => 'error', 'msg' => 'Order not found'], 404);
        }

        // Check if item exists in the cart
        if (!isset($cart[$cartOrderId]['items'][$itemId])) {
            return response()->json(['status' => 'error', 'msg' => 'Item not found in cart'], 404);
        }

        // Remove the item from the order
        unset($cart[$cartOrderId]['items'][$itemId]);

        // Recalculate order totals
        $subTotal = array_sum(array_column($cart[$cartOrderId]['items'], 'amount'));
        $totalTax = array_sum(array_column($cart[$cartOrderId]['items'], 'tax'));
        $grandTotal = calculateTotalBillAmount($subTotal);

        // Update order info
        $cart[$cartOrderId]['order_info'] = [
            'sub_total' => $subTotal,
            'tax_amount' => $totalTax,
            'total_amount' => $grandTotal
        ];

        // Store updated cart in session
        session()->put('restaurant-order-cart', $cart);

        // Return updated cart
        return response()->json([
            'status' => 'success',
            'cart' => json_encode($cart[$cartOrderId])
        ]);
    }

    public function clear(Request $request)
    {
        $cart = session()->get('restaurant-order-cart', []);
        $cartOrderId = $request->input('order_cart_id');

        if (array_key_exists($cartOrderId, $cart)) {
            // Remove the item from the cart
            $cart[$cartOrderId]['items'] = [];

            // Update the total amount to reflect the removal of items (optional)
            $cart[$cartOrderId]['order_info']['total_amount'] = 0;
            $cart[$cartOrderId]['order_info']['sub_total'] = 0;

            // Store the updated cart in the session
            session()->put('restaurant-order-cart', $cart);

            // Return the updated cart data
            // Return updated cart
            return response()->json([
                'status' => 'success',
                'cart' => json_encode($cart[$cartOrderId])
            ]);
        }
    }

    public function delete(Request $request)
    {
        $cart = session()->get('restaurant-order-cart', []);
        $cartOrderId = $request->input('order_cart_id');

        if (array_key_exists($cartOrderId, $cart)) {
            // Remove the item from the cart
            unset($cart[$cartOrderId]);

            // Store the updated cart in the session
            session()->put('restaurant-order-cart', $cart);
            return redirect('orders')->with('success', 'Cart has been successfully deleted');
        }
        return redirect('orders')->with('error', 'Cart not found');
    }
}
