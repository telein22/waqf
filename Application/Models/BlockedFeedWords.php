<?php

namespace Application\Models;

use System\Core\Model;

class BlockedFeedWords extends Model
{
    private $_table = 'blocked_feed_words';

    public function update($data, $id)
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function all(
        array $search = null,
        $offset = null,
        $limit = null,
        $from = null,
        $to = null,
        $searchValue = null
    ) {
        /**
         * @var \Application\Models\Follow
         */
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $feedM = Model::get('\Application\Models\Feed');
        $feedT = $feedM->getTable();

        $dbValues = [];
        $query = '';

        if ($searchValue != null) {
            $query .= "AND (`u`.`name` LIKE '%$searchValue%' ||
            `u`.`email` LIKE '%$searchValue%' || 
            `f`.`text` LIKE '%$searchValue%' ||
            `bfw`.`word` LIKE '%$searchValue%' )";
        }

        if (!empty($from) && !empty($to)) {

            $query .= 'AND `f`.`created_at` > ? AND `f`.`created_at` < ? ';
            $dbValues[] = (int) $from;
            $dbValues[] = (int) $to;
        }

        $SQL = "SELECT
                    `f`.*,
                    `bfw`.`id` as `blocked_feed_id`,
                    `u`.`id` as `owner_id`,
                    `u`.`name` as `owner_name`,
                    `u`.`email` as `owner_email`
                FROM `{$this->_table}` as `bfw`
                INNER JOIN `{$feedT}` as `f` 
                ON (`bfw`.`entity_id` = `f`.`id`)
                INNER JOIN `{$userT}` as `u` 
                ON (`f`.`user_id` = `u`.`id`)
                WHERE `bfw`.`entity_type` = 'feed'
                $query
                GROUP BY `bfw`.`entity_id`
                ORDER BY `f`.`id` DESC";

        if (is_numeric($offset) && is_numeric($limit)) {
            $SQL .= " LIMIT ?, ? ";
            $dbValues[] = (int) $offset;
            $dbValues[] = (int) $limit;
        }
        // var_dump($SQL);exit;

        $result = $this->_db->query($SQL, $dbValues)->getAll();
        return $result ? $result : [];
    }

    public function getByEntityId( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `entity_id` = '$id'";
        return $this->_db->query($SQL)->getAll();
    }

    public function getById($id)
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `id` = '$id'";
        return $this->_db->query($SQL)->get();
    }

    public function deleteById($id)
    {
        $SQL = "DELETE FROM `{$this->_table}` WHERE `id` = ?";

        return $this->_db->query($SQL, [$id]);
    }
}
