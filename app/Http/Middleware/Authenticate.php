<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    public function handle($request, \Closure $next, ...$guards)
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (Auth::check()) {
            return $next($request);
        }

        // Nếu người dùng chưa đăng nhập, bạn có thể thêm thông báo hoặc xử lý phù hợp
        return redirect()->route('login');
    }
}

?>