<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;


class AdminImagesController extends Controller {
    public function uploadSirTrevorImage(Request $request) {
        $filename = '';

        if ($request->hasFile('attachment')) {
            $data = $request->get('attachment');
            $filename = $data['uid'] . '-' . $data['name'];

            $file = $request->file('attachment');
            $file['file']->move(base_path() . config('images.imagesFolder'), $filename);
        }

        return json_encode(array('file' => array('url' => $filename)));
    }
}
