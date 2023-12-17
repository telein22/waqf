<?php

namespace System\Helpers;


class Strings
{
   public static function explode( $delimiter, $string )
   {
       $result = array();
       foreach ( explode($delimiter, $string) as $string )
       {
           if ( !empty($string) ) $result[] = $string;
       }
       return $result;
   }

   /**
    * Generate a random string
    *
    * @param int $length
    * @return string
    */
   public static function random( $length )
   {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
   }

   public static function limit( $string, $length = 10, $prepend = '..' )
   {
       if ( mb_strlen($string) < $length )  return $string;

       // else add dot.
       return mb_substr($string, 0, $length) . $prepend;
   }
}