<?php

namespace App\Converters;

use League\CommonMark\CommonMarkConverter;

use App\Constants\ElementType;
use App\Constants\Settings;

class ToHtmlConverter extends CommonMarkConverter {
    public function convertElementData($elementData) {
        $element = is_array($elementData) ? json_decode(json_encode($elementData), false) : $elementData;
        
        if(isset($element->data->format) && $element->data->format !== 'html') {
            if(in_array($element->type, ElementType::textTypes)) {
                $this->convertText($element);
            }

            if($element->type == ElementType::ElementList) {
                $this->convertList($element);
            }
        }
        
        if(is_array($elementData)) {
            $element = json_decode(json_encode($element), true);
            return $element;
        }
    }
    
    private function convertText($element) {
        $element->data->text = $this->convertToHtml($element->data->text);
        $element->data->format = 'html';
    }
    
    private function convertList($element) {
        $listItems = explode(Settings::MarkdownConverterConfig['list_item_style'], $element->data->text);
        $htmlListItems = array();
        foreach($listItems as $listItem) {
            if(!empty($listItem)) {
                $item = new \stdClass();
                $item->content = $this->convertToHtml($listItem);
                $htmlListItems[] = $item;
            }
        }
        $element->data->listItems = $htmlListItems;
        unset($element->data->text);
        $element->data->format = 'html';
    }
}