<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Artist::all();
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
        // Validate dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'name.string'   => 'Tên phải là một chuỗi ký tự.',
            'name.max'      => 'Tên không được vượt quá 255 ký tự.',
            'bio.string'    => 'Thông tin cá nhân phải là một chuỗi ký tự.',
        ]);
        
        // Tạo nghệ sĩ mới
        $artist = Artist::create([
            'name' => $request->name,
            'bio' =>$request->bio
        ]);

        // Trả về phản hồi dưới dạng JSON
        return response()->json([
            'message' => 'Artist tạo nghệ sĩ thành công!',
            'artist' => $artist,
        ], 201); // Mã trạng thái HTTP 201 - Created
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
    public function edit(string $id) {}

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
    // Phương thức destroy để xóa nghệ sĩ theo ID
    public function destroy($id)
    {
        // Tìm nghệ sĩ theo ID
        $artist = Artist::find($id);

        // Nếu nghệ sĩ không tồn tại, trả về lỗi 404
        if (!$artist) {
            return response()->json([
                'message' => 'Artist không tìm thấy',
            ], 404);
        }

        // Xóa nghệ sĩ
        $artist->delete();

        // Trả về phản hồi thành công
        return response()->json([
            'message' => 'Artist đã xóa thành công!',
        ], 200);
    }
}
