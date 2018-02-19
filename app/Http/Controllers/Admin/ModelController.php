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

        $column = $params['column'];
        $model = new $params['model'];

        $items = $model->getRelationValues($params['relation'], isset($params['dependsOnValues']) ? $params['dependsOnValues'] : []);

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

        $field = $params['field'];
        $object = isset($params['model_id']) ? $params['model']::find($params['model_id']) : new $params['model'];
        $itemModel = $object->getRelationModel($params['field']);
        $item = $itemModel::find($params['item_id']);
        $fullData = $params['full_data'];
        $isNew = true;
        
        $template = ($params['type'] === 'tags') ? 'form_tags_relation_item' : 'form_relation_item';
        
        return view('blocks.model.' . $template, compact('object', 'field', 'item', 'fullData', 'isNew'));
    }

    public function modelAddSubtags(Request $request) {
        $params = $request->all();

        $field = $params['field'];
        $object = isset($params['model_id']) ? $params['model']::find($params['model_id']) : new $params['model'];
        $level = $params['level'] + 1;

        $tags = new Collection([]);
        $tagsParents = Tag::whereIn('id', $params['tags_id'])->get();
        foreach($tagsParents as $tagsParent) {
          $tags = $tags->merge($tagsParent->children);
        }


        return view('blocks.model.form_tags_relation', compact('object', 'field', 'tags', 'level'));
    }
}
