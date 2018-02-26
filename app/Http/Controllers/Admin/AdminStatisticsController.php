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
        return $this->getStatisticsView($request, 'Node');
    }
    
    public function itemsList(Request $request) {
        $params = $request->all();
        
        $fields = [];
        if(!empty($params['model_type_id'])) {
            $modelClass = $params['data']['model'];
            $object = new $modelClass(['model_type_id' => $params['model_type_id']]);
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
        if(isset($params['model_type'])) {
            $model = new $modelClass(['model_type_id' => $params['model_type']]);
            $attributes = ['model_type_id' => $params['model_type']];
        } else {
            $model = new $modelClass;
            $attributes = [];
        }
        
        if(isset($params['statistic'])) {
            $statisticName = $params['statistic'];
            $statisticsFilter = $modelClass::withAll($attributes)
                ->filterByAllParams($params, false);

            if(isset($params['model_type'])) {
                $statisticsFilter = $statisticsFilter->filterByModelType($params['model_type']);
            }

            $statistics = $statisticsFilter->statistics($statisticName);
        }

        return view('admin.statistics.' . strtolower($modelName) . 's', compact('model', 'statistics', 'statisticName'));
    }
}