<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;

class Admin
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $current_route = $request->route()->getName();
        if($current_route == null){
            return redirect()->route('home');
        }
        if (strpos($current_route, 'create') !== false) {
            $parent_route = str_replace('create', 'index', $current_route);
            // dd(1,$parent_route);
            $method = 'create';
        } elseif (strpos($current_route, 'edit') !== false) {
            $parent_route = str_replace('edit', 'index', $current_route);
            $method = 'edit';
        } else {
            $parent_route = null;
            $method = 'index';
        }

        // if (!is_null(Auth::user())) {
        //     if (Auth::user()->is_root == false && $current_route != 'admin.index' && $current_route != 'admin.403') {
        //         $permissions = DB::table('role')->whereIn('id', explode(',', Auth::user()->role_id))->get()->pluck('permissons');
        //         if ($permissions) {
        //             foreach ($permissions as $permission) {
        //                 if (strpos($permission, $current_route) === false) {
        //                     //return view('admin.abort.403');
        //                     abort(403, 'Bạn không có quyền truy cập chức năng này');
        //                 }
        //             }
        //         } else {
        //             //return view('admin.abort.403');
        //             abort(403, 'Bạn không có quyền truy cập chức năng này');
        //         }
        //     }
        //     if ($parent_route == null) {
        //         $parent_route = $current_route;
        //     }

        //     \View::share(['current_route' => $current_route, 'parent_route' => $parent_route, 'method' => $method]);
        //     return $next($request);

        // }
        // else {
        //     return redirect()->route('auth.login');
        // }
        \View::share(['current_route' => $current_route, 'parent_route' => $parent_route, 'method' => $method]);
        return $next($request);
    }

}
