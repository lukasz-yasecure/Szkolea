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

/***********************[ action = comm ]*********************************************************************************
 *
 * 2011-11-09   dziala, wszystko wyswietla OK
 *
 ***********************[ action = comm_join ]****************************************************************************
 ***********************[ action = comm_offer ]***************************************************************************
 *************************************************************************************************************************/

if(isset($_GET['join']))
{
    try
    {
        $sys = new System('comm_join', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $pm->checkPrivileges($u);
        $ud = new UserData();
        $d = $ud->getCommJoinAndOfferData();

        $c = $sm->getCommision();

        if($c->getParts_count() < 16)
        {
            $dbc = new DBC($sys);
            $sql1 = 'INSERT INTO `commisions_group` (`id_comm`, `id_user`, `date_add`) VALUES (\''.$d.'\', \''.$u->getId_user().'\', \''.time().'\')';
            $dbc->query($sql1);
            $sql2 = 'UPDATE `szkolea`.`commisions` SET `parts_count` = \''.($c->getParts_count()+1).'\' WHERE `commisions`.`id_comm` =\''.$d.'\'';
            $dbc->query($sql2);

            BFEC::addm('Zostałeś dopisany do zlecenia!', SessionManager::getBackURL_Static());
        }
        else BFEC::add('Przepraszamy, ale szkolenie posiada maksymalną liczbę uczestników!', true, SessionManager::getBackURL_Static());
    }
    catch(Exception $e) // System
    {
        $em = new EXCManager($e, 'comm');
    }
}
else if(isset($_GET['offer']))
{
    try
    {
        $sys = new System('comm_offer', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $pm->checkPrivileges($u);

        $c = $sm->getCommision();
        $content = file_get_contents('view/html/comm_offer.html');
        $s = array('{%id%}', '{%cena%}', '{%cenax1%}', '{%cenax2%}', '{%cenax3%}', '{%rozl1%}', '{%rozl2%}', '{%rozl3%}', '{%inne1%}', '{%inne2%}', '{%inne3%}', '{%inne4%}', '{%ile_kaw%}', '{%date_a%}', '{%date_b%}');
        $r = array_fill(0, count($s), null);
        $r[0] = $c->getId_comm();
        $r[1] = RFD::get('CO', 'cena');
        if(!is_null(RFD::get('CO', 'cenax'))) $r[RFD::get('CO', 'cenax')+1] = 'checked=\'checked\'';
        if(!is_null(RFD::get('CO', 'rozl'))) $r[RFD::get('CO', 'rozl')+4] = 'selected=\'selected\'';
        if(!is_null(RFD::get('CO', 'inne')))
        {
            $i = explode(';', RFD::get('CO', 'inne'));
            foreach($i as $p)
            {
                $r[$p+7] = 'checked=\'checked\'';
            }
        }
        $r[12] = RFD::get('CO', 'ile_kaw');
        $r[13] = RFD::get('CO', 'date_a');
        $r[14] = RFD::get('CO', 'date_b');
        $content = str_replace($s, $r, $content);

        $tm = new TemplateManager();
        $mt = $tm->getMainTemplate($sys, $content, BFEC::showAll(), file_get_contents('temp/comm_offer.html'));
        echo $mt->getContent();
        RFD::clear('CO');
    }
    catch(Exception $e) // System
    {
        $em = new EXCManager($e, 'comm');
    }
}
else if(isset($_GET['offer_check']))
{
    try
    {
        $sys = new System('comm_offer_check', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $pm->checkPrivileges($u);

        $ud = new UserData();
        $o = $ud->getCommOffer();
        $o->setId_user($u->getId_user());
        $o->setDate_add(time());

        $sql = Query::getOfferAdd($o);
        $dbc = new DBC($sys);
        $res = $dbc->query($sql);
        if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        $sql = Query::getOfferCountForCommision($o->getId_comm());
        $res = $dbc->query($sql);
        if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        $res = $res->fetch_assoc();
        if($res['ile'] == 1)
        {
            $sql = 'UPDATE `commisions` SET `date_end` = \''.(time()+3600*48).'\' WHERE `commisions`.`id_comm` ='.$o->getId_comm();
            $res = $dbc->query($sql);
            if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        }

        /* 
         * WYSYŁANIE MAILI
         */
        $m = new Mailer();
        $c = $sm->getCommision();

        // założyciel zlecenia
        $cu = $um->getUser($dbc,$c->getId_user()); // pobieranie danych założyciela zlecenia ([c]ommition [u]ser)
        $m->sendMail($cu->getEmail(),'noreply@szkolea.pl','Nowa oferta','Treść: otrzymałeś ofertę');
        // $m->sendMail('d.volosevic@gmail.com', 'noreply@szkolea.pl', 'Nowa oferta','Treść: otrzymałeś ofertę'); do testów

        // obserwujący zlecenie
        $lo = $dbc->query(Query::getObserveCommUsers($c->getId_comm())); // pobieranie listy obserwujących zlecenie
            while ($x = $lo->fetch_assoc()) {    
                $ou = $um->getUser($dbc,$x['id_user']); // pobieranie danych obserwujących zlecenie ([o]bserve [u]ser)
                $m->sendMail($ou->getEmail(),'noreply@szkolea.pl','Uwaga! Nowa oferta','Treść wiadomości');
                // $m->sendMail('d.volosevic@gmail.com', 'noreply@szkolea.pl', 'Nowa oferta',$ou->getEmail()); do testów
            }
        BFEC::addm('Właśnie złożyłeś ofertę!', Pathes::getScriptCommisionPath($o->getId_comm()));
    }
    catch(Exception $e) // System
    {
        $em = new EXCManager($e, 'comm');
    }
}
else if(isset($_GET['id']))
{
    try
    {
        $sys = new System('comm', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $pm->checkPrivileges($u);
        $ud = new UserData();
        $cm = new CommisionManager();
        $dbc = new DBC($sys);
        $c = $cm->getCommision($dbc, $ud->getIDFromURL());
        $om = new OfferManager();
        $c->setOferty($om->getOfferCountForCommision($dbc, $c));
        $tm = new TemplateManager();
        $ct = $tm->getCommTemplate($sys, $c);
        $mt = $tm->getMainTemplate($sys, $ct->getContent(), BFEC::showAll(), file_get_contents('temp/comm.html'));
        $sm->storeCommision($c);
        echo $mt->getContent();
    }
    catch(Exception $e) // System
    {
        $em = new EXCManager($e);
    }
}
else
{
    try
    {
        $sys = new System('comm', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        BFEC::add('', true, Pathes::getScriptIndexPath());
    }
    catch(Exception $e) // System
    {
        $em = new EXCManager($e);
    }
}

exit();

 /**********************************************]***********************************************************************/

if(isset($_POST['trener_1'])) // TRENER OFERTA
{
    // trzeba sprawdzic czy USER jest dostawca itp - klasa ServControl -> form oferty
    // wyslanie oferty = walidacja pol -> DB

    $sc = new ServControl();
    //$sc->checkPrivileges('comm.php?id='.$_POST['id']);

    if($_POST['trener_1'] == 'oferta_form')
    {
        $cv = new CommView();
        echo $cv->getAddOfferForm($_POST['id']);
    }
    else if($_POST['trener_1'] == 'oferta')
    {
        pre($_POST);
        if($sc->getAllOfferFromSource($_POST))
        {
            $sc->setUID($ss->getUID());
            $s = new Services();
            $s->addOfferForCommToDB($sc);
            $ss->setMessage('offer');
            header('Location: comm.php?id='.$_POST['id']);
            exit();
        }
        else
        {
            $ss->setMessage('serv');
            header('Location: comm.php?id='.$_POST['id']);
            exit();
        }
    }

    //$o = new Observ();
    //$o->notifyObserversAboutNewOFE($_POST['id']);
    //$ss->setMessage('zle_ofe');
    //header('Location: comm.php?id='.$_POST['id']);
    //exit();
}
    

?>
