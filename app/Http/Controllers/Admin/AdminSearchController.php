<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Filters\FiltersUtils;
use App\Utils\HtmlElementsClasses;
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

    private function getFiltersView(Request $request, $modelName) {
        $params = $request->all();

        $modelClass = 'App\\' . $modelName;
        if(isset($params['model_type'])) {
            $modelTypeId = $params['model_type'];
            $model = new $modelClass(['model_type_id' => $modelTypeId]);
            $attributes = ['model_type_id' => $modelTypeId];
        } else {
            $modelTypeId = '';
            $model = new $modelClass;
            $attributes = [];
        }

        $items = [];
        if(!$modelClass::hasModelTypes() || isset($params['model_type'])) {
            $params = FiltersUtils::prepareParams($request->all());
            $items = $model::withAll($attributes)
                ->filterByAllParams($params)
                ->filterByModelType($modelTypeId)
                ->get();
        }

        return view('admin.search.' . strtolower($modelName) . 's', compact('items', 'model'));
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