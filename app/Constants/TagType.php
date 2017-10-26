<?php

namespace App\Constants;

final class TagType {
    const Publication = 'publication';
    const Brand = 'brand';
    const Category = 'category';
    const Subcategory = 'subcategory';
    const Influencer = 'influencer';

    const all = array(
        TagType::Publication => 'Publication',
        TagType::Brand => 'Brand',
        TagType::Category => 'Category',
        TagType::Subcategory => 'Subcategory',
        TagType::Influencer => 'Influencer'
    );
}