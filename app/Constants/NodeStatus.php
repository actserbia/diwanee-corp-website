<?php
namespace App\Constants;

final class NodeStatus {
    use Constants;
    
    const Unpublished = '0';
    const Published = '1';
    const Deleted = '4';

    const activeStatuses = array(self::Published);
    const inactiveStatuses = array(self::Deleted, self::Unpublished);

    public static function getAllWithoutDeletedForDropdown() {
        return self::getForDropdown(array(self::Unpublished, self::Published));
    }
}