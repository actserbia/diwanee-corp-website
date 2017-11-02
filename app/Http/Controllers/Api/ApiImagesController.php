<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
     *     @SWG\Response(response="200", description="successful operation")
     * )
     * */
    public function uploadImage(Request $request) {
        $data = $request->all();

        $validator = $this->validator($data);
        if ($validator->fails()) {
            return json_encode(array('errors' => $validator->errors()->all()));
        }

        $filename = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(base_path() . config('images.imagesFolder'), $filename);
        }

        return json_encode(array('file' => array('url' => $filename)));
    }

    private function validator(array $data) {
        return Validator::make($data, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
    }
}
