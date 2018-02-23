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
        $params = $request->all();
        
        $items = [];
        if(isset($params['node_type'])) {
            $nodeTypeId = $params['node_type'];
            
            $model = new Node(['node_type_id' => $nodeTypeId]);
            
            $params = FiltersUtils::prepareParams($request->all());
            $items = Node::withAll(['node_type_id' => $nodeTypeId])
                ->filterByAllParams($params)
                ->where('node_type_id', '=', $nodeTypeId)
                ->get();
        } else {
            $model = new Node;
        }
        
        return view('admin.search.nodes', compact('items', 'model'));
    }
    
    public function nodesList(Request $request) {
        $params = $request->all();
        
        $filterFields = [];
        if(!empty($params['node_type_id'])) {
            $object = new Node(['node_type_id' => $params['node_type_id']]);
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
        $params = FiltersUtils::prepareParams($request->all());

        $modelClass = 'App\\' . $modelName;
        $model = new $modelClass;

        $items = $modelClass::withAll()
            ->filterByAllParams($params)
            ->get();

        return view('admin.search.' . strtolower($modelName) . 's', compact('items', 'model'));
    }

    public function typeahead(Request $request) {
        $params = $request->all();
        
        $data = $params['data'];
        
        if($data['model'] === 'App\\Node') {
            $model = new Node(['node_type_id' => $data['modelType']]);
        } else {
            $model = new $data['model'];
        }
        
        return $model->getTypeaheadItems($params['param']);
    }

    public function searchAddFilter(Request $request) {
        $params = $request->all();
        
        $data = $params['data'];
        
        if($data['model'] === 'App\\Node') {
            return $this->searchAddFilterNodes($params);
        }

        $field = $params['field'];
        $model = new $data['model'];

        return view('blocks.search.' . $model->formFieldType($field), compact('field', 'model'));
    }
    
    private function searchAddFilterNodes($params) {
        $data = $params['data'];
        
        $field = $params['field'];
        $model = new Node(['node_type_id' => $data['modelType']]);
        
        return view('blocks.search.' . $model->formFieldType($field), compact('field', 'model'));
    }

    public function searchAddInput(Request $request) {
        $params = $request->all();
        
        $data = $params['data'];
        if($data['model'] === 'App\\Node') {
            return $this->searchAddInputNodes($params);
        }

        $field = $data['field'];
        $model = new $data['model'];

        return view('blocks.search.form_input_detail', compact('field', 'model'));
    }
    
    private function searchAddInputNodes($params) {
        $data = $params['data'];
        
        $field = $data['field'];
        $model = new Node(['node_type_id' => $data['modelType']]);

        return view('blocks.search.form_input_detail', compact('field', 'model'));
    }
}