<?php

namespace App\Converters;

use League\HTMLToMarkdown\HtmlConverter;

use App\Constants\ElementType;

class ToMarkdownConverter extends HtmlConverter {
    public function convertElementData($elementData) {
        $element = is_array($elementData) ? json_decode(json_encode($elementData), false) : $elementData;

        if(isset($element->data->format) && $element->data->format !== 'markdown') {
            if(in_array($element->type, ElementType::textTypes)) {
                $this->convertText($element);
            }

            if($element->type == ElementType::ElementList) {
                $this->convertList($element);
            }
        }

        if(is_array($elementData)) {
            $elementData = json_decode(json_encode($element), true);
            return $elementData;
        }
    }

    private function convertText($element) {
        $element->data->text = $this->convert($element->data->text);
        $element->data->format = 'markdown';
    }

    private function convertList($element) {
        $html = '<ul>';
        foreach($element->data->listItems as $listItem) {
            $html .= '<li>' . $listItem->content . '</li>';
        }
        $html .= '</ul>';

        $element->data->text = $this->convert($html);
        unset($element->data->listItems);
        $element->data->format = 'markdown';
    }
}