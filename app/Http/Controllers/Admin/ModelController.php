<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;
use App\Utils\HtmlElementsClasses;
use App\Utils\Utils;
use App\Tag;
use App\Constants\ElementType;

class ModelController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
    }
    
    public function modelPopulateField(Request $request) {
        $params = $request->all();

        $data = $params['data'];
        $column = $data['column'];
        $model = new $data['model'];

        $items = $model->getRelationValues($data['relation'], isset($params['dependsOnValues']) ? $params['dependsOnValues'] : []);

        $itemsOutput = [['value' => '', 'text' => '', 'selected' => '']];
        foreach($items as $item) {
            $itemsOutput[] = array(
                'value' => $item->id,
                'text' => $item->$column,
                'selected' => $model->defaultAttributeValue($data['relation']) == $item->id ? 'selected' : ''
            );
        }

        return json_encode($itemsOutput);
    }

    public function modelAddRelationItem(Request $request) {
        $params = $request->all();
        
        $data = $params['data'];
        $field = $data['relation'];
        $level = isset($data['level']) ? $data['level'] : 1;
        $object = isset($data['modelId']) ? $data['model']::find($data['modelId']) : new $data['model'](['model_type_id' => $data['modelType']]);
        $itemModel = $object->getRelationModel($data['relation']);
        $item = $itemModel::find($params['itemId']);
        $fullData = $data['fullData'];

        if(($params['type'] === 'tags_parenting')) {
            return view('blocks.model.relation.form_relation_tags_parenting_item', compact('object', 'field', 'item', 'fullData', 'level'));
        } else {
            return view('blocks.model.relation.form_relation_item', compact('object', 'field', 'item', 'fullData'));
        }
    }

    public function modelTagsParentingAddTagSubtags(Request $request) {
        $params = $request->all();

        $data = $params['data'];
        $field = $data['relation'];
        $object = isset($data['modelId']) ? $data['model']::find($data['modelId']) : new $data['model'](['model_type_id' => $data['modelType']]);
        $level = $data['level'] + 1;
        $checkSelected = isset($params['checkSelected']) && !empty($params['checkSelected']);

        $tags = new Collection([]);
        if(isset($params['tagsIds'])) {
            $tagsParents = Tag::whereIn('id', $params['tagsIds'])->get();
            foreach($tagsParents as $tagsParent) {
              $tags = $tags->merge($tagsParent->children);
            }
        }

        return ($tags->count() === 0) ? '' : view('blocks.model.relation.form_relation_tags_parenting', compact('object', 'field', 'tags', 'level', 'checkSelected'));
    }

    public function modelGetTagChildren(Request $request) {
        $params = $request->all();

        $itemsOutput = [];

        $tagParent = Tag::find($params['tag_id']);
        if($tagParent) {
            $column = $tagParent->defaultDropdownColumn;
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
        $itemFieldValue = false;
        $removeCheckbox = true;
        
        return view('blocks.model.form_checkbox_list_item', compact('object', 'fieldPrefix', 'field', 'itemFieldValue', 'removeCheckbox'));
    }
    
    
    public function typeaheadDiwaneeElementItems(Request $request) {
        $params = $request->all();
        
        $model = ElementType::itemsTypesSettings[$params['elementType']]['model'];
        $modelObject = new $model;

        $itemsQuery = $modelObject::select('id', $modelObject->defaultDropdownColumn . ' AS name');
        
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
            
            $filters = $filterModelObject::select('id', $filterModelObject->defaultDropdownColumn . ' AS name')->get();
        } else {
            $items = __('constants.' . $modelObject->modelName . Utils::getFormattedName($filter));
            
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
}
