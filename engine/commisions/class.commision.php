<?php

/**
 *  2011-11-08  kontener dla danych zwiazanych ze zleceniem
 */
class Commision
{
    // wszystkie pola z DB
    private $id_comm;
    private $id_user;
    private $date_add;
    private $date_end;
    private $long;
    private $days;
    private $date_a;
    private $date_b;
    private $date_c;
    private $date_d;
    private $expire;
    private $place;
    private $woj;
    private $cena_min;
    private $cena_max;
    private $parts_count;
    private $parts;
    private $kotm;
    private $kategoria_name;
    private $obszar_name;
    private $tematyka_name;
    private $tematyka; // to samo co subsubcat
    private $moduly_names; // nazwy

    // dodatkowe pola
    private $cat; // id cat
    private $subcat; // id obszaru
    private $moduly; // idy
    private $oferty; // liczba ofert
    private $show_offers; // link 'pokaż oferty'

    public function getOferty() {
        return $this->oferty;
    }

    public function setOferty($oferty) {
        $this->oferty = $oferty;
    }
    
    public function getObszar_name() {
        return $this->obszar_name;
    }

    public function setObszar_name($obszar_name) {
        $this->obszar_name = $obszar_name;
    }

    public function getModuly_names() {
        return $this->moduly_names;
    }

    public function setModuly_names($moduly_names) {
        $this->moduly_names = $moduly_names;
    }

    public function getCat() {
        return $this->cat;
    }

    public function setCat($cat) {
        $this->cat = $cat;
    }

    public function getSubcat() {
        return $this->subcat;
    }

    public function setSubcat($subcat) {
        $this->subcat = $subcat;
    }

    public function getSubsubcat() {
        return $this->tematyka;
    }

    public function setSubsubcat($subsubcat) {
        $this->tematyka = $subsubcat;
    }

    public function getModuly() {
        return $this->moduly;
    }

    public function setModuly($moduly) {
        $this->moduly = $moduly;
    }

    public function getKotm() {
        return $this->kotm;
    }

    public function setKotm($kotm) {
        $this->kotm = $kotm;
    }

    public function getKategoria_name() {
        return $this->kategoria_name;
    }

    public function setKategoria_name($kategoria_name) {
        $this->kategoria_name = $kategoria_name;
    }

    public function getTematyka_name() {
        return $this->tematyka_name;
    }

    public function setTematyka_name($tematyka_name) {
        $this->tematyka_name = $tematyka_name;
    }

    public function getTematyka() {
        return $this->tematyka;
    }

    public function setTematyka($tematyka) {
        $this->tematyka = $tematyka;
    }

    public function getId_comm() {
        return $this->id_comm;
    }

    public function setId_comm($id_comm) {
        $this->id_comm = $id_comm;
    }

    public function getId_user() {
        return $this->id_user;
    }

    public function setId_user($id_user) {
        $this->id_user = $id_user;
    }

    public function getDate_add() {
        return $this->date_add;
    }

    public function setDate_add($date_add) {
        $this->date_add = $date_add;
    }

    public function getDate_end() {
        return $this->date_end;
    }

    public function setDate_end($date_end) {
        $this->date_end = $date_end;
    }

    public function getLong() {
        return $this->long;
    }

    public function setLong($long) {
        $this->long = $long;
    }

    public function getDays() {
        return $this->days;
    }

    public function setDays($days) {
        $this->days = $days;
    }

    public function getDate_a() {
        return $this->date_a;
    }

    public function setDate_a($date_a) {
        $this->date_a = $date_a;
    }

    public function getDate_b() {
        return $this->date_b;
    }

    public function setDate_b($date_b) {
        $this->date_b = $date_b;
    }

    public function getDate_c() {
        return $this->date_c;
    }

    public function setDate_c($date_c) {
        $this->date_c = $date_c;
    }

    public function getDate_d() {
        return $this->date_d;
    }

    public function setDate_d($date_d) {
        $this->date_d = $date_d;
    }

    public function getExpire() {
        return $this->expire;
    }

    public function setExpire($expire) {
        $this->expire = $expire;
    }

    public function getPlace() {
        return $this->place;
    }

    public function setPlace($place) {
        $this->place = $place;
    }

    public function getWoj() {
        return $this->woj;
    }

    public function setWoj($woj) {
        $this->woj = $woj;
    }

    public function getCena_min() {
        return $this->cena_min;
    }

    public function setCena_min($cena_min) {
        $this->cena_min = $cena_min;
    }

    public function getCena_max() {
        return $this->cena_max;
    }

    public function setCena_max($cena_max) {
        $this->cena_max = $cena_max;
    }

    public function getParts_count() {
        return $this->parts_count;
    }

    public function setParts_count($parts_count) {
        $this->parts_count = $parts_count;
    }

    public function getParts() {
        return $this->parts;
    }

    public function setParts($parts) {
        $this->parts = $parts;
    }
    
    public function getShow_offers() {
        return $this->show_offers;
    }

    public function setShow_offers($show_offers) {
        $this->show_offers = $show_offers;
    }

}

?>
