<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\Settings;
use Illuminate\Support\Facades\Validator;


class ImagesController extends Controller {
    public function uploadSirTrevorImage(Request $request) {
        $filename = '';

        if ($request->hasFile('attachment')) {
            $data = $request->get('attachment');
            $filename = $data['uid'] . '-' . $data['name'];

            $file = $request->file('attachment');
            $file['file']->move(base_path() . Settings::ImagesFolder, $filename);
        }

        return json_encode(array('file' => array('url' => Settings::ImagesSrc . $filename)));
    }


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
            $file->move(base_path() . Settings::ImagesFolder, $filename);
        }

        return json_encode(array('file' => array('url' => Settings::ImagesSrc . $filename)));
    }

    private function validator(array $data) {
        return Validator::make($data, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
    }
}
