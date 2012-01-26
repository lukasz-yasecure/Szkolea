<?php

class CommView
{
    private $vw = null;

    public function __construct()
    {
        $this->vw = new View();
    }

    public function getCommSite($t)
    {
        $mc = new MainControl();
        $content = file_get_contents('view/html/comm.html');

        foreach($t['terminy'] as $k => $v)
        {
            $t['terminy'][$k] = date('Y.m.d', $v);
        }

        $s = array(
            '{%id%}',
            '{%zapisanych%}',
            '{%ofert%}',
            '{%koniec%}',
            '{%kategoria%}',
            '{%obszar%}',
            '{%tematyka%}',
            '{%dlugosc%}',
            '{%dni%}',
            '{%terminy%}',
            '{%miejsce%}',
            '{%cena%}');
        $r = array(
            $t['id'],
            $t['zapisanych'],
            $t['ofert'],
            date('Y-m-d H:i', $t['koniec']),
            $mc->getNameForCat($t['id_modul']),
            $mc->getNameForSubcat($t['id_modul']),
            $mc->getNameForSubsubcat($t['id_modul']),
            $t['dlugosc'],
            $this->days2Names($t['dni']),
            $t['terminy']['a'].' - '.$t['terminy']['b'].(isset($t['terminy']['c']) ? '<br/>'.$t['terminy']['c'].' - '.$t['terminy']['d'] : ''),
            $t['miejsce']['place'].(isset($t['miejsce']['woj']) ? ' ('.$t['miejsce']['woj'].')' : ''),
            $t['cena']['min'].' - '.$t['cena']['max'].' zł'
        );
        $content = str_replace($s, $r, $content);

        if(preg_match('#\{(.*)%modul%(.*)\}#s', $content, $m))
        {
            $n = '';

            foreach($t['modul'] as $md)
            {
                $n.= $m[1].$md.$m[2];
            }

            $content = str_replace($m[0], $n, $content);
        }

        return $content;
    }

    public function days2Names($dni)
    {
        $n = array('obojętnie', 'poniedziałek', 'wtorek', 'środa', 'czwartek', 'piątek', 'sobota', 'niedziela');
        $t = explode(';', $dni);
        if($dni != '')
        {
            $ret = $n[$t[0]];
            $c = count($t);


            for($i=1; $i<$c; $i++)
            {
                $ret.= '<br/>'.$n[$t[$i]];
            }
        }
        else
        {
            $ret = 'obojętnie';
        }

        return $ret;
    }

    public function clearWS($s)
    {
        $ws = array("\n", "\r", "\t", " ");
        return str_replace($ws, '', $s);
    }

    public function getAddForm()
    {
        /*
         * probuje dodac uzupelnianie pol wartosciami ...
         * na razie jest tylko wybot kategorii
         */
        $cat = null;
        
        if(isset($_SESSION['comm']['cat'])) $cat = $_SESSION['comm']['cat'];
        
        $content = file_get_contents('view/html/comm_add.html');
        $s = array(
            '{%cats%}',
            /*'{%obszary%}',
            '{%tematyki%}',
            '{%moduly%}',
            '{%long1%}',
            '{%long2%}',
            '{%long3%}',
            '{%long4%}',
            '{%days0%}',
            '{%days1%}',
            '{%days2%}',
            '{%days3%}',
            '{%days4%}',
            '{%days5%}',
            '{%days6%}',
            '{%days7%}',*/
            '{%wojs%}'
        );
        $r = array(
            $this->vw->getOptionsWithCategories($cat),
            $this->vw->getOptionsWithWojewodztwa()
        );
        $content = str_replace($s, $r, $content);
        return $content;
    }

    public function getAddOfferForm($id)
    {
        $content = file_get_contents('view/html/comm_offer.html');
        $s = array('{%id%}');
        $r = array($id);
        $content = str_replace($s, $r, $content);
        return $content;
    }
}

?>
