<?php
namespace App\Constants;

final class ElementType {
    use Constants;
    
    const Text = 'text';
    const SliderImage = 'slider_image';
    const DiwaneeImage = 'diwanee_image';
    const DiwaneeVideo = 'diwanee_video';
    const DiwaneeNode = 'diwanee_node';
    const DiwaneeList = 'diwanee_list';
    const ElementList = 'list';
    const Heading = 'heading';
    const Quote = 'quote';

    const textTypes = array(self::Text, self::Heading, self::Quote, self::DiwaneeNode);
    const imageTypes = array(self::DiwaneeImage, self::SliderImage);

    const itemsTypesSettings = array(
      self::DiwaneeNode => [
          'model' => 'App\\Node',
          'filter' => 'model_type'
      ],
      self::DiwaneeList => [
          'model' => 'App\\NodeList'
      ]
    );
}