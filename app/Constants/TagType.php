<?php

namespace App\Constants;

final class TagType {
    use ConstantsTrait;
    
    const Publication = 'publication';
    const Brand = 'brand';
    const Category = 'category';
    const Subcategory = 'subcategory';
    const Influencer = 'influencer';
}