<?php

namespace Application\Models;

use System\Core\Model;

class HashTags extends Model
{

    private $_table = 'hash_tags';

    public function createBulk( $entityId, $entityType, array $tags )
    {
        $SQL = "INSERT INTO `{$this->_table}` (`entity_id`, `entity_type`, `tag`, `created_at`) VALUES ";
        $dbParams = [];
        $placeHolders = [];

        foreach ( $tags as $tag )
        {
            $placeHolders[] = "(?, ?, ?, ?)";
            $dbParams[] = $entityId;
            $dbParams[] = $entityType;
            $dbParams[] = $tag;
            $dbParams[] = time();
        }

        $SQL .= implode(", ", $placeHolders);

        $result = $this->_db->query($SQL, $dbParams);
        return (bool) $result->rowCount();
    }

    public function fetchMostUsed( $limit = null )
    {
        $SQL = "SELECT `x`.* FROM (
                SELECT *, count(`id`) as `count`
                FROM `{$this->_table}`
                GROUP BY `tag`
            ) AS `x` ORDER BY `x`.`count` DESC";

        $dbValues = [];
        if ( is_numeric($limit) )
        {
            $SQL .= " LIMIT 0, ? ";
            $dbValues[] = $limit;
        }
        
        return $this->_db->query($SQL, $dbValues)->getAll();
    }
}