<?php

namespace App\Constants;

final class ArticleStatus {
    const Unpublished = 0;
    const Published = 1;

    public static function getAll() {
        return array(
            self::Unpublished => __('database.article_status.unpublished'),
            self::Published => __('database.article_status.published')
        );
    }
}