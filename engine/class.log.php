<?php

/**
 * wszelkie logi
 * errory
 *
 *  2011-09-21  ostatni wglad
 */
class Log
{
    public static function putErrorLog($name, Exception $e)
    {
        file_put_contents('logs/'.date('Ymd').'_'.$name.'_exc.log', date('Y-m-d H:i:s').' '.$e->getFile().':'.$e->getLine().' '.$e->getMessage().PHP_EOL, FILE_APPEND);
    }

    public static function SqlQuery($sql)
    {
        file_put_contents('logs/'.date('Ymd').'_sql_query.log', date('Y-m-d H:i:s').' '.$sql.PHP_EOL, FILE_APPEND);
    }

    public static function DBConnect(Exception $e)
    {
        Log::putErrorLog('dbconnect', $e);
    }

    public static function DBCharset(Exception $e)
    {
        Log::putErrorLog('dbcharset', $e);
    }

    public static function DBQuery(Exception $e)
    {
        Log::putErrorLog('dbquery', $e);
    }

    public static function NoUser(Exception $e)
    {
        Log::putErrorLog('usermanager', $e);
    }

    public static function TooManyUsers(Exception $e)
    {
        Log::putErrorLog('usermanager', $e);
    }

    public static function NoTemplateFile(Exception $e)
    {
        Log::putErrorLog('templatemanager', $e);
    }

    public static function NoPreparedActivationKeys(Exception $e)
    {
        Log::putErrorLog('activationmanager', $e);
    }

    public static function MailDidNotSend(Exception $e)
    {
        Log::putErrorLog('mailer', $e);
    }

    public static function System(Exception $e)
    {
        Log::putErrorLog('system', $e);
    }

    public static function HackingAttempt(Exception $e)
    {
        Log::putErrorLog('hack', $e);
    }
}

?>
