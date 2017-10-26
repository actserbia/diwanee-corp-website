<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use League\HTMLToMarkdown\HtmlConverter;
use League\CommonMark\CommonMarkConverter;

use App\Article;
use App\Constants\ElementType;
use App\Constants\Settings;

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
    
    public function getContentAttribute($value) {
        if(in_array($this->type, ElementType::imageTypes)) {
            $imagesConfig = config('images');
            $content = is_string($value) ? json_decode($value) : $value;
            if(strpos($content->file->url, $imagesConfig['imagesUrl']) === FALSE) {
                $content->file->url = $imagesConfig['imagesUrl'] . $content->file->url;
            }
            return is_string($value) ? json_encode($content) : $content;
        } else {
            return $value;
        }
    }
    
    public function getEditorContentAttribute() {
        $elementData = array();
        
        $elementData['type'] = $this->type;
        $elementData['data'] = json_decode($this->content);

        return $elementData;
    }
    
    public function getJsonEncodedAttribute() {
        return is_string($this->content);
    }

    public function populateData($elementData) {
        $this->fill($elementData);
        
        $preparedElementData = $this->prepareElementData($elementData);
        $this->content = json_encode($preparedElementData['data']);
    }
    
    private function prepareElementData($elementData) {
        $preparedElementData = $this->elementDataToMarkdown($elementData);
        
        if(in_array($this->type, ElementType::imageTypes)) {
            $imagesConfig = config('images');
            $preparedElementData['data']['file']['url'] = str_replace($imagesConfig['imagesUrl'], '', $preparedElementData['data']['file']['url']);
        }
        
        return $preparedElementData;
    }
    
    protected function elementDataToMarkdown($elementData) {
        $converter = new HtmlConverter(Settings::MarkdownConverterConfig);
        if(in_array($elementData['type'], ElementType::textTypes)) {
            $elementData['data']['text'] = $converter->convert($elementData['data']['text']);
            $elementData['data']['format'] = 'markdown';
        }

        if($elementData['type'] == ElementType::ElementList) {
            $html = '<ul>';
            foreach($elementData['data']['listItems'] as $listItem) {
                $html .= '<li>' . $listItem['content'] . '</li>';
            }
            $html .= '</ul>';
            $elementData['data']['text'] = $converter->convert($html);
            $elementData['data']['format'] = 'markdown';
        }
        
        return $elementData;
    }
    
    public function changeFormat($jsonEncode = true, $toHtml = false) {
        $jsonEncode ? $this->encodeContent() : $this->decodeContent();
        
        if($toHtml) {
            $this->convertToHtml();
        }
    }
    
    private function decodeContent() {
        if($this->jsonEncoded) {
            $this->content = json_decode($this->content);
        }
    }

    private function encodeContent() {
        if(!$this->jsonEncoded) {
            $this->content = json_encode($this->content);
        }
    }
    
    private function convertToHtml() {
        if(isset($this->content->format) && $this->content->format !== 'html') {
            $converter = new CommonMarkConverter();

            if(in_array($this->type, ElementType::textTypes)) {
                $this->content->text = $converter->convertToHtml($this->content->text);
                $this->content->format = 'html';
            }

            if($this->type == ElementType::ElementList) {
                $this->convertListToHtml($converter);
            }
        }
    }
    
    private function convertListToHtml($converter) {
        $listItems = explode(Settings::MarkdownConverterConfig['list_item_style'], $this->content->text);
        $htmlListItems = array();
        foreach($listItems as $listItem) {
            if(!empty($listItem)) {
                $htmlListItems[]['content'] = $converter->convertToHtml($listItem);
            }
        }
        $this->content->listItems = $htmlListItems;
        unset($this->content->text);
        $this->content->format = 'html';
    }
}