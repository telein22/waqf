<?php

namespace Application\Helpers;

use System\Core\Database;

class QueryHelper
{
    public static function markAsCompleted(string $tableName, array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $idString = implode(",", $ids);

        $SQL = "UPDATE `{$tableName}` SET `status` = ?
                WHERE `id` in ($idString)";

        $db = Database::get();
        $db->query($SQL, ['completed']);
    }
}