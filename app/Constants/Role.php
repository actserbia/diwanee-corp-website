<?php

namespace App\Constants;

final class Role {

    const User = 'user';
    const Brand = 'brand';
    const Moderator = 'moderator';
    const Admin = 'admin';

    public static function getRoles() {
        return array(
            self::User, self::Brand, self::Moderator, self::Admin
        );
    }
}