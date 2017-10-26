<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Article;
use App\Constants\ElementType;
use App\Constants\Settings;
use App\Converters\ToHtmlConverter;
use App\Converters\ToMarkdownConverter;

class Element extends Model {
    protected $fillable = [
        'type'
    ];

    public function articles() {
		return $this->belongsToMany(Article::class, 'article_element', 'id_element', 'id_article');
    }

    public function subelements() {
        return $this->belongsToMany(Tag::class, 'element_subelement', 'id_element', 'id_subelement');
    }

    public function parentElement() {
        return $this->belongsTo(Tag::class, 'element_subelement', 'id_subelement', 'id_element');
    }
    
    public function getDataAttribute($value) {
        if(in_array($this->type, ElementType::imageTypes)) {
            $imagesConfig = config('images');
            $data = is_string($value) ? json_decode($value) : $value;
            if(strpos($data->file->url, $imagesConfig['imagesUrl']) === FALSE) {
                $data->file->url = $imagesConfig['imagesUrl'] . $data->file->url;
            }
            return is_string($value) ? json_encode($data) : $data;
        } else {
            return $value;
        }
    }
    
    public function getEditorContentAttribute() {
        $elementData = array();
        
        $elementData['type'] = $this->type;
        $elementData['data'] = json_decode($this->data);

        return $elementData;
    }
    
    public function getJsonEncodedAttribute() {
        return is_string($this->data);
    }

    public function populateData($elementData) {
        $this->fill($elementData);
        
        $preparedElementData = $this->prepareElementData($elementData);
        $this->data = json_encode($preparedElementData['data']);
    }
    
    private function prepareElementData($elementData) {
        $converter = new ToMarkdownConverter(Settings::MarkdownConverterConfig);
        $preparedElementData = $converter->convertElementDataToMarkdown($elementData);
        
        if(in_array($this->type, ElementType::imageTypes)) {
            $imagesConfig = config('images');
            $preparedElementData['data']['file']['url'] = str_replace($imagesConfig['imagesUrl'], '', $preparedElementData['data']['file']['url']);
        }
        
        return $preparedElementData;
    }
    
    public function changeFormat($jsonEncode = true, $toHtml = false) {
        $jsonEncode ? $this->encodeData() : $this->decodeData();
        
        if($toHtml) {
            $converter = new ToHtmlConverter();
            $converter->convertElementDataToHtml($this);
        } else {
            $converter = new ToMarkdownConverter();
            $converter->convertElementDataToMarkdown($this);
        }
    }
    
    private function decodeData() {
        if($this->jsonEncoded) {
            $this->data = json_decode($this->data);
        }
    }

    private function encodeData() {
        if(!$this->jsonEncoded) {
            $this->data = json_encode($this->data);
        }
    }
}