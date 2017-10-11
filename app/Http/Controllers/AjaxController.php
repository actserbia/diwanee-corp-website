<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Tag;


class AjaxController extends Controller {
    public function subcategories($category = 0) {
        if($category !== 0) {
            $tags = Tag::findOrFail($category)->children;
        } else {
            $tags = Tag::where('type', '=', 'subcategory')->get();
        }

        $tagsOutput = array();
        foreach($tags as $tag) {
            $tagsOutput[] = array(
                'id' => $tag->id,
                'name' => $tag->name
            );
        }

        return json_encode($tagsOutput);
    }
}
