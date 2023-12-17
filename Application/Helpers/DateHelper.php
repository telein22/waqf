<?php

namespace Application\Helpers;

class DateHelper
{
    public static function butify( $timestamp )
    {
        if ( !is_numeric($timestamp) ) $timestamp = strtotime($timestamp);

        return date('M jS, Y, g:i a', $timestamp);
    }

    public static function timeToSec( $time )
    {
        // 12:24:59 PM
        // 17:30:45
        // 30:45
    }

    public static function remains( $timestamp, $showSecond = false)
    {
        $remains = $timestamp - time();
        $h = $remains / 3600;
        $m = ( $remains % 3600 ) / 60;
        $s = ( $remains % 3600 ) % 60;

        $arr = [floor($h), floor($m)];

        if( $showSecond )
        {
            $arr[] = floor($s);
        }

        return $arr;
    }
}