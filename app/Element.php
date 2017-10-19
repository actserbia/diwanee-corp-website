<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use League\HTMLToMarkdown\HtmlConverter;
use League\CommonMark\CommonMarkConverter;

use App\Article;

class Element extends Model {

    protected $textTypes = array('text', 'heading', 'quote');

    public function articles() {
		return $this->belongsToMany(Article::class, 'article_element', 'id_element', 'id_article');
    }

    public function subelements() {
        return $this->belongsToMany(Tag::class, 'element_subelement', 'id_element', 'id_subelement');
    }

    public function parentElement() {
        return $this->belongsTo(Tag::class, 'element_subelement', 'id_subelement', 'id_element');
    }
    
    public function getEditorContentAttribute() {
        $elementData = array();
        
        $elementData['type'] = $this->type;
        $elementData['data'] = json_decode($this->content);

        return $elementData;
    }
    
    public function getJsonEncodedAttribute() {
        return is_string($this->content);
    }

    public function populateBasicData($elementData) {
        $this->type = $elementData->type;
        $elementData = $this->elementDataToMarkdown($elementData);
        $this->content = json_encode($elementData->data);
    }
    
    public function changeJsonEncodeFormat($encode) {
        $encode ? $this->encodeContent() : $this->decodeContent();
        if( in_array($this->type, $this->textTypes) && $this->content->format !== 'html') {
            $converter = new CommonMarkConverter();
            $this->content->text = $converter->convertToHtml($this->content->text);
            $this->content->format = 'html';
        }
    }

    private function decodeContent() {
        if($this->jsonEncoded) {
            $this->content = json_decode($this->content);
        }
    }

    private function encodeContent() {
        if(!$this->jsonEncoded) {
            $this->content = json_encode($this->content);
        }


    protected function elementDataToMarkdown($elementData) {
        $converter = new HtmlConverter();
        if( in_array($elementData->type, $this->textTypes) ) {
            $elementData->data->text = $converter->convert($elementData->data->text);
            $elementData->data->format = "markdown";
        }
        /*Currently SirTrevor func parseFromMarkdown is not working well with Lists*/
        /*else if( $elementData->type == 'list' ) {
            $listItems = array();
            $elementData->data->format = "markdown";
            foreach( $elementData->data->listItems as $listItem ) {
                $md = new \stdClass();
                $md->content = $converter->convert($listItem->content);
                $listItems[] = $md;
            }
            $elementData->data->listItems = $listItems;
        }*/
        return $elementData;
    }
}
