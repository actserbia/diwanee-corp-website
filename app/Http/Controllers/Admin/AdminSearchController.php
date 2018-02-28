<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Filters\FiltersUtils;
use App\Utils\HtmlElementsClasses;
use App\Utils\Utils;
use App\Node;

class AdminSearchController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
    }
    
    public function nodes(Request $request) {
        return $this->getFiltersView($request, 'Node');
    }
    
    public function nodesList(Request $request) {
        $params = $request->all();
        
        $filterFields = [];
        if(!empty($params['model_type_id'])) {
            $object = new Node(['model_type_id' => $params['model_type_id']]);
            $filterFields = [['value' => '', 'text' => '']];
            foreach($object->getFilterFieldsWithLabels() as $field => $fieldTitle) {
                $filterFields[] = array(
                    'value' => $field,
                    'text' => $fieldTitle
                );
            }
        }

        return json_encode($filterFields);
    }

    public function elements(Request $request) {
        return $this->getFiltersView($request, 'Element');
    }

    public function tags(Request $request) {
        return $this->getFiltersView($request, 'Tag');
    }

    public function users(Request $request) {
        return $this->getFiltersView($request, 'User');
    }

    public function nodeLists(Request $request) {
        return $this->getFiltersView($request, 'NodeList');
    }

    private function getFiltersView(Request $request, $modelName) {
        $params = $request->all();

        $modelClass = 'App\\' . $modelName;
        if(isset($params['model_type'])) {
            $model = new $modelClass(['model_type_id' => $modelTypeId]);
            $attributes = ['model_type_id' => $modelTypeId];
        } else {
            $model = new $modelClass;
            $attributes = [];
        }

        $filterParams = FiltersUtils::prepareParams($request->all());
        $items = $model::withAll($attributes)
            ->filterByAllParams($filterParams);

        if(isset($params['model_type'])) {
            $items = $items->filterByModelType($params['model_type'])->get();
        } else {
            $items = $items->get();
        }

        return view('admin.search.' . Utils::getFormattedDBName($modelName) . 's', compact('items', 'model'));
    }

    public function typeahead(Request $request) {
        $params = $request->all();
        
        $model = $this->getModelFromData($params['data']);
        
        return $model->getTypeaheadItems($params['param']);
    }

    public function searchAddFilter(Request $request) {
        $params = $request->all();
        
        $model = $this->getModelFromData($params['data']);
        $field = $params['field'];

        return view('blocks.search.' . $model->formFieldType($field), compact('field', 'model'));
    }

    public function searchAddInput(Request $request) {
        $params = $request->all();
        
        $model = $this->getModelFromData($params['data']);
        $field = $params['data']['field'];

        return view('blocks.search.form_input_detail', compact('field', 'model'));
    }
    
    private function getModelFromData($data) {
        $modelClass = $data['model'];
        if($modelClass::hasModelTypes()) {
            return new $modelClass(['model_type_id' => $data['modelType']]);
        } else {
            return new $modelClass;
        }
    }
}