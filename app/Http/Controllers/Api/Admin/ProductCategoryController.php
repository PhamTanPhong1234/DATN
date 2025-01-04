<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Validator;
class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ProductCategory::all();
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
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.string' => 'Tên danh mục phải là một chuỗi.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả danh mục phải là một chuỗi.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Tạo mới một category
        $category = ProductCategory::create($request->only('name', 'description'));

        // Trả về phản hồi thành công
        return response()->json([
            'message' => 'Danh mục đã được tạo thành công!',
            'data' => $category,
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
    public function update(Request $request, $id)
    {
        // Tìm kiếm danh mục theo ID
        $category = ProductCategory::find($id);

        // Nếu không tìm thấy danh mục
        if (!$category) {
            return response()->json(['message' => 'Danh mục không tồn tại.'], 404);
        }

        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.string' => 'Tên danh mục phải là một chuỗi.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả danh mục phải là một chuỗi.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Cập nhật danh mục
        $category->update($request->only('name', 'description'));

        // Trả về phản hồi thành công
        return response()->json([
            'message' => 'Danh mục đã được cập nhật thành công!',
            'data' => $category,
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Tìm kiếm danh mục theo ID
        $category = ProductCategory::find($id);

        // Nếu không tìm thấy danh mục
        if (!$category) {
            return response()->json(['message' => 'Danh mục không tồn tại.'], 404);
        }

        // Xóa danh mục
        $category->delete();

        // Trả về phản hồi thành công
        return response()->json(['message' => 'Danh mục đã được xóa thành công!'], 200);
    }
}
