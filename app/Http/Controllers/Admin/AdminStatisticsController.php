<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\HtmlElementsClasses;
use App\Node;

class AdminStatisticsController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
    }

    public function nodes(Request $request) {
        $params = $request->all();
        
        if(isset($params['node_type'])) {
            $nodeTypeId = $params['node_type'];
            
            $model = new Node(['node_type_id' => $nodeTypeId]);
            
            if(isset($params['statistic'])) {
                $statisticName = $params['statistic'];
                $statistics = Node::withAll(['node_type_id' => $nodeTypeId])
                    ->filterByAllParams($params, false)
                    ->where('node_type_id', '=', $nodeTypeId)
                    ->statistics($statisticName);
            }
        } else {
            $model = new Node;
        }

        return view('admin.statistics.nodes', compact('model', 'statistics', 'statisticName'));
    }
    
    public function nodesList(Request $request) {
        $params = $request->all();
        
        $fields = [];
        if(!empty($params['node_type_id'])) {
            $object = new Node(['node_type_id' => $params['node_type_id']]);
            $fields = [['value' => '', 'text' => '']];
            foreach($object->getStatisticFieldsWithLabels() as $field => $fieldTitle) {
                $fields[] = array(
                    'value' => $field,
                    'text' => $fieldTitle
                );
            }
        }

        return json_encode($fields);
    }

    public function elements(Request $request) {
        return $this->getStatisticsView($request, 'Element');
    }

    public function tags(Request $request) {
        return $this->getStatisticsView($request, 'Tag');
    }

    public function users(Request $request) {
        return $this->getStatisticsView($request, 'User');
    }

    private function getStatisticsView(Request $request, $modelName) {
        $params = $request->all();
        
        $modelClass = 'App\\' . $modelName;
        $model = new $modelClass;
        
        if(isset($params['statistic'])) {
            $statisticName = $params['statistic'];
            $statistics = $modelClass::withAll()
                ->filterByAllParams($params, false)
                ->statistics($statisticName);
        }

        return view('admin.statistics.' . strtolower($modelName) . 's', compact('model', 'statistics', 'statisticName'));
    }
}