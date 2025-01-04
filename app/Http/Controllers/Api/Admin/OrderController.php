<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    // 1. Tạo đơn hàng mới
    public function store(Request $request) {
        // Kiểm tra dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Kiểm tra user_id phải tồn tại trong bảng users
            'total_amount' => 'required|numeric|min:0', // Kiểm tra total_amount là số dương
            'status' => 'nullable|string|in:pending,processed,shipped,delivered', // Kiểm tra status phải trong các giá trị cho phép
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // Trả về lỗi nếu có
        }

        // Tạo đơn hàng mới
        $order = Order::create($request->only(['user_id', 'total_amount', 'status'])); // Chỉ lấy các trường cần thiết
        return response()->json($order, 201); // Trả về đơn hàng mới được tạo với mã trạng thái 201
    }

    // 2. Lấy danh sách đơn hàng
    public function index() {
        $orders = Order::all(); // Lấy tất cả đơn hàng
        return response()->json($orders); // Trả về danh sách đơn hàng
    }

    // 3. Cập nhật đơn hàng
    public function update(Request $request, $id) {
        // Kiểm tra dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:CDSyncs_users,id', // Kiểm tra user_id nếu có
            'total_amount' => 'sometimes|required|numeric|min:0', // Kiểm tra total_amount nếu có
            'status' => 'sometimes|nullable|string|in:pending,processed,shipped,delivered', // Kiểm tra status nếu có
        ], [
            'user_id.required' => 'User ID là bắt buộc.',
            'user_id.exists' => 'User ID không tồn tại trong hệ thống.',
            'total_amount.required' => 'Tổng số tiền là bắt buộc.',
            'total_amount.numeric' => 'Tổng số tiền phải là một số.',
            'total_amount.min' => 'Tổng số tiền phải lớn hơn hoặc bằng 0.',
            'status.string' => 'Trạng thái phải là một chuỗi ký tự.',
            'status.in' => 'Trạng thái phải thuộc một trong các giá trị: pending, processed, shipped, delivered.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // Trả về lỗi nếu có
        }

        // Tìm và cập nhật đơn hàng
        $order = Order::findOrFail($id); // Tìm đơn hàng theo ID
        $order->update($request->only(['user_id', 'total_amount', 'status'])); // Cập nhật thông tin đơn hàng
        return response()->json($order); // Trả về đơn hàng đã cập nhật
    }

    // 4. Xóa đơn hàng
    public function destroy($id) {
        // Xóa đơn hàng
        Order::destroy($id); // Xóa đơn hàng theo ID
        return response()->json(null, 204); // Trả về mã trạng thái 204 (No Content)
    }

    // 5. Chi tiết đơn hàng
    public function show($id) {
        // Lấy chi tiết đơn hàng
        $order = Order::findOrFail($id); // Tìm đơn hàng theo ID
        return response()->json($order); // Trả về chi tiết đơn hàng
    }
}
