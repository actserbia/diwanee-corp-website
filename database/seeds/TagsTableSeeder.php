<?php

use Illuminate\Database\Seeder;

use App\Tag;
use Database\Seeds\TagsData;

class TagsTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        foreach(TagsData::$tags as $tagData) {
            $tag = new Tag;

            $tag->name = $tagData['name'];
            $tag->type = $tagData['type'];

            if(!empty($tagData['parentName'])) {
                $parentTag = Tag::where('name', $tagData['parentName'])->first();
                $tag->parentTag()->associate($parentTag);
            }

            $tag->save();
        }
    }
}
