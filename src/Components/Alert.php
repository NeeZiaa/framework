<?php

namespace NeeZiaa\Utils;

class Alert {

    /**
     * @param string $type
     * @param string $content
     * @throws \Exception
     * danger|warning|success|info
     */

     public static function alert(string $type, string $content): void
     {

         if(!preg_match('/danger|warning|success|info/', $type)) throw new \Exception("Incorrect type");

         $alert = array(
             'type' => $type,
             'content' => $content
         );

//         setcookie('alert', json_encode($alert), time()+10);
         $_SESSION['alert'] = json_encode($alert);

    }

    public static function show(): string
    {

        if(!isset($_SESSION['alert']) OR $_SESSION['alert'] == NULL OR $_SESSION['alert'] == "") {
            return "";
        } else {
            $alert = json_decode($_SESSION['alert'], true);
            unset($_SESSION['alert']);
//            setcookie("alert", "", time()-3600);
            return '
                <div class="alert alert-'. $alert['type'] .'">'. $alert['content'] .'</div>
            ';
        }

    }

}