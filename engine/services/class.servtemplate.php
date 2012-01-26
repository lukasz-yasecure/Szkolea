<?php

/**
 * kontener danych dla podstrony uslugi
 *
 *  2011-11-10  dziala
 */
class ServTemplate
{
    private $content;
    private $search = array(
        '{%id_serv%}',
        '{%date_end%}',
        '{%program%}',
        '{%info%}',
        '{%terminy%}',
        '{%cena%}',
        '{%wizytowka%}', // na przyszlosc
        '{%place%}',
        '{%kontakt%}',
        '{%kategoria_name%}',
        '{%obszar_name%}',
        '{%tematyka_name%}',
        '{%moduly_names%}',
        '{%name%}'
    );
    private $replace = array();

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
        $this->replace = array_fill(0, 14, null);
    }

    public function setId_serv($id)
    {
        $this->replace[0] = $id;
    }

    public function setDate_end($d)
    {
        $this->replace[1] = date('Y-m-d H:i', $d);
    }

    public function setProgram($l)
    {
        $this->replace[2] = $l;
    }

    public function setInfo($e)
    {
        $this->replace[3] = $e;
    }

    public function setTerminy($d1, $d2)
    {
        if(is_null($d1) || is_null($d2)) $this->replace[4] = 'do uzgodnienia';
        else $this->replace[4] = date('Y-m-d', $d1).' - '.date('Y-m-d', $d2);
    }

    public function setCena($c1, $c2)
    {
        $this->replace[5] = $c1.' [ '.UF::cena_2name($c2).' ]';
    }

    public function setWizytowka($e)
    {
        $this->replace[6] = $e;
    }

    public function setPlace($p, $w = null)
    {
        $p.= is_null($w) ? '' : ' ('.UF::nr2wojName($w).')';
        $this->replace[7] = $p;
    }

    public function setKontakt($m, $p, $k, $user_logged)
    {
        if($user_logged) $this->replace[8] = $k.'<br/>'.$m.'<br/>'.$p;
        else $this->replace[8] = BFEC::$e['UM']['serv_niezalogowany'];
    }

    public function setKategoria_name($e)
    {
        $this->replace[9] = $e;
    }

    public function setObszar_name($e)
    {
        $this->replace[10] = $e;
    }

    public function setTematyka_name($e)
    {
        $this->replace[11] = $e;
    }

    public function setModuly_names($e)
    {
        if(is_null($e)) $this->replace[12] = 'brak podanych modułów';
        else $this->replace[12] = $e;
    }

    public function setName($n)
    {
        $this->replace[13] = $n;
    }

    /**
     *
     * @return string
     */
    public function getContent()
    {
        return str_replace($this->search, $this->replace, $this->content);
    }
}

?>
