<?php

namespace App\Constants;

final class ElementType {
    const Text = 'text';
    const Image = 'image';
    const SliderImage = 'slider_image';
    const DiwaneeImage = 'diwanee_image';
    const Video = 'video';
    const ElementList = 'list';
    const Heading = 'heading';
    const Quote = 'quote';

    public static function populateTypes() {
        return array(
            'text',
            'image',
            'slider_image',
            'diwanee_image',
            'video',
            'list',
            'heading',
            'quote'
        );
    }
}