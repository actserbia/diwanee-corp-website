<?php

namespace App\Utils;

class HtmlElementsClasses {
    const HtmlElementsClasses = array(
        'admin' => array(
            'label_for_element' => 'control-label col-md-3 col-sm-2 col-xs-12',
            'element_div_with_label' => 'col-md-6 col-sm-8 col-xs-12',
            'element_div_without_label' => 'col-md-6 col-sm-8 col-xs-12 col-md-offset-3'
        ),
        'app' => array(
            'label_for_element' => 'control-label col-md-4',
            'element_div_with_label' => 'col-md-6',
            'element_div_without_label' => 'col-md-6 col-md-offset-4'
        )
    );
    
    
    public static function getHtmlClassForElement($element = 'input', $template = 'admin') {
        return self::HtmlElementsClasses[$template][$element];
    }
}