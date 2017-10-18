<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Constants\Settings;


class SirTrevorController extends Controller {
    public function images(Request $request) {
        $filename = '';

        if ($request->hasFile('attachment')) {
            $data = $request->get('attachment');
            $filename = $data['uid'] . '-' . $data['name'];

            $file = $request->file('attachment');
            $uploadFile = $file['file']->move(base_path() . Settings::ImagesFolder, $filename);
        }

        return json_encode(array('file' => array('url' => Settings::ImagesSrc . $filename)));
    }
}
