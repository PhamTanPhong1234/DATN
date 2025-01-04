<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OrderItemController extends Controller
{
    // Lấy danh sách tất cả các mục đơn hàng
    public function index()
    {
        $orderItems = OrderItem::all();
        return response()->json($orderItems, 200);
    }

    // Tạo mục đơn hàng mới
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:cdsyncs_orders,id',
            'product_id' => 'required|exists:cdsyncs_products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ], [
            'order_id.required' => 'Trường order_id là bắt buộc.',
            'order_id.exists' => 'Order không tồn tại trong hệ thống.',
            
            'product_id.required' => 'Trường product_id là bắt buộc.',
            'product_id.exists' => 'Sản phẩm không tồn tại trong hệ thống.',
            
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là một số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
            
            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'price.numeric' => 'Giá sản phẩm phải là một số.',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Xử lý tiếp nếu không có lỗi...
        

        $orderItem = OrderItem::create($request->all());

        return response()->json($orderItem, 201);
    }

    // Hiển thị thông tin chi tiết của một mục đơn hàng
    public function show($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        return response()->json($orderItem, 200);
    }

    // Cập nhật mục đơn hàng
    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);

        $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric',
        ]);

        $orderItem->update($request->only(['quantity', 'price']));

        return response()->json($orderItem, 200);
    }

    // Xóa mục đơn hàng
    public function destroy($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->delete();

        return response()->json(['message' => 'Order item deleted successfully'], 200);
    }
}

