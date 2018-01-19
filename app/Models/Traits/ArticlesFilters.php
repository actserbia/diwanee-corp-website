<?php
namespace App\Models\Traits;

use App\Constants\ArticleStatus;

trait ArticlesFilters  {
    public function scopeWithActive($query, $params) {
        if(isset($params['active'])) {
            $status = $params['active'] == 'true' ? ArticleStatus::Published : ArticleStatus::Unpublished;
            $query = $query->where('status', $status);
        }
        
        return $query;
    }
    
    
    public function scopeWithTags($query, $paramsName, $params, $tagAttribute) {
        if(isset($params[$paramsName])) {
            foreach($params[$paramsName] as $param) {
                $query = $this->queryWithTag($query, $param, $tagAttribute);
            }
        }
        return $query;
    }
    
    private function queryWithTag($query, $param, $tagAttribute) {
        return $query->whereHas('tags', function($q) use($param, $tagAttribute) {
            $q->where($tagAttribute, '=', $param);
        });
    }
    
    
    
    public function scopeWithElements($query, $paramsName, $params, $type = '', $dataAttribute = '') {
        if(isset($params[$paramsName])) {
            foreach($params[$paramsName] as $param) {
                $query = $this->queryWithElement($query, $param, $type, $dataAttribute);
            }
        }
        return $query;
    }
    
    private function queryWithElement($query, $param, $type, $dataAttribute) {
        return $query->whereHas('elements', function($q) use($param, $type, $dataAttribute) {
            $q->where('type', '=', empty($type) ? $param : $type);
            if(!empty($dataAttribute)) {
                $q->where('data', 'like', '%"' . $dataAttribute . '":"' . $param . '"%');
            }
        });
    }
    
    
    public function scopeWithAuthors($query, $paramsName, $params, $authorAttribute) {
        if(isset($params[$paramsName])) {
            $paramsArray = is_array($params[$paramsName]) ? $params[$paramsName] : explode(',', $params[$paramsName]);
            
            foreach($paramsArray as $param) {
                $query = $this->queryWithAuthor($query, $param, $authorAttribute);
            }
        }
        return $query;
    }
    
    private function queryWithAuthor($query, $param, $authorAttribute) {
        return $query->whereHas('author', function($q) use($param, $authorAttribute) {
            $q->where($authorAttribute, '=', $param);
        });
    }
}
