<?php

namespace System\Models;

use System\Core\Exceptions\SystemError;
use System\Core\Model;
use System\Core\Request;

class Session extends Model
{

    private static $_protectedKeys = array('home_url', 'user_agent');

    protected $_name = "session";

    protected function __construct( $options )
    {
        parent::__construct();

        if (session_id() == '' )
        {
            ini_set('session.use_trans_sid', '0');
            ini_set('session.use_cookies', '1');
            ini_set('session.use_only_cookies', '1');
        //    ini_set('session.gc_probability', 1);
        //    ini_set('session.gc_divisor', 1000);
        //    ini_set('session.gc_maxlifetime', 1800);
        }

        // start the session
        $this->_configure($options);
        $this->_start();
    }

    private function _configure( $options )
    {
        if ( isset($options['name']) ) $this->_name = $options['name'];
    }

    private function _start()
    {

        session_name($this->_name);
        
        $cookie = session_get_cookie_params();        
        $cookie['httponly'] = true;
        session_set_cookie_params($cookie['lifetime'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
        
        session_start();

        /** @var Request */
        $request = Request::instance();
        $homeUrl = $request->getFullHost();
        //session_regenerate_id(true);
        
        if ( !isset($_SESSION['home_url']) )
        {
            $_SESSION['home_url'] = $homeUrl;
            
        }else if ( $_SESSION['home_url'] !== $homeUrl ){
            $this->_regenerate();
        }
        
        $userAgent = $request->getUserAgent();
        
        if ( !isset($_SESSION['user_agent']) )
        {
            $_SESSION['user_agent'] = $userAgent;
            
        }else if ( $_SESSION['user_agent'] !== $userAgent ){
            $this->_regenerate();
            // The following line fixes the issue with
            // creating new sessions.
            $_SESSION['user_agent'] = $userAgent;
        }
    }

    private function _regenerate()
    {
        session_regenerate_id();
    }

    public function take( $key, $default = null )
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public function put( $key, $value )
    {   
        if ( in_array($key, self::$_protectedKeys) )
            throw new SystemError("Trying to set a protected key");
        
        $_SESSION[$key] = $value;
        return true;
    }

    public function delete( $key )
    {
        if ( isset($_SESSION[$key]) ) unset($_SESSION[$key]);
        
        return true;
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }
}