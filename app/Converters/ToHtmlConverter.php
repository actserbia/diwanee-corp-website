<?php

namespace App\Converters;

use League\CommonMark\CommonMarkConverter;

use App\Constants\ElementType;
use App\Constants\Settings;

class ToHtmlConverter extends CommonMarkConverter {
    public function convertElementDataToHtml($elementData) {
        $element = is_array($elementData) ? json_decode(json_encode($elementData), false) : $elementData;
        
        if(isset($element->data->format) && $element->data->format !== 'html') {
            if(in_array($element->type, ElementType::textTypes)) {
                $this->convertTextToHtml($element);
            }

            if($element->type == ElementType::ElementList) {
                $this->convertListToHtml($element);
            }
        }
        
        if(is_array($elementData)) {
            $elementData = json_decode(json_encode($element), true);
            return $elementData;
        }
    }
    
    private function convertTextToHtml($element) {
        $element->data->text = $this->convertToHtml($element->data->text);
        $element->data->format = 'html';
    }
    
    private function convertListToHtml($element) {
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