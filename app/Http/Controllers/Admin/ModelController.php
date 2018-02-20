<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;
use App\Utils\HtmlElementsClasses;
use App\Tag;
use App\Node;

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
    
    public function modelAddRelationItem(Request $request) {
        $params = $request->all();

        if(($params['type'] === 'tags')) {
            return $this->modelNodeTagsAddTagItem($request);
        }

        $data = $params['data'];
        $field = $data['relation'];
        $object = isset($data['modelId']) ? $data['model']::find($data['modelId']) : new $data['model'];
        $itemModel = $object->getRelationModel($data['relation']);
        $item = $itemModel::find($params['itemId']);
        $fullData = $data['fullData'];
        $isNew = true;
        
        return view('blocks.model.relation.form_relation_item', compact('object', 'field', 'item', 'fullData', 'isNew'));
    }

    private function modelNodeTagsAddTagItem($params) {
        $data = $params['data'];
        
        $field = $data['relation'];
        $level = isset($data['level']) ? $data['level'] : 1;
        $object = isset($data['modelId']) ? Node::find($data['modelId']) : new Node(['node_type_id' => $data['nodeType']]);
        $itemModel = $object->getRelationModel($data['relation']);
        $item = $itemModel::find($params['itemId']);
        $fullData = $data['fullData'];
        $isNew = true;

        return view('blocks.model.relation.form_relation_node_tags_item', compact('object', 'field', 'item', 'fullData', 'isNew', 'level'));
    }

    public function modelNodeTagsAddTagSubtags(Request $request) {
        $params = $request->all();

        $data = $params['data'];
        $field = $data['relation'];
        $object = isset($data['modelId']) ? Node::find($data['modelId']) : new Node(['node_type_id' => $data['nodeType']]);
        $level = $data['level'] + 1;
        $checkSelected = isset($params['checkSelected']) && !empty($params['checkSelected']);

        $tags = new Collection([]);
        if(isset($params['tagsIds'])) {
            $tagsParents = Tag::whereIn('id', $params['tagsIds'])->get();
            foreach($tagsParents as $tagsParent) {
              $tags = $tags->merge($tagsParent->children);
            }
        }

        return ($tags->count() === 0) ? '' : view('blocks.model.relation.form_relation_node_tags', compact('object', 'field', 'tags', 'level', 'checkSelected'));
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
}
