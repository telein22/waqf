<?php

namespace Application\Models;

use System\Core\Model;

class Reviews extends Model
{  
    const ENTITY_TYPE = 'reviews';

    private $_table = 'reviews';

    public function getTable()
    {
        return $this->_table;
    }

    public function update( $data, $id )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create( $data ) 
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function listAvgRatingsByType( $type ) 
    {
        $SQL = "SELECT
                    `r`.`entity_id`,
                    `r`.`entity_owner_id`,                    
                    `r`.`entity_type`,
                    AVG(`r`.`star`) as `avg_star`
                FROM `{$this->_table}` AS `r`
                WHERE `r`.`entity_type` = ?
                GROUP BY `r`.`entity_id`";

        return $this->_db->query($SQL, [$type])->getAll();
    }

    public function listByEntity( $entityId, $entityType ) 
    {
        $SQL = "SELECT * FROM `{$this->_table}` 
                WHERE `entity_id` = ?
                AND `entity_type` = ?
                ORDER BY `id` DESC";

        return $this->_db->query($SQL, [$entityId, $entityType])->getAll();
    }

    public function getReview( $userId, $entityId, $entityType )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `entity_id` = ? AND `entity_type` = ? AND `user_id` = ?";

        return $this->_db->query($SQL, [$entityId, $entityType, $userId])->get();
    }

    public function getReviewsUser( $id )
    {
        $SQL = "SELECT COUNT(*) as `count`, AVG(`star`) as `avg` FROM `{$this->_table}`
                WHERE `entity_owner_id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }
}