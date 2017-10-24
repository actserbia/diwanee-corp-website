<?php

namespace App\SWGModel;

/**
 * @SWG\Definition(required={"title", "type"}, type="object", @SWG\Xml(name="Article"))
 */
class Article {
    /**
     * @SWG\Property(example="")
     * @var string
     */
    public $title;

    /**
     * @SWG\Property(example="")
     * @var string
     */
    public $meta_title;

    /**
     * @SWG\Property(example="")
     * @var string
     */
    public $meta_description;

    /**
     * @SWG\Property(example="")
     * @var string
     */
    public $meta_keywords;

    /**
     * @SWG\Property(example="")
     * @var string
     */
    public $content_description;

    /**
     * @SWG\Property(example="")
     * @var string
     */
    public $external_url;

    /**
     * @SWG\Property(
     *     enum={0, 1}
     * )
     * @var string
     */
    public $status;

    /**
     * @SWG\Property()
     *
     * @var integer
     */
    public $publication;

    /**
     * @SWG\Property()
     *
     * @var integer
     */
    public $brand;

    /**
     * @SWG\Property()
     *
     * @var integer
     */
    public $category;

    /**
     * @SWG\Property(
     *     type = "array",
     *     example = {},
     *     @SWG\Items(
     *         type = "integer"
     *     )
     * )
     *
     * @var integer
     */
    public $subcategories;

    /**
     * @SWG\Property()
     *
     * @var integer
     */
    public $influencer;

    /**
     * @SWG\Property(
     *     example = {
     *          "data": {{
     *                  "type": "text",
     *                  "data": {
     *                      "text": "",
     *                      "format": "html"
     *                  }
     *              }
     *          }
     *     },
     * )
     *
     * @var string
     */
    public $content;
}
