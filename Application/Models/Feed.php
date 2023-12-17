<?php

namespace Application\Models;

use System\Core\Model;

class Feed extends Model
{
    const TYPE_USER_STATUS = 'status';

    const ENTITY_TYPE = 'feed';

    private $_table = 'feeds';

    public function getTable()
    {
        return $this->_table;
    }

    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function update( $id, $data )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function getFeedByRef( $ref )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `ref` = ?";

        return $this->_db->query($SQL, [$ref])->get();
    }

    public function getMediaFeeds($viewer_id, $fromId = null, $limit = 10, $ignoreSuspended = false)
    {
        /**
         * @var \Application\Models\Follow
         */
        $followM = Model::get('\Application\Models\Follow');
        $followT = $followM->getTable();

        $hiddenM = Model::get('\Application\Models\HiddenEntities');
        $hiddenT = $hiddenM->getTable();

        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT `f`.* FROM `{$this->_table}` as `f`
                WHERE `f`.`deleted` <> 1
                AND `f`.`data` LIKE '%image%'
                AND (`f`.`user_id` IN (
                    SELECT `follow` FROM `{$followT}`
                    WHERE `follower` = ?
                ) OR `f`.`user_id` = ?) AND
                `f`.`id` NOT IN (
                    SELECT `entity_id` FROM `{$hiddenT}`
                    WHERE `entity_type` = 'feed'
                ) AND `f`.`user_id` = ?";

        $dbValues = array($viewer_id, $viewer_id, $viewer_id);

        if (is_numeric($fromId)) {
            $SQL .= " AND `f`.`id` <= ? ";
            $dbValues[] = $fromId;
        }

        if( $ignoreSuspended )
        {
            $SQL .= " AND `f`.`user_id` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $SQL .= "ORDER BY `f`.`id` DESC";

        if (is_numeric($limit)) {
            $SQL .= " LIMIT 0, ? ";
            $dbValues[] = (int) $limit;
        }

        $result = $this->_db->query($SQL, $dbValues)->getAll();
        return $result ? $result : [];
    }

    public function getCommentedFeeds($viewer_id, $fromId = null, $limit = 10, $ignoreSuspended = false)
    {
        /**
         * @var \Application\Models\Follow
         */
        $followM = Model::get('\Application\Models\Follow');
        $followT = $followM->getTable();

        $hiddenM = Model::get('\Application\Models\HiddenEntities');
        $hiddenT = $hiddenM->getTable();

        /**
         * @var \Application\Models\Comment
         */
        $commentM = Model::get('\Application\Models\Comment');
        $commentT = $commentM->getTable();

        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT `f`.* FROM `{$this->_table}` as `f`
                INNER JOIN `{$commentT}` as `c`
                ON (`c`.`entity_id` = `f`.`id`)
                WHERE `f`.`deleted` <> 1
                AND (`f`.`user_id` IN (
                    SELECT `follow` FROM `{$followT}`
                    WHERE `follower` = ?
                ) OR `f`.`user_id` = ?) AND
                `f`.`id` NOT IN (
                    SELECT `entity_id` FROM `{$hiddenT}`
                    WHERE `entity_type` = 'feed'
                ) AND `c`.`entity_type` = 'feed'
                AND `c`.`user_id` = ? AND `f`.`user_id` != ?";

        $dbValues = array($viewer_id, $viewer_id, $viewer_id, $viewer_id);

        if (is_numeric($fromId)) {
            $SQL .= " AND `f`.`id` <= ? ";
            $dbValues[] = $fromId;
        }

        if( $ignoreSuspended )
        {
            $SQL .= " AND `f`.`user_id` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $SQL .= "ORDER BY `f`.`id` DESC";

        if (is_numeric($limit)) {
            $SQL .= " LIMIT 0, ? ";
            $dbValues[] = (int) $limit;
        }

        $result = $this->_db->query($SQL, $dbValues)->getAll();
        return $result ? $result : [];
    }

    public function getLikedFeeds($viewer_id, $fromId = null, $limit = 10, $ignoreSuspended = false)
    {
        /**
         * @var \Application\Models\Follow
         */
        $followM = Model::get('\Application\Models\Follow');
        $followT = $followM->getTable();

        $hiddenM = Model::get('\Application\Models\HiddenEntities');
        $hiddenT = $hiddenM->getTable();

        $expressionM = Model::get('\Application\Models\Expression');
        $expressionT = $expressionM->getTable();

        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT `f`.* FROM `{$this->_table}` as `f`
                INNER JOIN `{$expressionT}` as `e`
                ON (`e`.`entity_id` = `f`.`id`)
                WHERE `f`.`deleted` <> 1
                AND (`f`.`user_id` IN (
                    SELECT `follow` FROM `{$followT}`
                    WHERE `follower` = ?
                ) OR `f`.`user_id` = ?) AND
                `f`.`id` NOT IN (
                    SELECT `entity_id` FROM `{$hiddenT}`
                    WHERE `entity_type` = 'feed'
                ) AND `e`.`type` = 'like'
                AND `e`.`user_id` = ? AND `f`.`user_id` != ?";

        $dbValues = array($viewer_id, $viewer_id, $viewer_id, $viewer_id);

        if (is_numeric($fromId)) {
            $SQL .= " AND `f`.`id` <= ? ";
            $dbValues[] = $fromId;
        }

        if( $ignoreSuspended )
        {
            $SQL .= " AND `f`.`user_id` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $SQL .= "ORDER BY `f`.`id` DESC";

        if (is_numeric($limit)) {
            $SQL .= " LIMIT 0, ? ";
            $dbValues[] = (int) $limit;
        }

        $result = $this->_db->query($SQL, $dbValues)->getAll();
        return $result ? $result : [];
    }

    public function getFeeds($viewer_id, $fromId = null, $limit = 10, $ignoreSuspended = false)
    {
        /**
         * @var \Application\Models\Follow
         */
        $followM = Model::get('\Application\Models\Follow');
        $followT = $followM->getTable();

        $hiddenM = Model::get('\Application\Models\HiddenEntities');
        $hiddenT = $hiddenM->getTable();

        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `deleted` <> 1
                AND (`user_id` IN (
                    SELECT `follow` FROM `{$followT}`
                    WHERE `follower` = ?
                ) OR `user_id` = ?) AND
                `id` NOT IN (
                    SELECT `entity_id` FROM `{$hiddenT}`
                    WHERE `entity_type` = 'feed'
                )";

        if( $ignoreSuspended )
        {
            $SQL .= " AND `user_id` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $dbValues = array($viewer_id, $viewer_id);

        if (is_numeric($fromId)) {
            $SQL .= " AND `id` <= ? ";
            $dbValues[] = $fromId;
        }

        $SQL .= "ORDER BY `id` DESC";

        if (is_numeric($limit)) {
            $SQL .= " LIMIT 0, ? ";
            $dbValues[] = (int) $limit;
        }

        $result = $this->_db->query($SQL, $dbValues)->getAll();
        return $result ? $result : [];
    }

    public function all(
        array $search = null,
        $offset = null,
        $limit = null,
        $from = null,
        $to = null,
        $searchValue = null,
        $ignoreSuspended = false
    ) {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $dbValues = [];
        $query = '';

        if ($searchValue != null) {
            $query .= " WHERE ";

            $query .= "(`u`.`name` LIKE '%$searchValue%' ||
            `u`.`email` LIKE '%$searchValue%' ||
            `f`.`text` LIKE '%$searchValue%')";
        }

        if (!empty($from) && !empty($to)) {
            $query .= !empty($query) ? ' AND ' : ' WHERE ';

            $query .= ' `f`.`created_at` > ? AND `f`.`created_at` < ? ';
            $dbValues[] = (int) $from;
            $dbValues[] = (int) $to;
        }

        if( $ignoreSuspended )
        {
            $query .= !empty($query) ? ' AND ' : ' WHERE ';

            $query .= " `u`.`suspended` = 0 ";
        }

        $SQL = "SELECT
                    `f`.*,
                    `u`.`id` as `owner_id`,
                    `u`.`name` as `owner_name`,
                    `u`.`email` as `owner_email`
                FROM `{$this->_table}` as `f`
                INNER JOIN `{$userT}` as `u`
                ON (`f`.`user_id` = `u`.`id`)
                $query
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

    public function getFeedByIds( $ids )
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

    public function checkLatest($viewer_id, $lastId, $ignoreSuspended = false)
    {
        /**
         * @var \Application\Models\Follow
         */
        $followM = Model::get('\Application\Models\Follow');
        $followT = $followM->getTable();

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT count(*) as `count` FROM `{$this->_table}`
                WHERE `deleted` <> 1
                AND `user_id` IN (
                    SELECT `follow` FROM `{$followT}`
                    WHERE `follower` = ?
                ) AND `id` > ? ";

        if( $ignoreSuspended )
        {
            $SQL .= " AND `user_id` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $dbValues = array($viewer_id, $lastId);

        $result = $this->_db->query($SQL, $dbValues)->get();
        return $result['count'];
    }

    public function getProfileFeeds($userId, $fromId, $limit = 10)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
            WHERE `user_id` = ? AND `deleted` <> 1 ";

        $dbValues = array($userId);

        if (is_numeric($fromId)) {
            $SQL .= " AND `id` <= ? ";
            $dbValues[] = $fromId;
        }

        $SQL .= "ORDER BY `id` DESC";

        if (is_numeric($limit)) {
            $SQL .= " LIMIT 0, ? ";
            $dbValues[] = (int) $limit;
        }

        $result = $this->_db->query($SQL, $dbValues)->getAll();
        return $result ? $result : [];
    }

    public function getFeed($id, $includeDeleted = false)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
            WHERE `id` = ? ";

        if ($includeDeleted) $SQL .= " AND `deleted` <> 1";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function getAllFeeds($includeDeleted = false)
    {
        $SQL = "SELECT * FROM `{$this->_table}` ";

        if ($includeDeleted) $SQL .= " AND `deleted` <> 1";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function delete($id)
    {
        return $this->_db->update($this->_table, $id, array('deleted' => 1));
    }

    public function countAllFeeds( $ignoreSuspended = false )
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT COUNT(*) as `count`
                FROM `{$this->_table}`
                WHERE
                `deleted` <> 1";

        if( $ignoreSuspended )
        {
            $SQL .= " AND `user_id` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $result = $this->_db->query($SQL)->get();

        return $result ? $result['count'] : 0;
    }

    public function countFeeds($userId, $ignoreSuspended = false )
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT COUNT(*) as `count`
                FROM `{$this->_table}`
                WHERE
                    `user_id` = ?
                    AND `deleted` <> 1";

        if( $ignoreSuspended )
        {
            $SQL .= " AND `user_id` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $result = $this->_db->query($SQL, [$userId])->get();

        return $result ? $result['count'] : 0;
    }

    public function search(
        $term,
        $subSpecs = null,
        $userIds = null,
        $fromId = null,
        $limit = null,
        $ignoreSuspended = false
    ) {

        /**
         * @var UserSubSpecialty
         */
        $userM = Model::get(UserSubSpecialty::class);
        $subTable = $userM->getTable();

        /**
         * @var User
         */
        $userM = Model::get(User::class);
        $userTable = $userM->getTable();

        $SQL = "SELECT DISTINCT `f`.*
                FROM `{$this->_table}` AS `f`
                LEFT JOIN `{$subTable}` AS `s`
                ON ( `f`.`user_id` = `s`.`user_id` )
                LEFT JOIN `{$userTable}` AS `u`
                ON ( `f`.`user_id` = `u`.`id` )
                WHERE `f`.`text` LIKE ?
                AND `f`.`deleted` <> 1";

        if( $ignoreSuspended )
        {
            $SQL .= " AND `u`.`suspended` = 0 ";
        }

        $dbValues = [ '%' . $term . '%' ];

        $subSpecs = (array) $subSpecs;
        if ( !empty($subSpecs) )
        {
            $placeHolders = array_fill(0, count($subSpecs), '?');
            $values = array_values($subSpecs);

            $placeHolders = implode(', ', $placeHolders);

            $SQL .= " AND `s`.`specialty` iN ({$placeHolders}) ";
            $dbValues = array_merge($dbValues, $values);
        }

        $userIds = (array) $userIds;
        if ( !empty($userIds) )
        {
            $placeHolders = array_fill(0, count($userIds), '?');
            $values = array_values($userIds);

            $placeHolders = implode(', ', $placeHolders);

            $SQL .= " AND `f`.`user_id` iN ({$placeHolders}) ";
            $dbValues = array_merge($dbValues, $values);
        }

        if ( $fromId )
        {
            $SQL .= " AND `f`.`id` <= ?  ";
            $dbValues[] = $fromId;
        }

        $SQL .= " ORDER BY `f`.`id` DESC";

        if ( $limit )
        {
            $SQL .= " LIMIT 0, ? ";
            $dbValues[] = $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }
}
