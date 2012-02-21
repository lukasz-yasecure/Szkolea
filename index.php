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

/***********************[ action = index_view ]***********************************************************************
 *
 * 2011-09-28 left menu works
 *
 **********************************************]***********************************************************************/

try
{
    $sys = new System('index_view', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $dbc = new DBC($sys);
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $tm = new TemplateManager();
    $cm = new CategoryManager();
    $ud = new UserData();
    $c = $cm->getCategories($dbc, $ud->getIdForLeftMenu()); // kategorie dla lewego menu
    $lmlt = $tm->getLeftMenuListTemplate($sys, $c, $ud->getWhatForLeftMenu()); // lista w lewym menu
    $lmt = $tm->getLeftMenuTemplate($sys, $cm, $lmlt); // lewe menu

    $s = $ud->getSearch(); // pobieramy parametry szukania jesli jakies sa
    $rm = new ResultsManager(); // tworzymi liste wynikow do wyswietlenia
    $r = $rm->getResults($dbc, $s); // tutaj tak naprawde dopiero tworzymy liste wynikow na bazie wyszukiwania/wyboru z lewego menu
    $rlt = $tm->getResultsListTemplate($sys, $r); // szablon listy z wynikami
    $rt = $tm->getResultsTemplate($sys, $rlt); // szablon wynikow

    $skrypty = file_get_contents('temp/index.html');
    
    if(!$u->isLogged())
        $bar = $tm->getLoginbarTemplate($sys);
    else
        $bar = $tm->getUserbarTemplate($sys, $u->getEmail());
    $c = $cm->getListOfAllKOTM($dbc, 'searchForm'); // wczytujemy wszystkie kategorie na potrzeby selectow w searchform
    $st = $tm->getSearchTemplate($sys, $c, $bar->getContent()); // szablon wyszukiwarki
    //$it = $tm->getIndexTemplate($sys, $st->getContent(), $lmt->getContent(), '');
    $it = $tm->getIndexTemplate($sys, $st->getContent(), $lmt->getContent(), $rt->getContent()); // szablon strony glownej
    $mt = $tm->getMainTemplate($sys, $it->getContent(), BFEC::showAll(), $skrypty);
    RFD::clear('searchForm');
    echo $mt->getContent();
}
catch(BasicModuleDoesNotExist $e) // System
{
    Log::System($e);
    $sys->getFatalError();
}
catch(ModuleDoesNotExist $e) // System
{
    Log::System($e);
    $sys->getFatalError();
}
catch(NoDefinitionForAction $e) // System
{
    Log::System($e);
    $sys->getFatalError();
}
catch(NoTemplateFile $e) // TM
{
    Log::NoTemplateFile($e);
    $sys->getFatalError();
}
catch(DBConnectException $e) // DBC
{
    Log::DBConnect($e);
    $sys->getFatalError();
}
catch(DBCharsetException $e) // DBC
{
    Log::DBCharset($e);
    $sys->getFatalError();
}
catch(DBQueryException $e) // CategoryManager
{
    Log::DBQuery($e);
    $sys->getFatalError();
}
catch(EmptyList $e) // CategoryManager
{
    exit('empty list');
}

?>