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

/* * *********************[ action = cat ]****************************************************************************
 *
 * 2012-02-17 dzialaja katalogi zlecen i uslug
 *
 * ********************************************************************************************************************* */

try {
    $sys = new System('cat', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $tm = new TemplateManager();
    
    $content = '';
    // wyświetlanie listy K,O,T dla zleceń z liczebnością w postaci: Kategoria(ilość), Obszar(ilość), Tematyka(ilość)
    // dla cat.php?c zlecenia, w przeciwnym przypadku usługi    
    if (isset($_GET['c'])) {
        $content = $tm->getCatalog('comms', new DBC($sys), new CategoryManager(), new CommisionManager(), new ServiceManager());
        // wyświetlanie listy K,O,T dla zleceń z liczebnością w postaci: Kategoria(ilość), Obszar(ilość), Tematyka(ilość)
    // domyślnie dla usług
    } else {
        $content = $tm->getCatalog('servs', new DBC($sys), new CategoryManager(), new CommisionManager(), new ServiceManager());
    }

    $skrypty = '';
    $mt = $tm->getMainTemplate($sys, $content, BFEC::showAll(), $skrypty);
    echo $mt->getContent();
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>