<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants\Models;
use App\Models\Node\NodeModelManager;
use Auth;


class Node extends AppModel {
    use SoftDeletes;
    use NodeModelManager;

    protected $allAttributesFields = ['id', 'title', 'status', 'node_type_id', 'author_id', 'created_at', 'updated_at', 'deleted_at', 'elements_count'];

    protected $fillable = ['title', 'status', 'node_type_id', 'author_id'];

    protected $requiredFields = ['title', 'status', 'node_type_id'];
    
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

    protected $attributeType = [
        'status' => Models::AttributeType_Enum,
        'author_id' => Models::AttributeType_Number,
        'elements_count' => Models::AttributeType_Number,
        'node_type_id' => Models::AttributeType_Number
    ];

    protected $defaultDropdownColumn = 'title';

    protected $relationsSettings = [
        'node_type' => [
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
            'sortBy' => 'ordinal_number',
            'automaticSave' => false
        ],
        'elements' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Element',
            'pivot' => 'node_element',
            'foreignKey' => 'node_id',
            'relationKey' => 'element_id',
            'sortBy' => 'ordinal_number',
            'automaticSave' => false
        ]
    ];

    protected $multipleFields = [
        'tags' => true,
        'elements' => true
    ];
    
    protected $nodeType = null;

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

            $this->elements()->updateExistingPivot($element->id, ['ordinal_number' => $index + 1]);
        } else {
            $element = Element::create($preparedElementData);
            $this->elements()->attach($element->id, ['ordinal_number' => $index + 1]);
        }
    }

    public function changeFormat($toHtml = false) {
        Element::formatElements($this->elements, $toHtml);
    }

    public static function formatArticles($nodes, $jsonEncode = true, $toHtml = false) {
        foreach($nodes as $node) {
            Element::formatElements($node->elements, $jsonEncode, $toHtml);
        }
    }
    
    public function modelTypeIdValue() {
        return isset($this->nodeType->id) ? $this->nodeType->id : '';
    }
    
    protected function getFilterFields() {
        $filterFields = [];
        if(isset($this->relationsSettings['additional_data'])) {
            $relationFilterFields = $this->getRelationModel('additional_data')->getFilterFields();
            foreach($relationFilterFields as $relationFilterField => $visibility) {
                $filterFields['additional_data' . ':' . $relationFilterField] = $visibility;
            }
            
            foreach(array_keys($this->relationsSettings) as $relation) {
                if($this->checkRelationType($relation, 'App\\Node', 'tags')) {
                    $filterFields[$relation . ':name'] = true;
                }
            }
            
            $filterFields = array_merge($filterFields, $this->filterFields);
        }
        return $filterFields;
    }
}