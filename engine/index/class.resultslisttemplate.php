<?php

/**
 * template dla wynikow na glownej stronie
 *
 *  2011-09-29  szablon z petla
 *  2011-11-08  wykorzystane, dziala jak nalezy, na razie tylko dla Commisions
 */
class ResultsListTemplate
{
    private $content;
    private $comms = false;
    private $servs = false;
    private $search_c = array('{%bgcolor1%}', '{%bgcolor2%}', '{%ikona%}', '{%kategoria%}', '{%tematyka%}', '{%id%}', '{%gdzie%}', '{%zapisanych%}', '{%cena_min%}', '{%cena_max%}', '{%pozostalo%}', '{%moduly%}', '{%show_offers%}');
    private $search_s = array('{%bgcolor1%}', '{%bgcolor2%}', '{%ikona%}', '{%kategoria%}', '{%nazwa%}', '{%id%}', '{%gdzie%}', '{%cena%}', '{%pozostalo%}', '{%moduly%}', '{%program%}');
    private $replace = array();

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function addComm($b1, $b2, $ico, $kat, $tem, $id, $gdzie, $zapis, $c_min, $c_max, $pozo, $moduly, $show_offers)
    {
        $this->comms = true;
        $this->replace[] = array($b1, $b2, $ico, $kat, $tem, $id, $gdzie, $zapis, $c_min, $c_max, $pozo, $moduly, $show_offers);
    }

    public function addServ($b1, $b2, $ico, $kat, $nazwa, $id, $gdzie, $cena, $pozo, $moduly, $pro)
    {
        $this->servs = true;
        $this->replace[] = array($b1, $b2, $ico, $kat, $nazwa, $id, $gdzie, $cena, $pozo, $moduly, $pro);
    }

    /**
     *
     * @return string
     */
    public function getContent()
    {
        $content = '';
        $search = '';

        if($this->comms) $search = $this->search_c;
        else if($this->servs) $search = $this->search_s;

        foreach($this->replace as $l)
        {
            $content.= str_replace($search, $l, $this->content);
        }

        return $content;
    }

    public function comms()
    {
        return $this->comms;
    }
}

?>
