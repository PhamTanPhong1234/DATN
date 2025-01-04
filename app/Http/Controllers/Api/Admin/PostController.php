<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    // Phương thức lấy danh sách bài viết
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts, Response::HTTP_OK);
    }

    // Phương thức tạo bài viết mới
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:100',
            'status' => 'in:draft,published'
        ]);

        // Tạo bài viết
        $post = Post::create($validatedData);
        return response()->json($post, Response::HTTP_CREATED);
    }

    // Phương thức xem chi tiết bài viết
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($post, Response::HTTP_OK);
    }

    // Phương thức cập nhật bài viết
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        // Validate dữ liệu
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'author' => 'sometimes|required|string|max:100',
            'status' => 'in:draft,published'
        ]);

        // Cập nhật bài viết
        $post->update($validatedData);
        return response()->json($post, Response::HTTP_OK);
    }

    // Phương thức xóa bài viết
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted'], Response::HTTP_OK);
    }
}
