<?php
/**
 * @SWG\Swagger(
 *   basePath="/api",
 *   schemes={"http"},
 * 	 produces={"application/json"},
 * 	 consumes={"application/json"},
 *   @SWG\Info(
 *     title="Diwanee Corp articles API",
 *     version="1.0.0",
 *     @SWG\Contact(name="info@diwanee.com"),
 *   ),
 *   security={{"api_key_security":{}}} *
 * )
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
