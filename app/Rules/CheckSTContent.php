<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Constants\ElementType;

class CheckSTContent implements Rule
{
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
        $content = json_decode($value);
        if(!isset($content->data) || !is_array($content->data)) {
            return false;
        }

        foreach($content->data as $element) {
            if(!$this->checkElementContent($element)) {
                return false;
            }
        }

        return true;
    }

    private function checkElementContent($element) {
        if(!isset($element->type) || !isset($element->data)) {
            return false;
        }

        if(!in_array($element->type, ElementType::populateTypes())) {
            return false;
        }

        switch($element->type) {
            case ElementType::Text:
            case ElementType::Heading:
                if(!isset($element->data->text) || !$this->checkElementFormat($element)) {
                    return false;
                }
                break;

            case ElementType::Quote:
                if(!isset($element->data->text) || !isset($element->data->cite) || !$this->checkElementFormat($element)) {
                    return false;
                }
                break;

            case ElementType::DiwaneeImage:
            case ElementType::SliderImage:
                if(!isset($element->data->file->url) || !isset($element->data->seoname) || !isset($element->data->seoalt) || !isset($element->data->caption) || !isset($element->data->copyright)) {
                    return false;
                }
                break;

            case ElementType::Video:
                if(!isset($element->data->remote_id) || !isset($element->data->source)) {
                    return false;
                }
                break;

            case ElementType::ElementList:
                if(!isset($element->data->listItems) || !is_array($element->data->listItems) || !$this->checkElementFormat($element)) {
                    return false;
                }
                foreach($element->data->listItems as $listItem) {
                    if(!isset($listItem['content'])) {
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
        return 'Content is not in valid sir trevor format!';
    }
}
