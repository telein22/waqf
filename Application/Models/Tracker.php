<?php

namespace Application\Models;

use System\Core\Model;
use System\Core\Request;
use System\Core\Router;
use System\Helpers\URL;

class Tracker extends Model
{

    private $_table = 'tracker';

    public function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function __construct($options = null)
    {
        parent::__construct($options);

        $userId = '0';
        /**
         * @var \System\Core\Request
         */
        $request = Request::instance();
        $currentUri = $request->getUri();
        
        if( !strpos($currentUri, 'admin') && !strpos($currentUri, 'ajax') && !strpos($currentUri, 'logout') )
        {
            $userAgent = $request->getUserAgent();
            $uParser = new \WhichBrowser\Parser($userAgent);
            $device = $uParser->browser->toString() . ' ( ' . $uParser->os->toString() . ' )';

            $userM = Model::get('\Application\Models\User');

            if ($userM->isLoggedIn()) {
                $userInfo = $userM->getInfo();
                $userId = $userInfo['id'];
            }

            $ip = $this->get_client_ip();
            
            $result = $this->create(array(
                'user_id' => $userId,
                'uri' => $currentUri,
                'time' => time(),
                'device' => $device,
                'ip' => $ip
            ));


            return $result;
        }

        return false;
    }

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }


    public function all( $from = null, $to = null )
    {
        $dbValues = array();

        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();

        $SQL = "SELECT COUNT(*) as `count`, MAX(`time`) AS `time`, `user_id`, `uri`, `ip`, `device`, `u`.* FROM `{$this->_table}` as `t`
                INNER JOIN `$userT` as `u` ON (`t`.`user_id` = `u`.`id`)  ";

        if (!empty($from) && !empty($to)) {

            $SQL .= 'WHERE `t`.`time` > ? AND `t`.`time` < ? ';
            $dbValues[] = (int) $from;
            $dbValues[] = (int) $to;
        }

        $SQL .= ' GROUP BY `t`.`user_id`,`t`.`uri`,`t`.`ip`,`t`.`device`';

        $SQL .= ' ORDER BY `time` DESC';

        $result = $this->_db->query($SQL, $dbValues)->getAll();

        return $result;
    }
}
