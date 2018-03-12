<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use App\Constants\Models;
use App\Constants\FieldTypeCategory;
use App\Constants\NodeListRelationType;
use App\Constants\ElementType;
use App\Models\ModelParentingTagsManager;
use App\Models\ModelsUtils;
use Auth;
use Request;

class NodeList extends AppModel {
    use SoftDeletes;
    use ModelParentingTagsManager;
    
    protected $allAttributesFields = ['id', 'name', 'node_type_id', 'order_by_field_id', 'order', 'limit', 'author_id', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['name', 'node_type_id', 'order_by_field_id', 'order', 'limit', 'author_id'];

    protected $requiredFields = ['name', 'node_type', 'limit'];
    
    protected $defaultFieldsValues = [
        'order' => '0'
    ];

    protected $filterFields = [
        'id' => false,
        'name' => true,
        'node_type:name' => true,
        'order_by_field:title' => true,
        'order' => true,
        'limit' => true,
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
        'node_type_id' => Models::AttributeType_Number,
        'order_by_field_id' => Models::AttributeType_Number,
        'author_id' => Models::AttributeType_Number,
        'order' => Models::AttributeType_Enum,
        'limit' => Models::AttributeType_Number
    ];

    protected $defaultDropdownColumn = 'name';

    protected $relationsSettings = [
        'node_type' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\NodeType',
            'foreignKey' => 'node_type_id'
        ],
        'order_by_field' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\Field',
            'foreignKey' => 'order_by_field_id',
            'filters' => ['field_type.category' => [FieldTypeCategory::GlobalAttribute, FieldTypeCategory::Attribute]]
        ],
        'filter_tags' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Tag',
            'pivot' => 'node_list_relation',
            'foreignKey' => 'node_list_id',
            'relationKey' => 'relation_id',
            'pivotFilters' => ['type' => [NodeListRelationType::Tag]],
            'parenting' => true
        ],
        'filter_authors' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\User',
            'pivot' => 'node_list_relation',
            'foreignKey' => 'node_list_id',
            'relationKey' => 'relation_id',
            'pivotFilters' => ['type' => [NodeListRelationType::Author]]
        ],
        'author' => [
            'relationType' => 'belongsTo',
            'model' => 'App\\User',
            'foreignKey' => 'author_id'
        ],
        'parent_elements' => [
            'relationType' => 'belongsToMany',
            'model' => 'App\\Element',
            'pivot' => 'element_item',
            'foreignKey' => 'item_id',
            'relationKey' => 'element_id',
            'filters' => ['type' => [ElementType::DiwaneeList]],
            'automaticSave' => false
        ]
    ];
    
    protected $multipleFields = [
        'filter_tags' => true,
        'filter_authors' => true,
        'parent_elements' => true
    ];
    
    protected $dependsOn = [
        'order_by_field' => ['node_type']
    ];
    
    protected static $modelTypeField = 'node_type_id';
    
    public function populateData($attributes = null) {
        $this->defaultFieldsValues['order_by_field'] = config('app')['default_list_order_by_field_id'];
        
        if(isset($this->id) || isset($attributes['model_type_id'])) {
            $this->modelType = isset($this->id) ? $this->node_type : NodeType::find($attributes['model_type_id']);
            $this->populateTagFieldsData();
        }
    }

    private function populateTagFieldsData() {
        $tagFieldsRelationName = FieldTypeCategory::Tag . '_fields';
        foreach($this->modelType->$tagFieldsRelationName as $tagField) {
            $this->populateTagFieldData($tagField);
        }
    }

    private function populateTagFieldData($tagField) {
        if($tagField->pivot->active) {
            $relationSettings = [
                'parent' => 'filter_tags',
                'filters' => ['tag_type_id' => [$tagField->field_type_id]],
                'automaticRender' => true,
                'automaticSave' => true
            ];
            $this->relationsSettings[$tagField->formattedTitle] = $relationSettings;
            $this->multipleFields[$tagField->formattedTitle] = true;
        }
    }


    public function orderByFieldRelationValues($dependsOnValues = null) {
        $nodeType = $this->getDependsOnValue('node_type', $dependsOnValues);
        
        $globalFieldTypes = FieldType::where('category', '=', FieldTypeCategory::GlobalAttribute)->get();
        
        $fields = new Collection([]);
        foreach($globalFieldTypes as $globalFieldType) {
            $fields = $fields->merge($globalFieldType->fields);
        }
        
        return isset($nodeType->id) ? $fields->merge($nodeType->attribute_fields) : $fields;
    }
    
    public function getItemsAttribute() {
        $additionalDataTable = $this->node_type->additionalDataTableName;
        $items = Node::join($this->node_type->additionalDataTableName, 'nodes.id', '=', $additionalDataTable . '.node_id')->where('node_type_id', '=', $this->node_type->id);
        
        if(count($this->filter_tags) > 0) {
            $items = $this->addFilterByTagsToItemsQuery($items);
        }
        
        if(count($this->filter_authors) > 0) {
            $items = $items->whereIn('author_id', ModelsUtils::getItemsFieldsList($this->filter_authors, 'id'));
        }
        
        if(isset($this->order_by_field->id)) {
            if($this->order_by_field->fieldTypeCategory === FieldTypeCategory::GlobalAttribute) {
                $items = $items->orderBy('nodes' . '.' . $this->order_by_field->formattedTitle, $this->order ? 'asc' : 'desc');
            } else {
                $items = $items->orderBy($additionalDataTable . '.' . $this->order_by_field->formattedTitle, $this->order ? 'asc' : 'desc');
            }
        }
        
        return $items->limit($this->limit)->get();
    }
    
    private function addFilterByTagsToItemsQuery($items) {
        $filterTagIds = [];
        $tagIds = ModelsUtils::getItemsFieldsList($this->filter_tags, 'id');
        foreach($this->filter_tags as $tag) {
            if(empty(array_intersect(ModelsUtils::getItemsFieldsList($tag->children, 'id'), $tagIds))) {
                $filterTagIds[] = $tag->id;
            }
        }
        
        return $items->where(function ($query) use($filterTagIds) {
            foreach($filterTagIds as $filterTagId) {
                $query->orWhereHas('tags', function($q) use ($filterTagId) {
                    $q->where('tag_id', '=', $filterTagId);
                });
            }
        });
    }
    
    public function saveData(array $data) {
        if(isset($data['filter_tags'] )) {
            foreach($data['filter_tags'] as $tagId) {
                $data['pivot_filter_tags'][$tagId]['type'] = NodeListRelationType::Tag;
            }
        }
        
        if(isset($data['filter_authors'] )) {
            foreach($data['filter_authors'] as $authorId) {
                $data['pivot_filter_authors'][$authorId]['type'] = NodeListRelationType::Author;
            }
        }
        
        $data['author'] = isset($this->id) ? $this->author->id : Auth::id();

        parent::saveData($data);
    }

    public function deleteData() {
        foreach($this->parent_elements as $parentElement) {
            $parentElement->element_item()->detach();
            $parentElement->nodes()->detach();
            $parentElement->delete();
        }

        $this->delete();
    }
    
    public function __call($method, $parameters) {
        // GRAPHQL!!!
        if($method === 'list_items') {
            $this->relationsSettings['list_items'] = [
                'relationType' => 'belongsToMany',
                'model' => 'App\\Node',
                'pivot' => 'node_list_relation',
                'foreignKey' => 'node_list_id',
                'relationKey' => 'relation_id'
            ];

            $this->multipleFields['list_items'] = true;
        }
        
        // GRAPHQL!!!
        if($method === 'hydrate') {
            $lists = parent::__call($method, $parameters);
            foreach($lists as $list) {
                $list->list_items = $list->items;
            }
            return $lists;
        }

        return parent::__call($method, $parameters);
    }
}