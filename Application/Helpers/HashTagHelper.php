<?php

namespace Application\Helpers;

use GuzzleHttp\Psr7\Uri;
use System\Helpers\URL;

class HashTagHelper
{
    const REGEX = '#([^\s!@#$%^&*()=+.\/,\[{\]};:\'"?><]+)';

    public static function find( $string )
    {
        $match = array();
        if ( !preg_match_all('/' . self::REGEX . '/', $string, $match) ) return array();

        // else give hash tags
        if ( isset($match[0]) ) return $match[0];
    }

    public static function highlightHash( $string )
    {
        $url = URL::full('search?q=');
        return preg_replace('/(' . self::REGEX . ')/', '<a href="'. $url .'%23$2">$1</a>', $string);
    }
}