<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;
use App\Utils\HtmlElementsClasses;
use App\Tag;
use App\Constants\ElementType;
use App\Constants\Models;

class ModelController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
    }
    
    public function modelPopulateField(Request $request) {
        $params = $request->all();

        $data = $params['data'];
        $model = new $data['model'];
        $column = $model->getRepresentationField($data['field']);

        $items = $model->getRelationValues($data['field'], isset($params['dependsOnValues']) ? $params['dependsOnValues'] : []);

        $itemsOutput = [['value' => '', 'text' => '', 'selected' => '']];
        foreach($items as $item) {
            $itemsOutput[] = array(
                'value' => $item->id,
                'text' => $item->$column,
                'selected' => $model->defaultAttributeValue($data['field']) == $item->id ? 'selected' : ''
            );
        }

        return json_encode($itemsOutput);
    }

    public function modelAddRelationItem(Request $request) {
        $params = $request->all();
        
        $field = $params['relation'];
        $level = isset($params['level']) ? $params['level'] : 1;
        $object = isset($params['modelId']) ? $params['model']::find($params['modelId']) : new $params['model'](['model_type_id' => $params['modelType']]);
        $itemModel = $object->getRelationModel($params['relation']);
        $item = $itemModel::find($params['itemId']);
        $fullData = $params['fullData'];
        $withCategory = isset($params['withCategory']) ? $params['withCategory'] : false;
        $fieldPrefix = isset($params['fieldPrefix']) ? $params['fieldPrefix'] : '';

        if(($params['type'] === 'tags_parenting')) {
            return view('blocks.model.relation.form_relation_tags_parenting_item', compact('object', 'field', 'item', 'fullData', 'level', 'fieldPrefix'));
        } else {
            return view('blocks.model.relation.form_relation_item', compact('object', 'field', 'item', 'fullData', 'withCategory', 'fieldPrefix'));
        }
    }

    public function modelAddNewRelationItem(Request $request) {
        $params = $request->all();
        
        $data = $params['data'];
        
        $field = $data['field'];
        $object = new $data['model'](['model_type_id' => $data['modelType']]);
        $item = $object->getRelationModel($data['field']);
        $index = $data['lastIndex']++;
        
        return view('blocks.model.relation.form_relation_item__new', compact('object', 'field', 'item', 'index')); 
    }

    public function modelTagsParentingAddTagSubtags(Request $request) {
        $params = $request->all();

        $data = $params['data'];
        $field = $data['field'];
        $object = isset($data['modelId']) ? $data['model']::find($data['modelId']) : new $data['model'](['model_type_id' => $data['modelType']]);
        $level = $data['level'] + 1;
        $checkSelected = isset($params['checkSelected']) && !empty($params['checkSelected']);
        $fieldPrefix = isset($data['fieldPrefix']) ? $data['fieldPrefix'] : '';

        $tags = new Collection([]);
        if(isset($params['tagsIds'])) {
            $tagsParents = Tag::whereIn('id', $params['tagsIds'])->get();
            foreach($tagsParents as $tagsParent) {
              $tags = $tags->merge($tagsParent->children);
            }
        }

        return ($tags->count() === 0) ? '' : view('blocks.model.' . Models::FormFieldType_Relation . '__' . Models::FormFieldType_Relation_TagsParentingSuffix, compact('object', 'field', 'tags', 'level', 'checkSelected', 'fieldPrefix'));
    }

    public function modelGetTagChildren(Request $request) {
        $params = $request->all();

        $itemsOutput = [];

        $tagParent = Tag::find($params['tag_id']);
        if($tagParent) {
            $column = $tagParent->representationField;
            foreach($tagParent->children as $tag) {
                $itemsOutput[] = array(
                    'value' => $tag->id,
                    'text' => $tag->$column
                );
            }
        }

        return json_encode($itemsOutput);
    }
    
    public function modelAddCheckbox(Request $request) {
        $params = $request->all();

        $data = $params['data'];
        
        $object = isset($data['modelId']) ? $data['model']::find($data['modelId']) : new $data['model'];
        $fieldPrefix = $data['fieldPrefix'];
        $field = $data['field'];
        $removeCheckbox = isset($params['removeCheckbox']) ? $params['removeCheckbox'] : true;
        $itemFieldValue = !$removeCheckbox;
        
        return view('blocks.model.form_checkbox_list_item', compact('object', 'fieldPrefix', 'field', 'itemFieldValue', 'removeCheckbox'));
    }
    
    
    public function typeaheadDiwaneeElementItems(Request $request) {
        $params = $request->all();
        
        $model = ElementType::itemsTypesSettings[$params['elementType']]['model'];
        $modelObject = new $model;

        $itemsQuery = $modelObject::select('id', $modelObject->representationField . ' AS name');
        
        if(isset($params['filterValue'])) {
            $itemsQuery = $this->addFilterToQuery($itemsQuery, $params, $modelObject);
        }
        
        if($model === 'App\\Node') {
             $reqNode = intval(filter_var($request->server('HTTP_REFERER'), FILTER_SANITIZE_NUMBER_INT));
             $itemsQuery = $itemsQuery->where('id', '!=', $reqNode);
        }
        
        $items = $itemsQuery->withActive()->get();
        
        $results = array();
        foreach($items->toArray() as $arrNode) {
            $results[] = array_map('strval', $arrNode);
        }
        
        return json_encode($results);
    }
    
    private function addFilterToQuery($itemsQuery, $params, $modelObject) {
        $filter = ElementType::itemsTypesSettings[$params['elementType']]['filter'];
        
        if($modelObject->isRelation($filter)) {
            $itemsQuery = $this->addRelationFilterToQuery($itemsQuery, $params, $modelObject);
        } else {
            $itemsQuery = $itemsQuery->where($filter, '=', $params['filterValue']);
        }
        
        return $itemsQuery;
    }
    
    private function addRelationFilterToQuery($itemsQuery, $params, $modelObject) {
        $filter = ElementType::itemsTypesSettings[$params['elementType']]['filter'];
        $filterValue = $params['filterValue'];
        
        $relationSettings = $modelObject->getRelationSettings($filter);
        if($relationSettings['relationType'] === 'belongsToMany') {
            if($filterValue !== '') {
                $itemsQuery = $itemsQuery->whereHas($filter, function($q) use ($relationSettings, $filterValue) {
                    $q->where($relationSettings['relationKey'], '=', $filterValue);
                });
            }
        } else {
            $itemsQuery = $itemsQuery->where($relationSettings['foreignKey'], '=', $filterValue);
        }
        
        return $itemsQuery;
    }
    
    public function typeaheadDiwaneeElementItemsFilters(Request $request) {
        $params = $request->all();
        
        $model = ElementType::itemsTypesSettings[$params['elementType']]['model'];
        $modelObject = new $model;
        
        $filter = ElementType::itemsTypesSettings[$params['elementType']]['filter'];
        if($modelObject->isRelation($filter)) {
            $relationsSettings = $modelObject->getRelationSettings($filter);
            $filterModelObject = new $relationsSettings['model'];
            
            $filters = $filterModelObject::select('id', $filterModelObject->representationField . ' AS name')->get();
        } else {
            $items = __('constants.' . $modelObject->modelName . Str::studly($filter));
            
            $filters = [];
            foreach($items as $key => $value) {
                $filters[] = [
                    'id' => $key,
                    'name' => $value
                ];
            }
        }
        
        return json_encode($filters);
    }

    public function typeaheadModelRelationItems(Request $request) {
        $params = $request->all();

        $object = isset($params['modelId']) ? $params['model']::find($params['modelId']) : new $params['model'](['model_type_id' => $params['modelType']]);
        $items = $object->getRelationValues($params['relation'], isset($params['dependsOnValues']) ? $params['dependsOnValues'] : []);

        $itemsOutput = [];
        foreach($items as $item) {
            $itemsOutput[] = array(
                'id' => $item->id,
                'name' => $item->getNameWithCategoryField()
            );
        }

        $results = array();
        foreach($itemsOutput as $item) {
            $results[] = array_map('strval', $item);
        }

        return json_encode($results);
    }
}
