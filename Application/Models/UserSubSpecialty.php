<?php

namespace Application\Models;

use Application\Controllers\Admin\SubSpecialists;
use System\Core\Model;

class UserSubSpecialty extends Model
{

    private $_table = 'user_sub_specialties';

    public function getTable()
    {
        return $this->_table;
    }

    public function create( $userId, $specialties )
    {
        if ( empty($specialties) ) return false;

        $specialties = (array) $specialties;

        $SQL = "INSERT INTO `{$this->_table}` 
            ( `user_id`, `specialty`, `created_at` )
            VALUES ";

        $dbValues = array();
        $values = array();

        foreach( $specialties as $specialty )
        {
            $values[] = "(?, ?, ?)";
            $dbValues[] = $userId;
            $dbValues[] = $specialty;
            $dbValues[] = time();
        }

        $SQL .= implode(", ", $values);

        return (bool) $this->_db->query($SQL, $dbValues)->rowCount();
    }

    public function delete( $userId )
    {
        $SQL = "DELETE FROM `{$this->_table}`
            WHERE `user_id` = ?";
        
        return (bool) $this->_db->query($SQL, [$userId])->rowCount();
    }

    public function getByUserIds( $ids )
    {
        if ( empty($ids) ) return [];

        $ids = (array) $ids;

        $sTable = Model::get(SubSpecialty::class)->getTable();

        $SQL = "SELECT `st`.*, `us`.`user_id` FROM `{$this->_table}` AS `us`
                INNER JOIN `{$sTable}` AS `st`
                ON ( `us`.`specialty` = `st`.`id` )
                WHERE `user_id` IN ";

        $placeholders = array_fill(0, count($ids), '?');
        $values = array_values($ids);

        $SQL .= " (" . implode(', ', $placeholders) . ") ";

        $result = $this->_db->query($SQL, $values)->getAll();
        
        $output = [];
        foreach ( $result as $row )
        {
            $output[$row['user_id']][] = $row;
        }

        return $output;
    }

    public function getUserSpl( $userId )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `user_id` = ?";

        $result = $this->_db->query($SQL, [$userId])->getAll();
        if ( empty($result) ) return array();

        $ids = [];
        foreach ( $result as $row )
        {
            $ids[$row['specialty']] = $row;
        }

        return $ids;
    }

    public function getSpl( $userId )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `user_id` = ?";

        $result = $this->_db->query($SQL, [$userId])->getAll();
        if ( empty($result) ) return array();

        $ids = [];
        foreach ( $result as $row )
        {
            $ids[$row['specialty']] = $row['specialty'];
        }

        /**
         * @var \Application\Models\Specialty
         */
        $splM = Model::get('\Application\Models\SubSpecialty');
        $result = $splM->getByIdList($ids);

        return $result;
    }

    public function getTrending( $limit = null )
    {
        /**
         * @var SubSpecialty
         */
        $subM = Model::get(SubSpecialty::class);
        $subTable = $subM->getTable();

        $SQL = "SELECT `s`.*, `x`.`count` FROM (
                    SELECT *, COUNT(`id`) as `count` FROM `{$this->_table}`
                    GROUP BY `specialty`
            ) AS `x`
            INNER JOIN `{$subTable}` AS `s`
            ON ( `s`.`id` = `x`.`specialty` )
            ORDER BY `x`.`count` DESC";

        $dbValues = [];
        if ( is_numeric($limit) )
        {
            $SQL .= " LIMIT 0, ? ";
            $dbValues[] = $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }
}