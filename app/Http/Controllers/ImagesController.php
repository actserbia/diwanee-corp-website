<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ImagesManager;


class ImagesController extends Controller {
    public function uploadSirTrevorImage(Request $request) {
        $imageUrl = '';

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $imageUrl = ImagesManager::upload($file['file']->getPathname());
        }

        return json_encode(array('file' => array('url' => $imageUrl)));
    }
}
