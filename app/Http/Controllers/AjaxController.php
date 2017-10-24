<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Tag;


class AjaxController extends Controller {
    public function subcategories($category) {
        $tags = Tag::findOrFail($category)->children;

        $tagsOutput = array();
        foreach($tags as $tag) {
            $tagsOutput[] = array(
                'id' => $tag->id,
                'name' => $tag->name
            );
        }

        return json_encode($tagsOutput);
    }

    public function tagsByType($type) {
        $tags = Tag::where('type', '=', $type)->get();

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
