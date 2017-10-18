<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Article;
use App\Constants\ElementType;
use App\Constants\Settings;

class Element extends Model {
    public function articles() {
		return $this->belongsToMany(Article::class, 'article_element', 'id_element', 'id_article');
    }

    public function subelements() {
        return $this->belongsToMany(Tag::class, 'element_subelement', 'id_element', 'id_subelement');
    }

    public function parentElement() {
        return $this->belongsTo(Tag::class, 'element_subelement', 'id_subelement', 'id_element');
    }

    public function populateBasicData($elementData) {
        $this->type = $elementData->type;

        $options = array();
        switch($this->type) {
            case ElementType::Text:
            case ElementType::Heading:
                $this->content = $elementData->data->text;
                $options['format'] = $elementData->data->format;
                break;
              
            case ElementType::Quote:
                $this->content = $elementData->data->text;
                $options['format'] = $elementData->data->format;
                $options['cite'] = $elementData->data->cite;
                break;

            case ElementType::Image:
            case ElementType::SliderImage:
                $this->content = str_replace(Settings::ImagesSrc, '', $elementData->data->file->url);
                break;

            case ElementType::Video:
                $this->content = $elementData->data->remote_id;
                $options['source'] = $elementData->data->source;
                break;

            case ElementType::ElementList:
                $this->content = json_encode($elementData->data->listItems);
                $options['format'] = $elementData->data->format;
                break;
        }
        $this->options = json_encode($options);
    }
}
