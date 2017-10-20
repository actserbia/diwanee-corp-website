<?php

namespace App\Constants;

final class TagType {
    const Publication = 'publication';
    const Brand = 'brand';
    const Category = 'category';
    const Subcategory = 'subcategory';
    const Influencer = 'influencer';

    public static function populateTypes() {
        return array(
            'publication' => 'Publication',
            'brand' => 'Brand',
            'category' => 'Category',
            'subcategory' => 'Subcategory',
            'influencer' => 'Influencer'
        );
    }
}