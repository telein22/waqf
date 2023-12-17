<?php

namespace Application\Models;

use Application\Helpers\AppHelper;
use Application\Helpers\Calendar;
use Application\Helpers\TenantHelper;
use Application\Modules\Events\WorkshopEvent;
use System\Core\Config;
use System\Core\Model;

class Workshop extends Model
{
    const STATUS_CURRENT = 'current';
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_CANCELED = 'canceled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_PREPARING = 'preparing';

    const ENTITY_TYPE = 'workshop';

    private $_table = 'workshops';

    public function create($data)
    {
        $data['tenant_id'] = TenantHelper::getId();
        return $this->_db->insert($this->_table, $data);
    }

    public function delete($id)
    {
        $SQL = "DELETE FROM `{$this->_table}`
                WHERE `id` = ?";

        return $this->_db->query($SQL, [$id]);
    }

    public function update($id, $data)
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function getList(
        array $where, $skip = null,
        $limit = null, $participant = null,
        $excludeParti = false
    )
    {
        $SQL = "SELECT
                    `w`.`id`,
                    `w`.`user_id`,
                    `w`.`desc`,
                    `w`.`name`,
                    `w`.`date`,
                    `w`.`duration`,
                    `w`.`price`,
                    `w`.`capacity`,
                    `w`.`charity`,
                    `w`.`invite`,
                    `w`.`status`,
                    `w`.`created_at`
                FROM
                `{$this->_table}` AS `w`";

        $keys = array_keys($where);
        $dbValues = array();

        $finalKeys = array();
        foreach ($keys as $key) {
            $values = (array)$where[$key];
            $placeHolders = array();
            foreach ($values as $value) {
                if ($key == 'name') {
                    $placeHolders[] = " `w`.`{$key}` LIKE ?";
                    $dbValues[] = "%$value%";
                    continue;
                }

                if ($key == 'date') {
                    $placeHolders[] = " DATE(`w`.`date`) = ?";
                    $dbValues[] = $value;
                    continue;
                }

                if ($key == 'datetime') {
                    $placeHolders[] = " `w`.`date` = ?";
                    $dbValues[] = $value;
                    continue;
                }

                $placeHolders[] = " `w`.`{$key}` = ?";
                $dbValues[] = $value;
            }

            $finalKeys[] = " ( " . implode(" OR ", $placeHolders) . " ) ";
        }

        if (!empty($finalKeys)) {
            $keys = implode(" AND ", $finalKeys);
            $SQL .= " WHERE {$keys} ";
        }

        if (!empty($finalKeys)) {
            $SQL .= " AND w.user_id = ? OR EXISTS (SELECT 1 FROM participants WHERE participants.entity_id = w.id AND participants.entity_type = 'workshop' AND participants.user_id = ?)";
        } else {
            $SQL .= " WHERE w.user_id = ? OR EXISTS (SELECT 1 FROM participants WHERE participants.entity_id = w.id AND participants.entity_type = 'workshop' AND participants.user_id = ?)";
        }

        $dbValues[]= $participant;
        $dbValues[]= $participant;

        $SQL .= " AND `tenant_id` = ? ORDER BY `w`.`id` DESC ";
        $dbValues[] = TenantHelper::getId();

        if (is_numeric($skip) && is_numeric($limit)) {
            $SQL .= " LIMIT ?, ? ";
            $dbValues[] = (int)$skip;
            $dbValues[] = (int)$limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function findUserWorkshops($userId, $skip = null, $limit = null, $date = null, $onlyFollowing = false)
    {
        $dbValues = [$userId];

        /**
         * @var Follow
         */
        $followM = Model::get(Follow::class);
        $followT = $followM->getTable();

        $SQL = "SELECT * FROM
                `{$this->_table}`            
                WHERE
                `tenant_id` = ?
                AND `user_id` = ?
                AND `date` > NOW() ";

        $dbValues[] = TenantHelper::getId();

        if ($onlyFollowing) {
            $SQL .= " AND `user_id` IN (
                SELECT `follow` FROM `{$followT}`
                WHERE `follower` = ?
            )";

            $dbValues[] = $userId;
        }

        if (!empty($date)) {
            $SQL .= " AND `date` LIKE '%$date%' ";
        }

        $SQL .= " ORDER BY `id` DESC ";

        if (is_numeric($skip) && is_numeric($limit)) {
            $SQL .= " LIMIT ?, ?";
            $dbValues[] = (int)$skip;
            $dbValues[] = (int)$limit;
        }

        // var_dump($SQL);exit;

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function findForBooking($userId, $skip = null, $limit = null, $date = null, $onlyFollowing = false, $names = null)
    {
        $dbValues = [$userId];

        /**
         * @var Follow
         */
        $followM = Model::get(Follow::class);
        $followT = $followM->getTable();

        $SQL = "SELECT * FROM
                `{$this->_table}`
                WHERE `user_id` <> ?
                AND DATE_ADD(date, INTERVAL duration minute) > NOW()
                AND status in (?, ?)
                AND `tenant_id` = ?";

        if (!empty($names)) {
            $placeHolders = array();

            foreach ($names as $name) {
                $placeHolders[] = " `name` LIKE ? ";
                $dbValues[] = $name;
            }

            $final[] = " ( " . implode(" OR ", $placeHolders) . " ) ";
        }

        if (!empty($final)) {
            $keys = implode(" AND ", $final);
            $SQL .= " AND $keys ";
        }

        if ($onlyFollowing) {
            $SQL .= " AND `user_id` IN (
                SELECT `follow` FROM `{$followT}`
                WHERE `follower` = ?
            )";

            $dbValues[] = $userId;
        }

        $dbValues[] = self::STATUS_NOT_STARTED;
        $dbValues[] = self::STATUS_CURRENT;
        $dbValues[] = TenantHelper::getId();

        if (!empty($date)) {
            $SQL .= " AND `date` LIKE '%$date%' ";
        }

        $SQL .= " ORDER BY `date` ";

        if (is_numeric($skip) && is_numeric($limit)) {
            $SQL .= " LIMIT ?, ?";
            $dbValues[] = (int)$skip;
            $dbValues[] = (int)$limit;
        }

        // var_dump($SQL);exit;

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function findUpcoming($userId, $limit = null, $isAdvisor = true)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `status` = ?
                  AND `tenant_id` = ?
                AND `date` > NOW()";

        $dbValues = [self::STATUS_NOT_STARTED, TenantHelper::getId()];

        if ($isAdvisor) {
            $SQL .= " AND `user_id` = ? ";
            $dbValues[] = $userId;

        } else {

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


        if (is_numeric($limit)) {
            $SQL .= " LIMIT 0, ?";
            $dbValues[] = (int)$limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function upcomingOrCurrent(int $userId)
    {
        $waitingRoomLimit = Config::get('Website')->session_waiting_room_limit;
        $participantM = Model::get(Participant::class);
        $participantTable = $participantM->table();

        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `status` IN (?, ?, ?)
                AND (DATE_SUB(date, INTERVAL ? minute) <= NOW() AND DATE_ADD(date, INTERVAL duration minute) >= NOW())
                AND ((user_id = ? AND EXISTS (SELECT 1 FROM {$participantTable} WHERE entity_type = ? AND entity_id = `{$this->_table}`.id) ) OR id IN (SELECT entity_id FROM {$participantTable} WHERE entity_type = ? AND user_id = ?))
                AND `tenant_id` = ?";

        $dbValues = [
            self::STATUS_NOT_STARTED,
            self::STATUS_CURRENT,
            self::STATUS_PREPARING,
            $waitingRoomLimit,
            $userId,
            self::ENTITY_TYPE,
            self::ENTITY_TYPE,
            $userId,
            TenantHelper::getId()
        ];

        return $this->_db->query($SQL, $dbValues)->get();
    }

    public function getInfoById($id)
    {
        $SQL = "SELECT * FROM `{$this->_table}` 
                WHERE `id` = ? AND `tenant_id` = ?";

        return $this->_db->query($SQL, [$id, TenantHelper::getId()])->get();
    }

    public function getById($id)
    {
        $SQL = "SELECT * FROM `{$this->_table}` 
                WHERE `id` = ? AND `tenant_id` = ?";

        return $this->_db->query($SQL, [$id, TenantHelper::getId()])->get();
    }

    public function listByStatus($status)
    {
        $SQL = "SELECT COUNT(*) as `count` FROM `{$this->_table}` 
                WHERE `status` = ? AND `tenant_id` = ?";

        $userInfo = Model::get(User::class)->getInfo();

        if ($userInfo['type'] == 'entity') {
            $SQL .= " AND `user_id` IN (SELECT `id` from `users` WHERE `entity_id` =  {$userInfo['id']} )";
        }

        $result = $this->_db->query($SQL, [$status, TenantHelper::getId()])->get();

        return $result['count'];
    }

    public function getInfoByIds($ids)
    {
        if (empty($ids)) return [];

        $ids = (array)$ids;
        $SQL = "SELECT * FROM `{$this->_table}`
            WHERE `id` IN ";

        $placeholder = array_fill(0, count($ids), '?');
        $values = array_values($ids);

        $SQL .= " (" . implode(', ', $placeholder) . ")";

        $SQL .= ' AND `tenant_id` = ?';
        $values[]= TenantHelper::getId();
        $result = $this->_db->query($SQL, $values)->getAll();

        if (!$result) return [];

        $output = [];
        foreach ($result as $row) {
            $output[$row['id']] = $row;
        }

        return $output;
    }

    public function getSlotCountAt($startDatetime, $endDateTime)
    {
        $SQL = "SELECT (SUM(`capacity`) + COUNT(*)) AS `count` FROM `{$this->_table}`
            WHERE (
                (
                    `date` <= ?
                    AND ADDDATE(`date`, INTERVAL `duration` MINUTE) > ?
                ) OR (
                    `date` < ?
                    AND ADDDATE(`date`, INTERVAL `duration` MINUTE) >= ?
                ) OR (
                    `date` > ?
                    AND ADDDATE(`date`, INTERVAL `duration` MINUTE) <= ?
                )
            ) AND `status` <> ?
            ";

        $result = $this->_db->query($SQL, [
            $startDatetime, $startDatetime, $endDateTime,
            $endDateTime, $startDatetime, $endDateTime,
            self::STATUS_CANCELED
        ])->get();


        return $result['count'] ? $result['count'] : 0;
    }

    public function canUserAttend(array $workshop, int $userId = null): bool
    {
        $userId = $userId ?? User::getId();
        $participantM = Model::get(Participant::class);
        return $workshop['user_id'] != $userId && !$participantM->isParticipated($userId, $workshop['id'], self::ENTITY_TYPE);
    }

//    public function addToCalendar(WorkshopEvent $workshopEvent): Calendar {
//        $calendar = new Calendar($userInfo, $workshopEvent);
//        $calendar->save();
//
//        return $calendar;
//    }

    public function findWorkshopByGivenPeriod(int $userId, string $datetime, int $duration)
    {
            $SQL = "SELECT * FROM `{$this->_table}` WHERE ( 
               (date BETWEEN ? AND DATE_ADD(?, interval ? minute))
            OR (DATE_ADD(date, interval duration minute) BETWEEN ? AND DATE_ADD(?, interval ? minute)) 
            OR (? BETWEEN date AND DATE_ADD(date, interval duration minute)) 
            OR (DATE_ADD(?, interval ? minute) BETWEEN date AND DATE_ADD(date, interval duration minute)) 
            ) AND user_id = ? AND status in (?, ?) AND `tenant_id` = ?   limit 1";

            $result = $this->_db->query($SQL, [
                $datetime,
                $datetime,
                $duration,
                $datetime,
                $datetime,
                $duration,
                $datetime,
                $datetime,
                $duration,
                $userId,
                self::STATUS_CURRENT,
                self::STATUS_NOT_STARTED,
                TenantHelper::getId()
            ])->get();

        return $result;
    }

    public function getAllCurrentOrNotStartedWorkshops()
    {
        $SQL = "SELECT `id`, `name` FROM `{$this->_table}` WHERE `status` IN (?, ?) AND `date` >= NOW() AND `tenant_id` = ? ORDER BY `id` DESC";

        $result = $this->_db->query($SQL, [
            self::STATUS_NOT_STARTED,
            self::STATUS_CURRENT,
            TenantHelper::getId()
        ])->getAll();

        return $result;
    }

    public function getLastCreatedWorkshop()
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `tenant_id` = ?
                ORDER BY `id` DESC
                LIMIT 1";

        return $this->_db->query($SQL, [
            TenantHelper::getId()
        ])->get();
    }

    public function getPerformedMinutes()
    {
        $SQL = "SELECT sum(`duration`) as numMinutes FROM `{$this->_table}` WHERE `status` IN (?, ?) AND `tenant_id` = ?";
        $res = $this->_db->query($SQL, [
            self::STATUS_COMPLETED,
            self::STATUS_CURRENT,
            TenantHelper::getId()
        ])->get();

        return $res['numMinutes'];
    }

    public function getLatestOne($userId)
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `user_id` = ? AND `tenant_id` = ? ORDER BY id DESC LIMIT 1";
        return $this->_db->query($SQL, [
            $userId, TenantHelper::getId()
        ])->get();
    }
}