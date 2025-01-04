<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLoginAndRole
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

       
        if ($role && $user->role != $role) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}

?>