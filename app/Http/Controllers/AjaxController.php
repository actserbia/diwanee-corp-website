<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AjaxController extends Controller {
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
    
    public function modelAddSelectedItem(Request $request) {
        $params = $request->all();

        $field = $params['field'];
        $item = $params['item'];
        $sortable = $params['sortable'];

        return view('blocks.model.form_selected_item', compact('field', 'item', 'sortable'));
    }
}
