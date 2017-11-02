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
            $value = $this->getImageFullUrl($value);
        }
        
        return $value;
    }
    
    private function getImageFullUrl($value) {
        $imageData = is_string($value) ? json_decode($value) : $value;
        
        if(strpos($imageData->file->url, config('images.imagesUrl')) === FALSE) {
            $imageData->file->url = config('images.imagesUrl') . $imageData->file->url;
        }
            
        return is_string($value) ? json_encode($imageData) : $imageData;
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
        $preparedElementData = $converter->convertElementData($elementData);
        
        if(in_array($this->type, ElementType::imageTypes)) {
            $preparedElementData['data']['file']['url'] = str_replace(config('images.imagesUrl'), '', $preparedElementData['data']['file']['url']);
        }
        
        return $preparedElementData;
    }
    
    public function changeFormat($jsonEncode = true, $toHtml = false) {
        $jsonEncode ? $this->encodeData() : $this->decodeData();
        
        $converter = $toHtml ? new ToHtmlConverter() : new ToMarkdownConverter();
        $converter->convertElementData($this);
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