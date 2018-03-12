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
        $data = $element->data;

        $data->text = $this->convertToHtml($element->data->text);
        if($element->type == 'heading') {
          $data->text = strip_tags($element->data->text, '<strong><b><i><a><em>');
          $data->text = str_replace("\n", '', $element->data->text);
        }
        $data->format = 'html';

        $element->data = $data;
    }
    
    private function convertList($element) {
        $data = $element->data;

        $listItems = explode(Settings::MarkdownConverterConfig['list_item_style'], $data->text);
        $htmlListItems = array();
        foreach($listItems as $listItem) {
            if(!empty($listItem)) {
                $item = new \stdClass();
                $item->content = strip_tags($this->convertToHtml($listItem), '<strong><b><i><a><em>');
                $item->content = str_replace("\n", '', $item->content);
                $htmlListItems[] = $item;
            }
        }
        $data->listItems = $htmlListItems;
        unset($data->text);
        $data->format = 'html';

        $element->data = $data;
    }
}