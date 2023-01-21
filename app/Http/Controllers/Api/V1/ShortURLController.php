<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShortURLController extends Controller
{
    /**
     * Devuelve una URL acortada
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $content = $request->getContent();

        // Validación del contenido, si viene como JSON o HTML y con una URL válida
        $json_content = json_decode($content);
        if (json_last_error() === JSON_ERROR_NONE && property_exists($json_content,"url")){
            $url = trim($json_content->url);
        } elseif (substr($content,0,5) === "url: ") {
            $url = trim(substr($content,4));
        } else {
            return response([
                'error' => 'Formato incorrecto, el cuerpo de la petición debe ser url: string'
            ], 403);
        }

        if ($this->URLValidation($url)){
            // Petición a TinyURL para acortar la URL
            $response = Http::get('https://tinyurl.com/api-create.php?url='.$url);
        } else {
            return response([
                'error' => 'URL es requerido y debe tener formato correcto'
            ], 403);
        }

        return response([
            'url' => $response->body()
        ]);
    }

    /**
     * Validación por Expresión regular de la URL
     *
     * @param  string $url
     * @return bool
     */
    private function URLValidation($url){
        $url_regex = "/^(https?:\\/\\/)?(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)$/";
        return preg_match($url_regex,$url);
    }

}
