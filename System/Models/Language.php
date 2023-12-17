<?php

namespace System\Models;

use System\Core\Application;
use System\Core\Config;
use System\Core\Model;
use System\Core\Request;

class Language extends Model
{

    private $_lang;

    private $_langs;

    public function __construct( $options = null )
    {
        parent::__construct($options);

        $this->_lang = isset($options['default_lang']) ? strtolower($options['default_lang']) : null;

        $this->_langs = Config::get("Lang")->getAll();
    }

    public function setDefault( $lang )
    {
        $this->_lang = strtolower($lang);
    }

    public function take( $key, $vars = null, $lang = null )
    {
        $lang = $lang ? $lang : $this->_lang;

        if (
            !isset($this->_langs[$lang]) ||
            !isset($this->_langs[$lang][$key])
        ) return $lang . "." . $key;

        //else return the lang                
        $str = $this->_langs[$lang][$key];

        if ( is_array($vars) )
        {
            $replace = [];
            foreach ( $vars as $k => $v )
            {
                $replace['(:' . $k . ')'] = $v;
            }

            $str = strtr($str, $replace);
        }

        return $str;
    }

    public function current()
    {
        return $this->_lang;
    }

    public function __invoke( $keys, $vars = null, $lang = null )
    {
        return $this->take($keys, $vars, $lang);
    }

}