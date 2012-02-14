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



//zaczytywanie danych
    $c = $cm->getCategoriesForCatalogs($dbc);


    //wczytywanie szablonów
    $tempK = file_get_contents('view/html/cat.html');
    $tempO = file_get_contents('view/html/cat_oit.html');
    $tempT = file_get_contents('view/html/cat_t.html');
    $content = '';


// wyświetlanie listy K,O,T dla zleceń z liczebnością w postaci: Kategoria(ilość), Obszar(ilość), Tematyka(ilość)
// dla cat.php?c zlecenia, w przeciwnym przypadku usługi    
    if (isset($_GET['c'])) {
        $what = 'comms';


        //zliczamy wszystko w 3 krokach
        $CatsSums = $cm->getCatsSums($dbc);
        $SubcatsSums = $cm->getSubcatsSums($dbc);
        $SubsubcatsSums = $cm->getSubsubcatsSums($dbc);

        $t1 = '';
//wyświetlamy wszystko za pośrednictwem szablonów
        while ($k = $c->getK()) {

            $t1 .= str_replace(array('{%what%}', '{%id%}', '{%kategoria%}', '{%ile%}'), array($what, $k[1], $k[0], empty($CatsSums[$k[1]]) ? 0 : $CatsSums[$k[1]]), $tempK);

            $t2 = '';
//przydzielanie obszarów do kategorii
            while (($o = $c->getO())) {
                if ((strpos($o[1], $k[1] . '_')) === 0) {

                    $t2 .= str_replace(array('{%what%}', '{%id%}', '{%obszar%}', '{%ile%}'), array($what, $o[1], $o[0], empty($SubcatsSums[$o[1]]) ? 0 : $SubcatsSums[$o[1]]), $tempO);


                    $t3 = '';
//przydzielanie tematyk do obszarów
                    while (($t = $c->getT())) {
                        if ((strpos($t[1], $o[1] . '_')) === 0) {


                            $t3 .= str_replace(array('{%what%}', '{%id%}', '{%tematyka%}', '{%ile%}'), array($what, $t[1], $t[0], empty($SubsubcatsSums[$t[1]]) ? 0 : $SubsubcatsSums[$t[1]]), $tempT);
                        }
                    }
                    $t2 = str_replace('{%cat_t.html%}', $t3, $t2);
                }
                $c->resetNrT(); //reset wartości
            }
            $t1 = str_replace('{%cat_oit.html%}', $t2, $t1);



            $c->resetNrO(); //reset wartości
        }
        $t1 = str_replace('{%cat.html%}', $t2, $t1);

        $content = $t1;
        // wyświetlanie listy K,O,T dla zleceń z liczebnością w postaci: Kategoria(ilość), Obszar(ilość), Tematyka(ilość)
// domyślnie dla usług
    } else {
        $what = 'servs';

        
        //zliczamy wszystko w 3 krokach
        $ServsSums = $cm->getServsSums($dbc);
        $SubservsSums = $cm->getSubservsSums($dbc);
        $SubsubservsSums = $cm->getSubsubservsSums($dbc);

        $t1 = '';
//wyświetlamy wszystko za pośrednictwem szablonów
        while ($k = $c->getK()) {

            $t1 .= str_replace(array('{%what%}', '{%id%}', '{%kategoria%}', '{%ile%}'), array($what, $k[1], $k[0], empty($ServsSums[$k[1]]) ? 0 : $ServsSums[$k[1]]), $tempK);

            $t2 = '';
//przydzielanie obszarów do kategorii
            while (($o = $c->getO())) {
                if ((strpos($o[1], $k[1] . '_')) === 0) {

                    $t2 .= str_replace(array('{%what%}', '{%id%}', '{%obszar%}', '{%ile%}'), array($what, $o[1], $o[0], empty($SubservsSums[$o[1]]) ? 0 : $SubservsSums[$o[1]]), $tempO);


                    $t3 = '';
//przydzielanie tematyk do obszarów
                    while (($t = $c->getT())) {
                        if ((strpos($t[1], $o[1] . '_')) === 0) {


                            $t3 .= str_replace(array('{%what%}', '{%id%}', '{%tematyka%}', '{%ile%}'), array($what, $t[1], $t[0], empty($SubsubservsSums[$t[1]]) ? 0 : $SubsubservsSums[$t[1]]), $tempT);
                        }
                    }
                    $t2 = str_replace('{%cat_t.html%}', $t3, $t2);
                }
                $c->resetNrT(); //reset wartości
            }
            $t1 = str_replace('{%cat_oit.html%}', $t2, $t1);



            $c->resetNrO(); //reset wartości
        }
        $t1 = str_replace('{%cat.html%}', $t2, $t1);

        $content = $t1;
    }



    $tm = new TemplateManager();
    $skrypty = '';
    $mt = $tm->getMainTemplate($sys, $content, BFEC::showAll(), $skrypty);
    echo $mt->getContent();
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>