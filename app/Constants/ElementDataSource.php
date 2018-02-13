<?php
namespace App\Constants;

final class ElementDataSource {
    use Constants;

    const Youtube = 'youtube';
    const Vimeo = 'vimeo';
    const Vine = 'vine';
    const Dailymotion = 'dailymotion';
    const Kaltura = 'kaltura';

    const all = array(self::Youtube, self::Vimeo, self::Vine, self::Dailymotion, self::Kaltura);
}