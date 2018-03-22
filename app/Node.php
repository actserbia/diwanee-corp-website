<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Models;
use App\Models\Node\NodeModelManager;
use App\Models\ModelParentingTagsManager;
use Auth;
use App\Constants\ElementType;
use App\Constants\NodeStatus;
use App\Elastic\Searchable;


class Node extends AppModel {
    use SoftDeletes;
    use NodeModelManager;
    use ModelParentingTagsManager;
    use Searchable;


    protected $allAttributesFields = ['id', 'title', 'status', 'node_type_id', 'author_id', 'created_at', 'updated_at', 'deleted_at', 'elements_count'];

    protected $fillable = ['title', 'status', 'node_type_id', 'author_id'];

    protected $requiredFields = ['title', 'status', 'model_type'];
    
    protected $defaultFieldsValues = [
        'status' => NodeStatus::Unpublished
    ];

    protected $filterFields = [
        'id' => false,
        'title' => true,
        'status' => true,
        'tags:tag_id' => false,
        'tags:name' => false,
        'tags:created_at' => false,
        'tags:updated_at' => false,
        'tags:deleted_at' => false,
        'created_at' => true,
        'updated_at' => true,
        'deleted_at' => false,
        'author_id' => false,
        'author:name' => true,
        'author:email' => true,
        'author:role' => true,
        'author:active' => false,
        'author:api_token' => false,
        'author:created_at' => false,
        'author:updated_at' => false,
        'author:deleted_at' => false
    ];
    
    protected $statisticFields = [
        'status',
        'created_at',
        'author:name',
        'author:email',
        'elements:data:heading_type',
        'elements:data:source'
    ];

    protected $attributeType = [
        'status' => Models::AttributeType_Enum,
        'author_id' => Models::AttributeType_Number,
        'elements_count' => Models::AttributeType_Number,
        'node_type_id' => Models::AttributeType_Number
    ];

    protected $representationField = 'title';

    protected $relationsSettings = [
        'model_type' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\NodeType',
            'foreignKey' => 'node_type_id'
        ],
        'author' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\User',
            'foreignKey' => 'author_id'
        ],
        'tags' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Tag',
            'pivot' => 'node_tag',
            'foreignKey' => 'node_id',
            'relationKey' => 'tag_id',
            'pivotSortBy' => 'pivot_ordinal_number',
            'automaticSave' => false,
            'formHierarchy' => true
        ],
        'elements' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Element',
            'pivot' => 'node_element',
            'foreignKey' => 'node_id',
            'relationKey' => 'element_id',
            'pivotSortBy' => 'pivot_ordinal_number',
            'automaticSave' => false
        ],
        'parent_elements' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Element',
            'pivot' => 'element_item',
            'foreignKey' => 'item_id',
            'relationKey' => 'element_id',
            'filters' => ['type' => [ElementType::DiwaneeNode]],
            'automaticSave' => false
        ]
    ];

    protected $multipleFields = [
        'tags' => true,
        'elements' => true,
        'parent_elements' => true
    ];

    protected static $modelTypeField = 'node_type_id';
    protected $modelTypeRelation = 'model_type';
    
    protected $editable = true;
    
    public function getEditorContentAttribute() {
        $data = array();
        
        foreach($this->elements as $element) {
            $data[] = $element->editorContent;
        }

        $content['data'] = $data;

        return json_encode($content);
    }

    public function saveData(array $data) {
        $data['author'] = isset($this->id) ? $this->author->id : Auth::id();

        parent::saveData($data);
        
        $this->saveElements($data);
    }

    private function saveElements(array $data) {
        $content = json_decode(str_replace("'", "\"", $data['content']), true);

        foreach($this->elements as $element) {
            $contentElement = array_filter($content['data'], function($el) use ($element) {
                return isset($el['data']['id']) && $el['data']['id'] === $element->id;
            });
            if(empty($contentElement)) {
                $element->element_item()->detach();

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
            $element = $this->elements()->where('element_id', '=', $elementData['data']['id'])->first();

            $element->update($preparedElementData);

            $this->elements()->updateExistingPivot($element->id, ['pivot_ordinal_number' => $index + 1]);
        } else {
            $element = Element::create($preparedElementData);

            $this->elements()->attach($element->id, ['pivot_ordinal_number' => $index + 1]);
        }

        $element->saveItems($elementData);
    }

    public function changeFormat($toHtml = false) {
        Element::formatElements($this->elements, $toHtml);
    }

    public static function formatArticles($nodes, $jsonEncode = true, $toHtml = false) {
        foreach($nodes as $node) {
            Element::formatElements($node->elements, $jsonEncode, $toHtml);
        }
    }
    
    protected function getFilterFields() {
        $fields = [];
        if(isset($this->relationsSettings['additional_fields'])) {
            $relationFields = $this->getRelationModel('additional_fields')->getFilterFields();
            foreach($relationFields as $relationField => $visibility) {
                $fields['additional_fields' . ':' . $relationField] = $visibility;
            }
            
            foreach(array_keys($this->relationsSettings) as $relation) {
                if($this->checkRelationType($relation, 'tags')) {
                    $fields[$relation . ':name'] = true;
                }
            }
            
            $fields = array_merge($fields, $this->filterFields);
        }
        return $fields;
    }
    
    protected function getStatisticFields() {
        $fields = [];
        if(isset($this->relationsSettings['additional_fields'])) {
            foreach(array_keys($this->relationsSettings) as $relation) {
                if($this->checkRelationType($relation, 'tags')) {
                    $fields[] = $relation . ':name';
                }
            }
            
            $fields = array_merge($fields, $this->statisticFields);
        }
        return $fields;
    }

    public function deleteData() {
        foreach($this->parent_elements as $parentElement) {
            $parentElement->element_item()->detach();
            $parentElement->nodes()->detach();
            $parentElement->delete();
        }

        $this->delete();
    }
    
    public function scopeWithActive($query) {
        $query->whereIn('status', NodeStatus::activeStatuses);
    }
}