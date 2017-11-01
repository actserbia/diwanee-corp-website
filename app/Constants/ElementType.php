<?php

namespace App\Constants;

final class ElementType {
    use ConstantsTrait;
    
    const Text = 'text';
    const SliderImage = 'slider_image';
    const DiwaneeImage = 'diwanee_image';
    const Video = 'video';
    const ElementList = 'list';
    const Heading = 'heading';
    const Quote = 'quote';
    
    const textTypes = array(self::Text, self::Heading, self::Quote);
    const imageTypes = array(self::DiwaneeImage, self::SliderImage);
}