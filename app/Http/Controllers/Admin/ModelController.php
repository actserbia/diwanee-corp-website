<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\HtmlElementsClasses;

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
        $itemModel = $object->getRelationModel($field);
        $item = $itemModel::find($params['item_id']);
        $onlyLabel = $params['only_label'];
        
        return view('blocks.model.form_relation_item', compact('object', 'field', 'item', 'onlyLabel'));
    }
}
