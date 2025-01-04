<?php

namespace App\Http\Controllers\Api\Admin;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ProductReview::all();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:cdsyncs_users,id',
            'product_id' => 'required|exists:cdsyncs_products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ], [
            'user_id.required' => 'Vui lòng cung cấp ID người dùng.',
            'user_id.exists' => 'Người dùng không tồn tại.',
            'product_id.required' => 'Vui lòng cung cấp ID sản phẩm.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'rating.required' => 'Vui lòng cung cấp đánh giá.',
            'rating.integer' => 'Đánh giá phải là một số nguyên.',
            'rating.min' => 'Đánh giá tối thiểu là 1.',
            'rating.max' => 'Đánh giá tối đa là 5.',
            'comment.required' => 'Vui lòng nhập nội dung bình luận.',
            'comment.string' => 'Bình luận phải là một chuỗi ký tự.',
            'comment.max' => 'Bình luận không được vượt quá 1000 ký tự.',
        ]);
        
        // Kiểm tra nếu xác thực không thành công
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Tạo mới ProductReview
        $productReview = ProductReview::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Cảm ơn bạn đã đánh giá',
            'data' => $productReview
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Tìm đánh giá sản phẩm theo ID
        $productReview = ProductReview::find($id);
        // Nếu không tìm thấy đánh giá sản phẩm
        if (!$productReview) {
            return response()->json(['message' => 'Không tìm thấy đánh giá sản phẩm.'], 404);
        }
        // Xóa đánh giá sản phẩm
        $productReview->delete();

        return response()->json(['message' => 'Đánh giá sản phẩm đã được xóa thành công.'], 200);
    }
}
