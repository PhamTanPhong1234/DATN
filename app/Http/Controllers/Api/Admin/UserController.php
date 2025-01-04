<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::all();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view("/* view tạo */")
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:cdsyncs_users',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],[
            'username.required' => 'Tên người dùng không được bỏ trống.',
            'username.string' => 'Tên người dùng phải là một chuỗi ký tự.',
            'username.max' => 'Tên người dùng không được vượt quá 255 ký tự.',
            'password.required' => 'Mật khẩu không được bỏ trống.',
            'password.string' => 'Mật khẩu phải là một chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'email.required' => 'Email không được bỏ trống.',
            'email.string' => 'Email phải là một chuỗi ký tự.',
            'email.email' => 'Email phải là một địa chỉ email hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'image.image' => 'Tệp phải là một hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng là jpeg, png, jpg, gif, hoặc svg.',
            'image.max' => 'Hình ảnh không được vượt quá 2048 kilobytes.',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public'); // Lưu ảnh vào thư mục 'images' trong 'storage/app/public'
        }else{
            $imagePath ='imagesavatar.jpg';
        }
        // Tạo người dùng mới với đường dẫn ảnh
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'social_id' => $request->social_id,
            'role' => $request->role,
            'image' => $imagePath, // Lưu đường dẫn ảnh vào cơ sở dữ liệu
        ]);
    
        return response()->json($user, 201); // Trả về người dùng mới với mã trạng thái 201
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $data = User::find($id);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại.'], 404);
        }
        
        // Trả về view với thông tin người dùng
        // return view('/* View nhận api/user */', compact('user'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Tìm người dùng theo ID
        $user = User::find($id);
        
        // Nếu không tìm thấy người dùng, trả về lỗi 404
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại.'], 404);
        }
    
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|min:8',
            'email' => 'sometimes|string|email|max:255|unique:cdsyncs_users,email,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'username.string' => 'Tên người dùng phải là một chuỗi ký tự.',
            'username.max' => 'Tên người dùng không được vượt quá 255 ký tự.',
            'password.string' => 'Mật khẩu phải là một chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'email.string' => 'Email phải là một chuỗi ký tự.',
            'email.email' => 'Email phải là một địa chỉ email hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'image.image' => 'Tệp phải là một hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng là jpeg, png, jpg, gif, hoặc svg.',
            'image.max' => 'Hình ảnh không được vượt quá 2048 kilobytes.',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        // Kiểm tra và lưu ảnh nếu có
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public'); // Lưu ảnh vào thư mục 'images' trong 'storage/app/public'
            $user->image = $imagePath; // Cập nhật đường dẫn ảnh
        }
    
        // Cập nhật các thông tin khác
        $user->username = $request->username ?? $user->username;
        $user->password = $request->password ? bcrypt($request->password) : $user->password;
        $user->email = $request->email ?? $user->email;
        $user->social_id = $request->social_id ?? $user->social_id;
        $user->role = $request->role ?? $user->role;
        $user->save();
    
        return response()->json($user, 200); // Trả về người dùng đã cập nhật với mã trạng thái 200
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }
    
        $user->delete();
    
        return response()->json(['message' => 'Xóa thành công']);
    }
}
