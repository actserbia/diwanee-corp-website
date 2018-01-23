<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Models;
use Illuminate\Support\Facades\DB;
use App\Models\ModelDataManager;
use App\Constants\NodeStatus;


class Node extends Model
{
    use SoftDeletes;
    use ModelDataManager;

    protected $fields = ['id', 'title', 'meta_title', 'meta_description', 'status', 'type_id', 'author_id', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['title', 'meta_title', 'meta_description', 'status', 'type_id', 'author_id'];

    protected $required = ['title', 'status', 'type_id'];

    protected $attributeType = [
        'status' => Models::AttributeType_Enum,
        'author_id' => Models::AttributeType_Number
    ];

    protected $defaultDropdownColumn = 'title';

    protected $relationsSettings = [
//        'tags' => [
//            'relationType' => 'belongsToMany',
//            'model' => 'App\\Tag',
//            'pivot' => 'node_tag',
//            'foreignKey' => 'node_id',
//            'relationKey' => 'tag_id',
//            'sortBy' => 'ordinal_number'
//        ],
//
//        'elements' => [
//            'relationType' => 'belongsToMany',
//            'model' => 'App\\Element',
//            'pivot' => 'node_element',
//            'foreignKey' => 'node_id',
//            'relationKey' => 'element_id'
//        ],
        'author' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\User',
            'foreignKey' => 'author_id'
        ],
        'type' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\Type',
            'foreignKey' => 'type_id'
        ]
    ];

    //protected $multiple = ['tags', 'elements'];

    public function scopeWithActive($query) {
        $query->whereIn('status', NodeStatus::activeStatuses);
    }

    public function getEditorContentAttribute() {
        $data = array();

        foreach($this->elements as $element) {
            $data[] = $element->editorContent;
        }

        $content['data'] = $data;

        return json_encode($content);
    }


    public function saveNode(array $data) {
        DB::beginTransaction();
        try {
            $this->fill($data);
            $this->save();

            //$this->saveElements($data);
            //$this->saveTags($data);

            DB::commit();
            return true;

        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function saveElements(array $data) {
        $content = json_decode(str_replace("'", "\"", $data['content']), true);

        foreach($this->elements as $element) {
            $contentElement = array_filter($content['data'], function($el) use ($element) {
                return isset($el['data']['id']) && $el['data']['id'] === $element->id;
            });
            if(empty($contentElement)) {
                $this->elements()->detach($element->id);
                Element::find($element->id)->delete();
            }
        }

        foreach($content['data'] as $index => $elementData) {
            if(!empty($elementData['data'])) {
                $this->saveElement($elementData, $index);
            }
        }

        $this->load('elements');
    }

    private function saveElement($elementData, $index) {
        $preparedElementData = Element::prepareElementData($elementData);

        if(isset($elementData['data']['id'])) {
            $element = $this->elements()->where('id_element', '=', $elementData['data']['id'])->first();
            $element->update($preparedElementData);

            $this->elements()->updateExistingPivot($element->id, ['ordinal_number' => $index + 1]);
        } else {
            $element = Element::create($preparedElementData);
            $this->elements()->attach($element->id, ['ordinal_number' => $index + 1]);
        }
    }

    private function saveTags(array $data) {
        foreach(TagType::getAll() as $type) {
            $this->saveRelationItems($data[$type], $type);
        }
    }

    public function changeFormat($toHtml = false) {
        Element::formatElements($this->elements, $toHtml);
    }

    public static function formatNodes($nodes, $jsonEncode = true, $toHtml = false) {
        foreach($nodes as $node) {
            Element::formatElements($node->elements, $jsonEncode, $toHtml);
        }
    }
}
