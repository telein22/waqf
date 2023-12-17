<?php 


namespace Application\Models;

use System\Core\Model;

class Follow extends Model
{

    private $_table = 'follows';

    public function follow( $followerId, $followId )
    {
        return $this->_db->insert($this->_table, array(
            'follower' => $followerId,
            'follow' => $followId,
            'created_at' => time(),
        ));
    }

    public function getTable()
    {
        return $this->_table;
    }

    public function isFollowing( $followerId, $followId )
    {
        $SQL = "SELECT 1 FROM `{$this->_table}` WHERE `follow` = ? AND `follower` = ?";

        return (bool) $this->_db->query($SQL, [$followId, $followerId])->rowCount();
    }

    public function unFollow( $followerId, $followId )
    {
        $SQL = "DELETE FROM `{$this->_table}` WHERE `follow` = ? AND `follower` = ?";

        return (bool) $this->_db->query($SQL, [$followId, $followerId])->rowCount();
    }

    public function followerCount( $userId, $ignoreSuspended = false )
    {

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT count(*) as `count` FROM `{$this->_table}` WHERE `follow` = ?";

        if( $ignoreSuspended )
        {
            $SQL .= " AND `follower` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $result = $this->_db->query($SQL, [$userId])->get();

        return $result ? $result['count'] : 0;
    }

    public function followCount( $userId, $ignoreSuspended = false )
    {

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT count(*) as `count` FROM `{$this->_table}` WHERE `follower` = ?";

        if( $ignoreSuspended )
        {
            $SQL .= " AND `follow` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $result = $this->_db->query($SQL, [$userId])->get();

        return $result ? $result['count'] : 0;
    }

    public function getFollowing( $userId, $skip = null, $limit = null, $ignoreSuspended = false )
    {

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT * FROM `{$this->_table}` WHERE `follower` = ? ";

        if( $ignoreSuspended )
        {
            $SQL .= " AND `follow` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $SQL .= " ORDER BY `id` DESC ";

        $dbValues = [$userId];

        if ( is_numeric($skip) && is_numeric($limit) )
        {
            $SQL .= " LIMIT ?, ? ";
            $dbValues[] = (int) $skip;
            $dbValues[] = (int) $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }
    
    public function getFollowers( $userId, $skip = null, $limit = null, $ignoreSuspended = false )
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT * FROM `{$this->_table}` WHERE `follow` = ? ";

        if( $ignoreSuspended )
        {
            $SQL .= " AND `follower` IN (
                SELECT `id` FROM `{$userT}`
                WHERE `suspended` = 0
            ) ";
        }

        $SQL .= " ORDER BY `id` DESC ";

        $dbValues = [$userId];

        if ( is_numeric($skip) && is_numeric($limit) )
        {
            $SQL .= " LIMIT ?, ? ";
            $dbValues[] = (int) $skip;
            $dbValues[] = (int) $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function getFollowingIds( $userId )
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT * FROM `{$this->_table}` WHERE `follower` = ? ";

        $dbValues = [$userId];

        $result = $this->_db->query($SQL, $dbValues)->getAll();

        $output = array();
        foreach ( $result as $row )
        {
            $output[$row['follow']]  = $row['follow'];
        }

        return $output;

    }
}