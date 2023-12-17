<?php

namespace Application\Models;

use System\Core\Model;

class Participant extends Model
{
    private $_table = 'participants';

    public function table()
    {
        return $this->_table;
    }

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function all( $entityId, $entityType )
    {
        $SQL = "SELECT * FROM
            `{$this->_table}`
            WHERE `entity_id` = ?
            AND `entity_type` = ?";
        
        return $this->_db->query($SQL, [$entityId, $entityType])->getAll();
    }

    public function getByEntities( array $entities )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE ";

        $wheres = [];
        $dbValues = [];
        foreach ( $entities as $key => $entities )
        {
            $entities = (array) $entities;

            foreach ( $entities as $id )
            {
               $wheres[] =  "( `entity_type` = ? AND `entity_id` = ? )";
               $dbValues[] = $key;
               $dbValues[] = $id;
            }

        }

        $SQL .= implode(" AND ", $wheres);
        $SQL .= " ORDER BY `id` DESC ";

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function getParticipated( $userId, $entityType, $skip = null, $limit = null )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
            WHERE `user_id` = ?
            AND `entity_type` = ?";

        $dbValues = array($userId, $entityType);

        if ( is_numeric($skip) && is_numeric($limit) )
        {
            $SQL .= " LIMIT ?, ? ";
            $dbValues[] = $skip;
            $dbValues[] = $limit;
        }

        $SQL .= "ORDER BY `id` DESC";

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function count( $entityId, $entityType )
    {
        $entityId = (array) $entityId;

        $SQL = "SELECT COUNT(*) as `count`, `entity_id` FROM
            `{$this->_table}`
            WHERE `entity_id` IN (" . implode(", ", array_fill(0, count($entityId), '?')) . ")
            AND `entity_type` = ? GROUP BY `entity_id` ";
        
        $dbValues = array_values($entityId);
        $dbValues[] = $entityType;
        
        $result = $this->_db->query($SQL, $dbValues)->getAll();
        $output = [];
        foreach ( $result as $row )
        {
            $output[$row['entity_id']] = $row['count'];
        }

        return $output;
    }

    public function isParticipated( $userId, $entityId, $entityType )
    {
        $SQL = "SELECT 1 FROM `{$this->_table}`
                WHERE `entity_id` = ?
                AND `entity_type` = ?
                AND `user_id` = ?";
        
        $result = $this->_db->query($SQL, [$entityId, $entityType, $userId])->get();

        return (bool) $result;
    }

    public function delete( $entityType, $entityId, $userId = null )
    {
        $SQL = "DELETE FROM `{$this->_table}`
            WHERE `entity_id` = ? 
            AND `entity_type` = ? ";

        $dbValues = [$entityId, $entityType];

        if ( $userId )
        {
            $SQL .= "AND `user_id` = ?";
            $dbValues[] = $userId;
        }

        return (bool) $this->_db->query($SQL, $dbValues)->rowCount();
    }

    public function setMeetingAuthToken(int $id, string $authToken)
    {
        $SQL = "UPDATE `{$this->_table}` SET `meeting_auth_token` = ?
                WHERE `id` = ?";

        $this->_db->query($SQL, [$authToken, $id]);
    }

    public function getParticipant( $userId, $entityId, $entityType )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `entity_id` = ?
                AND `entity_type` = ?
                AND `user_id` = ?";

        return $this->_db->query($SQL, [$entityId, $entityType, $userId])->get();
    }

}