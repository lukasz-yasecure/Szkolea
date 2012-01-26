<?php

$sysFile = 'engine/class.system.php';
$logFile = 'engine/class.log.php';
$logDir = 'logs/';

if(file_exists($sysFile) && file_exists($logFile))
{
    require_once($sysFile);
    require_once($logFile);
}
else
{
    date_default_timezone_set('Europe/Warsaw');
    file_put_contents($logDir.date('Ymd').'_system_exc.log', date('Y-m-d H:i:s').' SYSTEM/LOG NIEDOSTEPNY!'.PHP_EOL, FILE_APPEND);
    exit('Strona niedostepna! Prosze sprobowac pozniej oraz skontaktowac sie z administratorem: admin@szkolea.pl !');
}

/////

require_once('config_old.php');

if(isset($_GET['id']))
{
    if(isset($_GET['anuluj']))
    {
        if($_GET['z'] <= 1 && $_GET['o'] == 0)
        {
            $sql = 'UPDATE `szkolea`.`commisions` SET `date_end` = \'1\' WHERE `commisions`.`id_comm` =\''.$_GET['id'].'\'';
            $db = Valid::getDBObject();
            $db->query($sql);
            echo 'zlecenie zostalo anulowane!';
        }
        else
        {
            echo 'zlecenie moze byc anulowane tylko jesli nikt sie nie zapisal i nie ma zadnych ofert!';
        }
    }
    else if(isset($_GET['oferty']))
    {
        echo 'oferty:<br/>';
        $sql = 'SELECT * FROM `commisions_ofe` WHERE `id_comm`=\''.$_GET['id'].'\'';
        $db = Valid::getDBObject();
        $res = $db->query($sql);
        echo '<table width="800px"><tr><td>cena</td><td> &nbsp; </td><td>sposób rozliczania</td><td>wynajem sali</td><td>materiały</td><td>lunch</td><td>przerwy kawowe</td><td>termin</td></tr>';
        while($t = $res->fetch_assoc())
        {
            echo "<tr><td>".$t['cena']."</td><td> Bez Vat </td><td> Rachunek </td><td> tak </td><td> nie </td><td> tak </td><td>2 </td><td> - </td></tr>";
        }
        echo '</table>';
    }
}

?>
