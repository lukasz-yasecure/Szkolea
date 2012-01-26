<?php

/**
 *  W tej klasie trzymam zadania dotyczace wyswietlanych zlecen/uslug
 *  Obiekt jest ustawiany albo przez klikniecie w lewym menu albo przez wyszukiwarke
 *  Trzeba dokonczyc tworzenie tego obiektu w UserData
 *
 *  2011-11-07  parametry wyswietlania wynikow (wyszukiwarka, menu z lewej)
 */
class Search
{
    private $what;
    private $all;
    private $k;
    private $o;
    private $t;
    private $kot_id;
    private $woj;
    private $place;
    private $cena_min;
    private $cena_max;
    private $data_a;
    private $data_b;
    private $word;

    public function getKot_id() {
        return $this->kot_id;
    }

    public function setKot_id($kot_id) {
        $this->kot_id = $kot_id;
    }

    public function getAll() {
        return $this->all;
    }

    public function setAll($all) {
        $this->all = $all;
    }

    public function getWhat() {
        return $this->what;
    }

    public function setWhat($what) {
        $this->what = $what;
    }

    public function getK() {
        return $this->k;
    }

    public function setK($k) {
        $this->k = $k;
    }

    public function getO() {
        return $this->o;
    }

    public function setO($o) {
        $this->o = $o;
    }

    public function getT() {
        return $this->t;
    }

    public function setT($t) {
        $this->t = $t;
    }

    public function getWoj() {
        return $this->woj;
    }

    public function setWoj($woj) {
        $this->woj = $woj;
    }

    public function getPlace() {
        return $this->place;
    }

    public function setPlace($place) {
        $this->place = $place;
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

    public function getData_a() {
        return $this->data_a;
    }

    public function setData_a($data_a) {
        $this->data_a = $data_a;
    }

    public function getData_b() {
        return $this->data_b;
    }

    public function setData_b($data_b) {
        $this->data_b = $data_b;
    }

    public function getWord() {
        return $this->word;
    }

    public function setWord($word) {
        $this->word = $word;
    }
}

?>
