<?php

namespace App\Constants;

final class ElementType {
    const Text = 'text';
    const SliderImage = 'slider_image';
    const DiwaneeImage = 'diwanee_image';
    const Video = 'video';
    const ElementList = 'list';
    const Heading = 'heading';
    const Quote = 'quote';

    const all = array(
        ElementType::Text,
        ElementType::SliderImage,
        ElementType::DiwaneeImage,
        ElementType::Video,
        ElementType::ElementList,
        ElementType::Heading,
        ElementType::Quote
    );
    
    const textTypes = array(ElementType::Text, ElementType::Heading, ElementType::Quote);
    const imageTypes = array(ElementType::DiwaneeImage, ElementType::SliderImage);
}