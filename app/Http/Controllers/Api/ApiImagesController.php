<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Utils\ImagesManager;

class ApiImagesController extends Controller {
    /**
     * @SWG\Post(
     *     path="/upload-image",
     *     tags={"articles"},
     *     consumes={"multipart/form-data"},
     *     summary="uploads an image",
     *     operationId="uploadImage",
     *     @SWG\Parameter(
     *         description="file to upload",
     *         in="formData",
     *         name="image",
     *         required=true,
     *         type="file"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(response=200, description="successful operation"),
     *     @SWG\Response(response=400, description="validation error")
     * )
     * */
    public function uploadImage(Request $request) {
        $data = $request->all();

        $this->validator($data)->validate();

        $filename = '';
        if ($request->hasFile('image')) {
            $filename = ImagesManager::upload($request->file('image')->getPathname());
        }

        return json_encode(array('file' => array('url' => $filename)));
    }

    private function validator(array $data) {
        return Validator::make($data, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
    }
}
