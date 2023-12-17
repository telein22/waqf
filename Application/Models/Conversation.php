<?php

namespace Application\Models;

use System\Core\Config;
use System\Core\Model;

class Conversation extends Model
{
    const STATUS_CURRENT = 'current';
    const STATUS_CANCELED = 'canceled';
    const STATUS_COMPLETED = 'completed';

    const ENTITY_TYPE = 'conversation';

    private $_table = 'conversations';

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function update( $id, $data )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function getById( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
        WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function listAll( $status)
    {
        $SQL = "SELECT COUNT(*) as `count` FROM `{$this->_table}`
                WHERE `status` = ? AND `is_temp` = 0";

        $userInfo = Model::get(User::class)->getInfo();

        if ($userInfo['type'] == 'entity') {
            $SQL .= " AND `owner_id` IN (SELECT `id` from `users` WHERE `entity_id` =  {$userInfo['id']} )";
        }

        $result =  $this->_db->query($SQL, [$status])->get();

        return $result['count'];
    }

    public function list( $from = null, $to = null )
    {
        $dbValues = array();
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `is_temp` = 0";

        if( !empty($from) && !empty($to) )
        {
            $SQL .= '`created_at` > ? AND `created_at` < ?';

            $dbValues[] = (int) $from;
            $dbValues[] = (int) $to;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function getInfoByIds( $ids )
    {
        if ( empty($ids) ) return [];

        $ids = (array) $ids;
        $SQL = "SELECT * FROM `{$this->_table}`
            WHERE `id` IN ";

        $placeholder = array_fill(0, count($ids), '?');
        $values = array_values($ids);

        $SQL .= " (" . implode(', ', $placeholder) . ")";

        $result = $this->_db->query($SQL, $values)->getAll();

        if ( !$result ) return [];

        $output = [];
        foreach ( $result as $row )
        {
            $output[$row['id']] = $row;
        }

        return $output;
    }

    public function getList(
        array $where, $skip = null,
        $limit = null
    ) {
        $SQL = "SELECT
            `c`.*
        FROM
        `{$this->_table}` AS `c`";

        $keys = array_keys($where);
        $dbValues = array();

        $finalKeys = array();
        foreach( $keys as $key )
        {
            $values = (array) $where[$key];
            $placeHolders = array();
            foreach ( $values as $value )
            {
                $placeHolders[] = " `c`.`{$key}` = ?";
                $dbValues[] = $value;
            }

            $finalKeys[] = " ( " . implode(" OR ", $placeHolders) . " ) ";
        }

        if ( !empty($finalKeys) )
        {
            $keys = implode(" AND ", $finalKeys);
            $SQL .= " WHERE {$keys} ";
        }

        $SQL .= " ORDER BY `c`.`id` DESC ";

        if ( is_numeric($skip) && is_numeric($limit) )
        {
            $SQL .= " LIMIT ?, ? ";
            $dbValues[] = (int) $skip;
            $dbValues[] = (int) $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function searchOpponents( $term, $viewerId, $isAdvisor, $limit = null )
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userTable = $userM->getTable();

        /**
         * @var \Application\Models\Participant
         */
        $partiM = Model::get('\Application\Models\Participant');
        $partiTable = $partiM->table();

        $SUBSQL = "SELECT `p`.`entity_id`
                FROM `{$partiTable}` AS `p`
                WHERE `p`.`user_id` = :vId
                AND `p`.`entity_type` = '" .  Conversation::ENTITY_TYPE. "'";

        $SQL = "SELECT
                DISTINCT `u`.`id`,
                `u`.`name`
                FROM `{$this->_table}` AS `c`
                INNER JOIN `{$partiTable}` AS `pp`
                ON (
                    `pp`.`entity_id` = `c`.`id` AND
                    `pp`.`entity_type` = '" . Conversation::ENTITY_TYPE . "'
                )
                INNER JOIN `{$userTable}` AS `u`
                ON ( `pp`.`user_id` = `u`.`id` )
                WHERE `u`.`id` <> :vId AND `u`.`name` LIKE :like AND `c`.`is_temp` <> 1 AND `c`.`id` IN ({$SUBSQL})";

        $SQL .= $isAdvisor ? " AND `c`.`owner_id` = :vId " : " AND `c`.`created_by` = :vId ";

        $dbValues = [ ':vId' => $viewerId, ":like" => "%{$term}%" ];
        $SQL .= " ORDER BY `c`.`id` DESC ";

        if ( is_numeric($limit) )
        {
            $SQL .= " LIMIT 0, :limit ";
            $dbValues[':limit'] = $limit;
        }

        // var_dump("asd", $SQL, $dbValues);exit;

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function findUpcoming( $userId, $limit = null, $isAdvisor = true )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `status` = ?
                AND `created_at` >= ? ";

        $timeout = Config::get('Website')->conversation_timeout;
        $time = time() - $timeout;

        $dbValues = [self::STATUS_CURRENT, $time];

        if ( $isAdvisor )
        {
            $SQL .= " AND `owner_id` = ? ";
            $dbValues[] = $userId;

        } else{

            $SQL .= " AND `created_by` = ? ";
            $dbValues[] = $userId;
        }


        if ( is_numeric($limit) )
        {
            $SQL .= " LIMIT 0, ?";
            $dbValues[] = (int) $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

}