<?php

namespace App\Constants;

final class ArticleStatus {
    const Unpublished = 0;
    const Published = 1;

    const all = array(
        self::Unpublished => 'Unpublished',
        self::Published => 'Published'
    );
}