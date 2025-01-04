<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{


    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $order = Order::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'pending'],
            ['total_amount' => 0]
        );

        $orderItem = OrderItem::updateOrCreate(
            ['order_id' => $order->id, 'product_id' => $request->product_id],
            ['quantity' => $request->quantity, 'price' => Product::find($request->product_id)->price]
        );

        $order->total_amount += $orderItem->price * $orderItem->quantity;
        $order->save();

        return response()->json(['message' => 'Item added to cart', 'order' => $order]);
    }

    public function viewCart()
    {
        $order = Order::where('user_id', Auth::id())->where('status', 'pending')->with('items.product')->first();
        
        if (!$order) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        return response()->json(['cart' => $order]);
    }

    public function updateCart(Request $request, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $orderItem = OrderItem::findOrFail($itemId);
        $orderItem->quantity = $request->quantity;
        $orderItem->save();

        $order = $orderItem->order;
        $order->total_amount = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $order->save();

        return response()->json(['message' => 'Cart updated', 'order' => $order]);
    }

    public function removeFromCart($itemId)
    {
        $orderItem = OrderItem::findOrFail($itemId);
        $order = $orderItem->order;

        $orderItem->delete();

        $order->total_amount = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $order->save();

        return response()->json(['message' => 'Item removed from cart', 'order' => $order]);
    }
}
