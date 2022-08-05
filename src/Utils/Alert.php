<?php

namespace NeeZiaa\Utils;

use NeeZiaa\Utils\Exception;

class Alert {

    /**
     * @param string $type
     * @param string $content
     * @throws \NeeZiaa\Utils\Exception
     */

     public static function alert(string $type, string $content): void
     {

         if(!preg_match('danger|warning|success|info', $type)) throw new \NeeZiaa\Utils\Exception("Incorrect type");

         $alert = array(
            'type' => $type,
             'content' => $content
         );

         setcookie('alert', json_encode($content));
    }

    /**
     * @throws \NeeZiaa\Utils\Exception
     */
    public static function show(): string
    {

        if(isset($_COOKIE['alert']) && $_COOKIE['alert'] != NULL) {
            throw new \NeeZiaa\Utils\Exception("Can't show empty alert");
        } else {
            $alert = json_decode($_COOKIE['alert'], true);

            return '
                <div class="alert-'. $alert['type'] .'">'. $alert['content'] .'</div>
            ';
        }

    }

}