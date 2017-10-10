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
        DB::table('tag_parent')->delete();
        DB::table('tags')->delete();

        foreach(TagsData::$tags as $tagData) {
            $tag = new Tag;

            $tag->name = $tagData['name'];
            $tag->type = $tagData['type'];
            $tag->save();

            //one parent
            //if(!empty($tagData['parentName'])) {
                //$parentTag = Tag::where('name', $tagData['parentName'])->first();
                //$tag->parentTag()->associate($parentTag);
            //}

            //more parents
            if(!empty($tagData['parents'])) {
                $index = 0;
                $tag->parents()->detach();
                foreach ($tagData['parents'] as $parentName) {
                    $parent = Tag::where('name', $parentName)->first();
                    $tag->parents()->save($parent, ['ordinal_number' => $index++]);
                }
            }
        }
    }
}
