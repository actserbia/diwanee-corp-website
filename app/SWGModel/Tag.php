<?php

namespace App\SWGModel;

/**
 * @SWG\Definition(required={"name", "type"}, type="object", @SWG\Xml(name="Tag"))
 */
class Tag {
    /**
     * @SWG\Property(example="")
     * @var string
     */
    public $name;

    /**
     * @var string
     * @SWG\Property(
     *     enum={"publication", "brand", "category", "subcategory", "influencer"}
     * )
     */
    public $type;

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
    public $parents;

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
    public $children;
}
