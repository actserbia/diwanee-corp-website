<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Article;
use App\Tag;
use DB;
use App\Models\Traits\MultipleTags;
use App\Models\Traits\BaseFilters;
use App\Models\Traits\Pagination;


class Tag extends Model {

    use SoftDeletes;
    use MultipleTags;
    use BaseFilters;
    use Pagination;

    protected $fillable = [
        'name', 'type'
    ];

    public function articles() {
        return $this->belongsToMany(Article::class, 'article_tag', 'id_tag', 'id_article');
    }
    
    public function children() {
        return $this->belongsToMany(Tag::class, 'tag_parent', 'id_parent', 'id_tag');
    }

    public function parents() {
        return $this->belongsToMany(Tag::class, 'tag_parent', 'id_tag', 'id_parent');
    }


    public function saveTag(array $data) {
        DB::beginTransaction();
        try {
            $this->fill($data);
            $this->save();

            $this->saveTags($data, 'parents');
            $this->saveTags($data, 'children');

            DB::commit();
            return true;

        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function saveTags(array $data, $tagsName) {
        $newTagsIds = isset($data[$tagsName]) ? $data[$tagsName] : array();

        $this->changeTags($newTagsIds, $tagsName, true);
    }
}
