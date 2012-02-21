<?php

/**
 * podstawowe funkcje do debugowania itp
 *
 *  2011-09-21  ostatni wglad
 */
class STD
{
    public static function pre($a)
    {
        if(is_array($a)) var_dump($a);
        else
        {
            echo '<pre>';
            print_r($a);
            echo '</pre>';
        }
    }

    public static function dpr($msg)
    {
        echo '<br/><b>UWAGA:</b> <i>'.$msg.'</i><br/>';
        var_dump($msg);
    }

    public static function sg1()
    {
        echo '<pre>';
        STD::pre($_SERVER);
        STD::pre($_ENV);
        STD::pre($_SESSION);
        echo '</pre>';
    }

    public static function sg2()
    {
        echo '<pre>';
        //STD::pre($_REQUEST);
        STD::pre($_GET);
        STD::pre($_POST);
        STD::pre($_COOKIE);
        STD::pre($_FILES);
        echo '</pre>';
    }

    public static function basicHttpAuth()
    {
        // jest jedna proba, pozniej trzeba czyscic dane logowana w FF
        // ctrl+shift+del -> aktywne zalogowania
        $login = 'szkolea';
        $pass = 'testy';

        if(!isset($_SERVER['PHP_AUTH_USER']))
        {
            header('WWW-Authenticate: Basic realm="gusto.pl"');
            header('HTTP/1.0 401 Unauthorized');
            exit();
        }
        else
        {
            if($_SERVER['PHP_AUTH_USER'] == $login && $_SERVER['PHP_AUTH_PW'] == $pass)
            {

            }
            else
            {
                header('HTTP/1.0 401 Unauthorized');
                exit();
            }
        }
    }
}

?>
