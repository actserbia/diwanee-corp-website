<?php

namespace App\Constants;

final class TagType {
    const Publication = 'publication';
    const Brand = 'brand';
    const Category = 'category';
    const Subcategory = 'subcategory';
    const Influencer = 'influencer';

    const all = array(
        self::Publication => 'Publication',
        self::Brand => 'Brand',
        self::Category => 'Category',
        self::Subcategory => 'Subcategory',
        self::Influencer => 'Influencer'
    );
}