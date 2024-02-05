<?php

namespace App\Http\Controllers;

use OpenApi\Generator;

class OpenApiController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function __invoke()
    {
        $openApi = Generator::scan([
            app_path('Http/Controllers/Api')
        ]);

        return response()->json(json_decode($openApi->toJson(), false, 512, JSON_THROW_ON_ERROR));
    }
}
