<?php

namespace App\Constants;

final class ArticleStatus {
    use ConstantsTrait;
    
    const Unpublished = 0;
    const Published = 1;
    const Deleted = 4;
}