<?php
namespace App\Constants;

final class ElementDataHeadingType {
    use Constants;

    const H1 = 'h1';
    const H2 = 'h2';
    const H3 = 'h3';
    const H4 = 'h4';
    const H5 = 'h5';

    const all = array(self::H1, self::H2, self::H3, self::H4, self::H5);
}