<?php

namespace App\Validation\Rules;

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


        $content = json_decode(str_replace("'", "\"", $value));
        if(!isset($content->data) || !is_array($content->data)) {
            $this->message = __('messages.check_sir_trevor_content.data_missing');
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
            $this->message = __('messages.check_sir_trevor_content.type_or_data_missing', ['elementIndex' => $index]);
            return false;
        }

        if(!in_array($element->type, ElementType::getAll())) {
            $this->message = __('messages.check_sir_trevor_content.type_not_valid', ['type' => $element->type, 'validTypes' => implode(',', ElementType::getAll())]);
            return false;
        }

        switch($element->type) {
            case ElementType::Quote:
                if(!isset($element->data->cite)) {
                    $this->message = __('messages.check_sir_trevor_content.data_param_missing', ['elementIndex' => $index, 'param' => 'cite']);
                    return false;
                }

            case ElementType::Text:
            case ElementType::Heading:
                if(!isset($element->data->text)) {
                    $this->message = __('messages.check_sir_trevor_content.data_param_missing', ['elementIndex' => $index, 'param' => 'text']);
                    return false;
                }
                if(!$this->checkElementFormat($element, $index)) {
                    $this->message = __('messages.check_sir_trevor_content.format_not_valid', ['elementIndex' => $index]);
                    return false;
                }
                break;

            case ElementType::DiwaneeImage:
            case ElementType::SliderImage:
                if(!isset($element->data->file->url) || !isset($element->data->seoname) || !isset($element->data->seoalt) || !isset($element->data->caption) || !isset($element->data->copyright)) {
                    $this->message = __('messages.check_sir_trevor_content.image_data_missing', ['elementIndex' => $index]);
                    return false;
                }
                break;

            case ElementType::DiwaneeVideo:
                if(!isset($element->data->remote_id) || !isset($element->data->source)) {
                    $this->message = __('messages.check_sir_trevor_content.video_data_missing', ['elementIndex' => $index]);
                    return false;
                }
                break;

            case ElementType::ElementList:
                if(!isset($element->data->listItems) || !is_array($element->data->listItems)) {
                    $this->message = __('messages.check_sir_trevor_content.list_items_not_valid', ['elementIndex' => $index]);
                    return false;
                }
                if(!$this->checkElementFormat($element, $index)) {
                    $this->message = __('messages.check_sir_trevor_content.format_not_valid', ['elementIndex' => $index]);
                    return false;
                }
                foreach($element->data->listItems as $itemIndex => $listItem) {
                    if(!isset($listItem->content)) {
                        $this->message = __('messages.check_sir_trevor_content.list_item_content_missing', ['elementIndex' => $index, 'itemIndex' => $itemIndex]);
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
        return __('messages.check_sir_trevor_content.not_valid_message', ['message' => $this->message]);
    }
}
