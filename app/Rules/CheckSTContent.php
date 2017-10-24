<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Constants\ElementType;

class CheckSTContent implements Rule
{
    private $message = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->message = '';
        
        
        $content = json_decode($value);
        if(!isset($content->data) || !is_array($content->data)) {
            $this->message = "It should be array with data key!";
            return false;
        }

        foreach($content->data as $index => $element) {
            if(!$this->checkElementContent($element, $index)) {
                return false;
            }
        }

        return true;
    }

    private function checkElementContent($element, $index) {
        if(!isset($element->type) || !isset($element->data)) {
            $this->message = "Element " . $index . " type or data not set!";
            return false;
        }

        if(!in_array($element->type, ElementType::populateTypes())) {
            $this->message = $element->type . ' is not valid sir trevor element type. Valid types are: ' . implode(',', ElementType::populateTypes());
            return false;
        }

        switch($element->type) {
            case ElementType::Quote:
                if(!isset($element->data->cite)) {
                    $this->message = "Element " . $index . " cite is not set!";
                    return false;
                }
                
            case ElementType::Text:
            case ElementType::Heading:
                if(!isset($element->data->text)) {
                    $this->message = "Element " . $index . " text is not set!";
                    return false;
                }
                if(!$this->checkElementFormat($element, $index)) {
                    $this->message = "Element " . $index . " format is not set or is not valid!";
                    return false;
                }
                break;

            case ElementType::DiwaneeImage:
            case ElementType::SliderImage:
                if(!isset($element->data->file->url) || !isset($element->data->seoname) || !isset($element->data->seoalt) || !isset($element->data->caption) || !isset($element->data->copyright)) {
                    $this->message = "Element " . $index . " data is not set!";
                    return false;
                }
                break;

            case ElementType::Video:
                if(!isset($element->data->remote_id) || !isset($element->data->source)) {
                    $this->message = "Element " . $index . " remote_id or source is not set!";
                    return false;
                }
                break;

            case ElementType::ElementList:
                if(!isset($element->data->listItems) || !is_array($element->data->listItems)) {
                    $$this->message = "Element " . $index . " listItems is not set or is not array!";
                    return false;
                }
                if(!$this->checkElementFormat($element, $index)) {
                    $this->message = "Element " . $index . " format is not set or is not valid!";
                    return false;
                }
                foreach($element->data->listItems as $key => $listItem) {
                    if(!isset($listItem->content)) {
                        $this->message = "Element " . $index . " list item " . $key . " content is not set!";
                        return false;
                    }
                }
                break;
        }

        return true;
    }

    private function checkElementFormat($element) {
        if(!isset($element->data->format) || !in_array($element->data->format, array('html', 'markdown'))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Content is not in valid sir trevor format! ' . $this->message;
    }
}
