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

        switch($element->type) {

            case ElementType::DiwaneeList:
                if(!isset($element->data->item_id) || $element->data->item_id == 'undefined') {
                    $this->message = __('messages.check_sir_trevor_content.data_param_missing', ['elementIndex' => '', 'param' => 'nodes list']);
                    return false;
                }
                break;

            case ElementType::DiwaneeNode:
                if(!isset($element->data->item_id) || $element->data->item_id == 'undefined') {
                    $this->message = __('messages.check_sir_trevor_content.data_param_missing', ['elementIndex' => '', 'param' => 'node']);
                    return false;
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
