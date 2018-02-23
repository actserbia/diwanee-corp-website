<?php
namespace App\Models\Filters\FiltersManager\FiltersBase;

use App\Constants\Filters;

abstract class FiltersBaseParams extends FiltersBase {
    const connectionTypesOptions = array(
        Filters::ConnectionAnd => [
            'function' => 'where',
            'functionAggregate' => 'having',
            'functionRelation' => 'whereHas'
        ],

        Filters::ConnectionOr => [
            'function' => 'orWhere',
            'functionAggregate' => 'orHaving',
            'functionRelation' => 'orWhereHas'
        ]
    );

    const searchTemplates = array(
        'default' => '[PARAM]',
        Filters::SearchLike => '%[PARAM]%',
        Filters::SearchEmptyOrNull => '""'
    );

    const searchTypesOptions = array(
        Filters::SearchEqual => [
            'nullFunction' => 'whereNotNull',
            'operator' => '=',
            'connectFunction' => 'where'
        ],
        Filters::SearchLike => [
            'nullFunction' => 'whereNotNull',
            'operator' => 'like',
            'connectFunction' => 'where'
        ],
        Filters::SearchEmptyOrNull => [
            'nullFunction' => 'whereNull',
            'operator' => '=',
            'connectFunction' => 'orWhere'
        ],
        Filters::SearchGreater => [
            'nullFunction' => 'whereNotNull',
            'operator' => '>',
            'connectFunction' => 'where'
        ],
        Filters::SearchGreaterOrEqual => [
            'nullFunction' => 'whereNotNull',
            'operator' => '>=',
            'connectFunction' => 'where'
        ],
        Filters::SearchLessOrEqual => [
            'nullFunction' => 'whereNotNull',
            'operator' => '<=',
            'connectFunction' => 'where'
        ],
        Filters::SearchLess => [
            'nullFunction' => 'whereNotNull',
            'operator' => '<',
            'connectFunction' => 'where'
        ]
    );

    const oppositeOptions = array(
        'whereNull' => 'whereNotNull',
        'whereNotNull' => 'whereNull',
        'where' => 'orWhere',
        'orWhere' => 'where',
        'having' => 'orHaving',
        'orHaving' => 'having',
        '=' => '!=',
        '>' => '<=',
        '>=' => '<',
        '<' => '>=',
        '<=' => '>',
        'like' => 'not like',
        'whereHas' => 'orWhereDoesntHave',
        'orWhereHas' => 'whereDoesntHave',
    );

    const oppositeSearchTypes = array(
        Filters::SearchNotEqual => Filters::SearchEqual,
        Filters::SearchNotLike => Filters::SearchLike,
        Filters::SearchNotEmptyOrNull => Filters::SearchEmptyOrNull
    );

    protected $paramsArray = [];
    protected $searchTypesArray = [];

    protected $connectionType = Filters::DefaultConnectionType;
    protected $connectionTypeNegation = false;

    protected $searchType = Filters::SearchEqual;
    protected $searchTypeNegation = false;

    protected $query = null;

    protected function setAllSearchSettings($query, $params, $paramName) {
        $this->paramsArray = $params[$paramName];

        $this->searchTypesArray = isset($params['searchTypes_' . $paramName]) ? $params['searchTypes_' . $paramName] : [];

        $this->setConnectionType($paramName, $params);

        $this->query = $query;
    }

    private function setConnectionType($paramName, $params) {
        $this->connectionType = Filters::DefaultConnectionType;
        $this->connectionTypeNegation = false;

        if(isset($params['connectionType_' . $paramName]) && in_array($params['connectionType_' . $paramName], Filters::connectionTypes)) {
            $this->connectionType = $params['connectionType_' . $paramName];
        }

        if(isset($params['connectionTypeNegation_' . $paramName]) && $params['connectionTypeNegation_' . $paramName] == 'true') {
            $this->connectionTypeNegation = true;
        }
    }

    protected function setSearchTypeForParam($index) {
        $this->searchType = Filters::SearchEqual;
        $this->searchTypeNegation = false;

        if(isset($this->searchTypesArray[$index])) {
            if(key_exists($this->searchTypesArray[$index], self::oppositeSearchTypes)) {
                $this->searchType = self::oppositeSearchTypes[$this->searchTypesArray[$index]];
                $this->searchTypeNegation = true;
            }

            elseif(key_exists($this->searchTypesArray[$index], self::searchTypesOptions)) {
                $this->searchType = $this->searchTypesArray[$index];
            }
        }
    }

    protected function checkIfAllSearchTypesAreEqual() {
        foreach($this->searchTypesArray as $searchType) {
            if($searchType !== Filters::SearchEqual) {
                return false;
            }
        }

        return true;
    }

    protected function getSearchTypesOptions($isRelations = false) {
        if(!$isRelations && ($this->searchTypeNegation xor $this->connectionTypeNegation)) {
            $options = $this->getOppositeOptions(self::searchTypesOptions[$this->searchType]);
        } else {
            $options = self::searchTypesOptions[$this->searchType];
        }

        $options['template'] = $this->getSearchTemplate();

        return $options;
    }

    protected function getSearchTemplate() {
        return isset(self::searchTemplates[$this->searchType]) ? self::searchTemplates[$this->searchType] :  self::searchTemplates['default'];
    }

    private function getOppositeOptions($options) {
        $oppositeOptions = array();
        foreach($options as $key => $value) {
            $oppositeOptions[$key] = self::oppositeOptions[$value];
        }
        return $oppositeOptions;
    }

    protected function getConnectionTypeFunction($functionName = 'function') {
        $function = self::connectionTypesOptions[$this->connectionType][$functionName];

        if($this->connectionTypeNegation) {
            $function = self::oppositeOptions[$function];
        }

        return $function;
    }
}