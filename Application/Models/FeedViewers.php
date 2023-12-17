<?php

namespace Application\Models;

use System\Core\Model;

class FeedViewers extends Model
{  
    private $_table = 'feed_viewers';

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }

    public function count( $feedId )
    {
        $SQL = "SELECT COUNT(*) as `count` FROM `{$this->_table}` 
                WHERE `feed_id` = ? GROUP BY `feed_id`";
        return $this->_db->query($SQL, [$feedId])->get();
    }

    public function getTotalViews()
    {
        $SQL = "SELECT count(*) as viewsCount FROM `{$this->_table}`";
        $res = $this->_db->query($SQL)->get();

        return $res['viewsCount'];
    }
}