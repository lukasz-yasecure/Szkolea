<?php

$sysFile = 'engine/class.system.php';
$logFile = 'engine/class.log.php';
$logDir = 'logs/';

if (file_exists($sysFile) && file_exists($logFile)) {
    require_once($sysFile);
    require_once($logFile);
} else {
    date_default_timezone_set('Europe/Warsaw');
    file_put_contents($logDir . date('Ymd') . '_system_exc.log', date('Y-m-d H:i:s') . ' SYSTEM/LOG NIEDOSTEPNY!' . PHP_EOL, FILE_APPEND);
    exit('Strona niedostepna! Prosze sprobowac pozniej oraz skontaktowac sie z administratorem: admin@szkolea.pl !');
}

/* * *********************[ action = XXX ]****************************************************************************
 *
 * 2011-1x-xx
 *
 * ********************************************************************************************************************* */

try {
    $sys = new System('cat', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    //$pm = new PrivilegesManager($sys);
    //$p = $pm->checkPrivileges($u);


    $dbc = new DBC($sys);
    $cm = new CategoryManager();
    



    $c = $cm->getCategoriesForCatalogs($dbc);

    
    if(isset($_GET['c'])) {
    $CatsSums = $cm->getCatsSums($dbc);
    $SubcatsSums = $cm->getSubcatsSums($dbc);
    $SubsubcatsSums = $cm->getSubsubcatsSums($dbc);


    while ($k = $c->getK()) {
        echo '<h1><a href="index.php?what=comms&id=' . $k[1] . '">' . $k[0] . '</a>' . '(';


        if (!empty($CatsSums[$k[1]])) {
            echo $CatsSums[$k[1]];
        } else {
            echo '0';
        }

        echo ')' . '</h1>';

        while (($o = $c->getO())) {
            if ((strpos($o[1], $k[1] . '_')) === 0) {
        echo '<h2><a href="index.php?what=comms&id=' . $o[1] . '">' . $o[0] . '</a>' . '(';

                if (!empty($SubcatsSums[$o[1]])) {
                    echo $SubcatsSums[$o[1]];
                } else {
                    echo '0';
                }
                echo ')' . '</h2>';

                echo '<p>';
                while (($t = $c->getT())) {
                    if ((strpos($t[1], $o[1] . '_')) === 0) {

        echo '<a href="index.php?what=comms&id=' . $t[1] . '">' . $t[0] . '</a>' . '(';

                        if (!empty($SubsubcatsSums[$t[1]])) {
                            echo $SubsubcatsSums[$t[1]];
                        } else {
                            echo '0';
                        }

                        echo ')' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }
                echo '</p>';
            }
            $c->resetNrT();
        }
        $c->resetNrO();
    }
    
}else {
    
    
    $ServsSums = $cm->getServsSums($dbc);
    $SubservsSums = $cm->getSubservsSums($dbc);
    $SubsubservsSums = $cm->getSubsubservsSums($dbc);


    while ($k = $c->getK()) {
        echo '<h1><a href="index.php?what=servs&id=' . $k[1] . '">' . $k[0] . '</a>' . '(';


        if (!empty($ServsSums[$k[1]])) {
            echo $ServsSums[$k[1]];
        } else {
            echo '0';
        }

        echo ')' . '</h1>';

        while (($o = $c->getO())) {
            if ((strpos($o[1], $k[1] . '_')) === 0) {
        echo '<h2><a href="index.php?what=servs&id=' . $o[1] . '">' . $o[0] . '</a>' . '(';

                if (!empty($SubservsSums[$o[1]])) {
                    echo $SubservsSums[$o[1]];
                } else {
                    echo '0';
                }
                echo ')' . '</h2>';

                echo '<p>';
                while (($t = $c->getT())) {
                    if ((strpos($t[1], $o[1] . '_')) === 0) {

        echo '<a href="index.php?what=servs&id=' . $t[1] . '">' . $t[0] . '</a>' . '(';

                        if (!empty($SubsubservsSums[$t[1]])) {
                            echo $SubsubservsSums[$t[1]];
                        } else {
                            echo '0';
                        }

                        echo ')' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }
                echo '</p>';
            }
            $c->resetNrT();
        }
        $c->resetNrO();
    }
    
    
}



} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>