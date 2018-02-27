<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;
use App\Utils\HtmlElementsClasses;
use App\Tag;

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

        $itemsOutput = [['value' => '', 'text' => '']];
        foreach($items as $item) {
            $itemsOutput[] = array(
                'value' => $item->id,
                'text' => $item->$column
            );
        }

        return json_encode($itemsOutput);
    }
    
    public function modelAddRelationItemOLD(Request $request) {
        $params = $request->all();

        if(($params['type'] === 'tags_parenting')) {
            return $this->modelTagsParentingAddTagItem($request);
        }

        $data = $params['data'];
        $field = $data['relation'];
        $object = isset($data['modelId']) ? $data['model']::find($data['modelId']) : new $data['model'];
        $itemModel = $object->getRelationModel($data['relation']);
        $item = $itemModel::find($params['itemId']);
        $fullData = $data['fullData'];
        
        return view('blocks.model.relation.form_relation_item', compact('object', 'field', 'item', 'fullData'));
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
}
