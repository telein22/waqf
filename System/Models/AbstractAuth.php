<?php

namespace System\Models;

use System\Core\Exceptions\SystemError;
use System\Core\Exceptions\Redirect;
use System\Core\Model;

abstract class AbstractAuth extends Model
{
    const SUCCESS = 1;
    const INVALID_USERNAME = 2;
    const INVALID_PASSWORD = 3;
    const USER_SUSPENDED = 4;
    const USER_DEACTIVATED = 5;

    protected $_table = "users";

    protected $_autoCookieName = "auto_login";

    private $_cookie;

    /**
     * @var \System\Models\Session
     */
    private $_session;

    private $_user;

    protected function __construct( $options = null )
    {
        parent::__construct( $options );

        // TODO: make api authentication.
        // /**
        //  * 
        //  * @var \System\Core\Request;
        //  */
        // $request = Request::instance();
        // if ( $request->isAjax() ) return;

        /**
         * Only web version of code will run with cookie and 
         * session.
         */
        $this->_session = Model::get("\System\Models\Session");

        if ( isset($options['auto_login']) && $options['auto_login'] == true )
        {
            /** @var \System\Models\Cookie */
            $this->_cookie = Model::get("\System\Models\Cookie");
        }
    }

    abstract protected function usernameColumn();

    abstract protected function passwordColumn();

    abstract protected function uidColumn();

    abstract protected function verifyPassword( $password, $encPassword );

    public function login( array $arr )
    {
        $username = $arr['username'];
        $password = $arr['password'];
        $remember = isset($arr['remember']) ? $arr['remember'] : false;

        // Try to login
        $ucolumn = $this->usernameColumn();
        $pcolumn = $this->passwordColumn();

        $result = $this->find(array($ucolumn => $username));

        if ( !$result ) return self::INVALID_USERNAME;
        if ( !$this->verifyPassword($password, $result[$pcolumn]) ) return self::INVALID_PASSWORD;

        if (isset($result['suspended']) && $result['suspended'] == 1) {
            return self::USER_SUSPENDED;
        }

        if (isset($result['is_deactivated']) && $result['is_deactivated'] == 1) {
            return self::USER_DEACTIVATED;
        }

        if ( $remember )
        {
            // TODO:: Work with remember feature
            $uidColumn = $this->uidColumn();
        }

        // set the session
        $this->_session->put('user', $result[$this->uidColumn()]);
        $this->_session->put('userInfo', $result);

        return self::SUCCESS;
    }

    public function logout()
    {
        if ( $this->isLoggedIn() )
        {
            $this->_session->delete('user');         
            $this->_session->delete('userInfo');
        }

        return true;
    }

    public function isLoggedIn()
    {
        return $this->_session->has('user');
    }

    public function getInfo()
    {
        if ( !$this->isLoggedIn() ) {
            throw new Redirect("login");
        }

        if ( !$this->_user )
        {
            $SQL = "SELECT * FROM `{$this->_table}` WHERE `{$this->uidColumn()}` = ?";
            $this->_user = $this->_db->query($SQL, [$this->_session->take('user')])->get();
        }

        return $this->_user;
    }

    public function find( array $with )
    {
        return $this->_prepareFind($with, function($result) {
            return $result->get();
        });
    }

    public function findAll( array $with )
    {
        return $this->_prepareFind($with, function($result) {
            return $result->getAll();
        });
    }

    public function _prepareFind( array $with, $closure )
    {
        $dbValues = [];
        $where = [];
        foreach ( $with as $key => $value )
        {
            $where[] = " `{$key}` = ? ";
            $dbValues[] = $value;
        }

        $SQL = "SELECT * FROM
                `{$this->_table}`
                WHERE " . implode(" AND ", $where);

        $result = $this->_db->query($SQL, $dbValues);
        return $closure($result);
    }
}