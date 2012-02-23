<?php

/**
 * solniczka
 *
 *  2011-09-21  getRemindSalt() +
 */
class Salts
{
    public static function getFrontSalt()
    {
        return '&Al7';
    }

    public static function getBackSalt()
    {
        return '$4Lt';
    }

    public static function getRemindSalt()
    {
        return 'P0|_$k4';
    }

    public static function getPasswordSalt()
    {
        return '$2K0|_34d21\/\/k0';
    }
}

?>
