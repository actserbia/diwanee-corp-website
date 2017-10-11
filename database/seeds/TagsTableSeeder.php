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

            if(!empty($tagData['parents'])) {
                $index = 0;
                foreach ($tagData['parents'] as $parentName) {
                    $parent = Tag::where('name', $parentName)->first();
                    $tag->parents()->save($parent, ['ordinal_number' => $index++]);
                }
            }
        }
    }
}
