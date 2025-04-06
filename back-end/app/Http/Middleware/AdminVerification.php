<?php

namespace App\Http\Middleware;

use App\Models\Types;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
       
        $type = Types::where("user_id", $user->id)->first();

        if(!$user && !$type->type === "admin"){
            return response()->json(["message" => "Acesso Negado"],403);
        }
        return $next($request);
    }
}
