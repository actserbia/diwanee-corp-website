<?php

namespace App\Constants;

final class Role {

    const User = 'user';
    const Brand = 'brand';
    const Moderator = 'moderator';
    const Admin = 'admin';

    const all = array(
        self::User => self::User,
        self::Brand => self::Brand,
        self::Moderator => self::Moderator,
        self::Admin => self::Admin
    );
}