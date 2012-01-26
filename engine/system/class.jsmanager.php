<?php

/**
 * generowanie skryptow JS i sciezek do dodania w HEAD dla kazdej podstrony
 *
 *  2011-10-03  dla CommGroupJoin
 */
class JSManager
{
    static function getScriptsForCommGroupJoin($group)
    {
        $temp1 = '<script type="text/javascript" src="js/onloader.js"></script>';
        $temp2 = '<script type="text/javascript">{%code%}</script>';

        $code = file_get_contents('js/comm_group_join.js');
        $code = str_replace('{%grupa%}', $group, $code);

        $temp1.= str_replace('{%code%}', $code, $temp2);
        return $temp1;
    }
}

?>
