<?php

namespace Application\Models;

use System\Core\Config;
use System\Core\Model;
use Application\Helpers\Calendar;

class Call extends Model
{
    const STATUS_CURRENT = 'current';
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_CANCELED = 'canceled';
    const STATUS_COMPLETED = 'completed';

    const ENTITY_TYPE = 'call';

    private $_table = 'calls';

    public function getTable()
    {
        return $this->_table;
    }

    public function getList(
        int $userId,
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
                if ( $key == 'date' )
                {
                    $placeHolders[] = " DATE(`c`.`date`) = ?";
                    $dbValues[] = $value;
                    continue;
                }

                if ( $key == 'datetime' )
                {
                    $placeHolders[] = " `c`.`date` = ?";
                    $dbValues[] = $value;
                    continue;
                }

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

        if (empty($finalKeys))
        {
            $SQL .= " WHERE created_by = ? OR owner_id = ?";
        } else {
            $SQL .= " AND (created_by = ? OR owner_id = ?)";
        }
        $dbValues[] = $userId;
        $dbValues[] = $userId;


        $SQL .= " ORDER BY `c`.`id` DESC ";

        if ( is_numeric($skip) && is_numeric($limit) )
        {
            $SQL .= " LIMIT ?, ? ";
            $dbValues[] = (int) $skip;
            $dbValues[] = (int) $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }
    
    public function update( $id, $data )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function getBySlotId( $id, $isTemp = false )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
        WHERE `slot_id` = ?";

        if( $isTemp )
        {
            $SQL .= " AND `is_temp` = 0 ";
        }

        return $this->_db->query($SQL, [$id])->get();
    }

    public function getById( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
        WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function getByStatus( $status )
    {
        $SQL = "SELECT COUNT(*) as `count` FROM `{$this->_table}`
        WHERE `status` = ?";

        $userInfo = Model::get(User::class)->getInfo();

        if ($userInfo['type'] == 'entity') {
            $SQL .= " AND `owner_id` IN (SELECT `id` from `users` WHERE `entity_id` =  {$userInfo['id']} )";
        }

        $result = $this->_db->query($SQL, [$status])->get();

        return $result['count'];
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

    public function searchOpponents( $term, $viewerId, $isAdvisor, $limit = null )
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userTable = $userM->getTable();

        $join = $isAdvisor ? "( `c`.`created_by` = `u`.`id` )" : "( `c`.`owner_id` = `u`.`id` )" ;

        $SQL = "SELECT
                DISTINCT `u`.`id`,
                `u`.`name`
                FROM `{$this->_table}` AS `c`                
                INNER JOIN `{$userTable}` AS `u`
                ON {$join}
                WHERE `u`.`name` LIKE :like AND `c`.`is_temp` <> 1";

        $SQL .= $isAdvisor ? " AND `c`.`owner_id` = :vId " : " AND `c`.`created_by` = :vId ";

        $dbValues = [ ':vId' => $viewerId, ":like" => "%{$term}%" ];
        $SQL .= " ORDER BY `c`.`id` DESC ";

        if ( is_numeric($limit) )
        {
            $SQL .= " LIMIT 0, :limit ";
            $dbValues[':limit'] = $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    
    public function findUpcoming( $userId, $limit = null, $isAdvisor = true )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `status` = ?
                AND `date` > NOW()";

        $dbValues = [self::STATUS_NOT_STARTED];        

        if ( $isAdvisor )
        {
            $SQL .= " AND `owner_id` = ? ";
            $dbValues[] = $userId;

        } else{

            /**
             * @var Participant
             */
            $partiM = Model::get(Participant::class);
            $partiTable = $partiM->table();

            $SUBSQL = "SELECT `entity_id` FROM `{$partiTable}`
            WHERE `user_id` = ? AND `entity_type` = ?";

            $SQL .= " AND `id` IN ({$SUBSQL}) ";

            $dbValues[] = $userId;
            $dbValues[] = self::ENTITY_TYPE;
        }

        
        if ( is_numeric($limit) )
        {
            $SQL .= " LIMIT 0, ?";                
            $dbValues[] = (int) $limit;
        }

        // var_dump($SQL, $dbValues);
        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function upcomingOrCurrent(int $userId)
    {
        $waitingRoomLimit = Config::get('Website')->call_waiting_room_limit;
        $participantM = Model::get(Participant::class);
        $participantTable = $participantM->table();

        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `status` IN (?, ?)
        AND (DATE_SUB(date, INTERVAL ? minute) <= NOW() AND DATE_ADD(date, INTERVAL duration minute) >= NOW())
        AND ((owner_id = ? AND EXISTS (SELECT 1 FROM {$participantTable} WHERE entity_type = ? AND entity_id = `{$this->_table}`.id) ) OR id IN (SELECT entity_id FROM {$participantTable} WHERE entity_type = ? AND user_id = ?))";

        $dbValues = [
            self::STATUS_NOT_STARTED,
            self::STATUS_CURRENT,
            $waitingRoomLimit,
            $userId,
            self::ENTITY_TYPE,
            self::ENTITY_TYPE,
            $userId,
        ];

        return $this->_db->query($SQL, $dbValues)->get();
    }

    public function canUserAttend(array $call, int $userId = null): bool {
        $userId = $userId ?? User::getId();
        $participantM = Model::get(Participant::class);
        return $call['owner_id'] != $userId && !$participantM->isParticipated($userId, $call['id'], self::ENTITY_TYPE);
    }

    public function getPerformedMinutes()
    {
        $SQL = "SELECT sum(`duration`) as numMinutes FROM `{$this->_table}` WHERE `status` IN (?, ?)";
        $res = $this->_db->query($SQL, [
            self::STATUS_COMPLETED,
            self::STATUS_CURRENT
        ])->get();

        return $res['numMinutes'];
    }
}