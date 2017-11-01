<?php

namespace App\Constants;

final class TagType {
    const Publication = 'publication';
    const Brand = 'brand';
    const Category = 'category';
    const Subcategory = 'subcategory';
    const Influencer = 'influencer';

    public static function getAll() {
        return array(
            self::Publication => __('database.tag_type.' . self::Publication),
            self::Brand => __('database.tag_type.' . self::Brand),
            self::Category => __('database.tag_type.' . self::Category),
            self::Subcategory => __('database.tag_type.' . self::Subcategory),
            self::Influencer => __('database.tag_type.' . self::Influencer)
        );
    }
}