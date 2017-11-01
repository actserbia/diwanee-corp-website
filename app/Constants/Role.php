<?php

namespace App\Constants;

final class Role {

    const User = 'user';
    const Brand = 'brand';
    const Moderator = 'moderator';
    const Admin = 'admin';

    public static function getAll() {
        return array(
            self::User => __('database.role.' . self::User),
            self::Brand => __('database.role.' . self::Brand),
            self::Moderator => __('database.role.' . self::Moderator),
            self::Admin => __('database.role.' . self::Admin)
        );
    }
}