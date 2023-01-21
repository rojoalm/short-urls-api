<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShorturlAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if ($this->TokenValidation($token)) {
            return $next($request);
        }

        return response([
            'message' => 'Error en token'
        ], 403);

    }


    /**
     * Valida si el token está bien formado con paréntesis balanceados
     *
     * @param  mixed $token
     * @return void
     */
    private function TokenValidation($token) {
        $token_array = str_split($token);
        $control = array();
        foreach($token_array as $value){
            switch ($value) {
                case '(':
                    array_push($control, 0);
                    break;
                case ')':
                    if (array_pop($control) !== 0) {
                        return false;
                    }
                    break;
                case '[':
                    array_push($control, 1);
                    break;
                case ']':
                    if (array_pop($control) !== 1){
                        return false;
                    }
                    break;
                case '{':
                    array_push($control, 2); break;
                case '}':
                    if (array_pop($control) !== 2){
                        return false;
                    }
                    break;
                default:
                    return false;
            }
        }
        return (empty($control));
    }
}
