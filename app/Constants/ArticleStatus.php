<?php

namespace App\Constants;

final class ArticleStatus {
    use ConstantsTrait;
    
    const Unpublished = 0;
    const Published = 1;
    const Deleted = 4;

    public static function getAllWithoutDeletedForDropdown() {
        return self::getForDropdown(array(self::Unpublished, self::Published));
    }
}