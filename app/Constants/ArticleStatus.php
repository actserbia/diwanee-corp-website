<?php

namespace App\Constants;

final class ArticleStatus {
    const Unpublished = 0;
    const Published = 1;

    public static function populateStatus() {
        return array(
            0 => 'Unpublished',
            1 => 'Published'
        );
    }
}