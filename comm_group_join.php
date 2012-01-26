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

/***********************[ action = comm_group_join ]***********************************************************************
 *
 * 2011-09-26
 *
 **********************************************]***********************************************************************/

if(isset($_GET['id']))
{
    try
    {
        $sys = new System('comm_group_join', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        // SM, UM, PM
        // czy ID comm w SM = ID comm z _GET ??
        $sm = new SessionManager();
        $sm->storeQueryString();
        $cm = new CommisionManager();
        $c = $cm->getCommisionFromSession($sm);

        $tm = new TemplateManager(); // nowy manager szablonow
        $cgjft = $tm->getCommGroupJoinFormTemplate($sys, $c->getParts_count());
        $mt = $tm->getMainTemplate($sys, $cgjft->getContent(), BFEC::showAll(), JSManager::getScriptsForCommGroupJoin($c->getParts_count()));

        echo $mt->getContent();
        // na stronie zlecenia musi byc zachowanie usera w sesji - tego prawie nigdzie nie zrobilem LOL takze nie trzyma logowania
        //

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
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
}
else if(isset($_POST))
{
    /*
     * no wiec nalezy sprawdzac z baza
     * $c musi zostac zapdejtowane przed sama walidacja
     * zeby sprawdzic czy $c jest jeszcze otwarte, czy nie ma max czlonkow
     * w ogole pasowaloby zmienic sposob zapisu w bazie zeby jakos odroznic co user zapisal w bazie (siebie czy innych userow)
     * cza sprawdzac czy user zapisujacy to nie user ktory dodal zlecenie
     * no i trzeba sprawdzic czy user w ogole ma prawo dopisywac (klient) trzeba pobrac jego dane bo do zapisu wymagane jest imie i nazwisko
     */
    try
    {
        $sys = new System('comm_group_join_check', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $cm = new CommisionManager();
        $c = $cm->getCommisionFromSession($sm);

        $ud = new UserData();
        $ud->getCommGroupJoinFormData($c);

        BFEC::addm('grupa zostala dopisana!', 'comm.php?'.$sm->getQueryString());
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
    catch(NoRulesAccept $e)
    {
        BFEC::add('musisz zaakceptować regulamin', true, 'comm_group_join.php?'.$sm->getQueryString());
    }
    catch(SomeErrors $e)
    {
        BFEC::add('grupa nie zostala dopisana z powodu bledow w formularzu', true, 'comm_group_join.php?'.$sm->getQueryString());
    }
    catch(NoMembersToJoin $e)
    {
        BFEC::add('musisz podac przynajmniej jednego uczestnika', true, 'comm_group_join.php?'.$sm->getQueryString());
    }
    catch(TooManyMembers $e)
    {
        BFEC::add('podales za duzo uczestnikow (maksymalnie 16 osob w grupie)', true, 'comm_group_join.php?'.$sm->getQueryString());
    }
}

?>