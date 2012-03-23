<?php

/**
 *  2011-11-09  kontenet danych dla Usługi Service
 */
class Service {

    // wszystkie pola z DB
    private $id_serv;
    private $id_user;
    private $date_add;
    private $date_end;
    private $name;
    private $program;
    private $date_a;
    private $date_b;
    private $place;
    private $woj;
    private $cena;
    private $cena_;
    private $desc;
    private $mail;
    private $phone;
    private $contact;
    private $kotm;
    private $kategoria_name;
    private $obszar_name;
    private $tematyka_name;
    private $tematyka;
    private $moduly_names;
    // dodatkowe pola
    private $cat;
    private $subcat;
    private $moduly;
    private $date_uzg;
    private $promote_date_add;
    private $promote_date_end;

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

    public function getDate_uzg() {
        return $this->date_uzg;
    }

    public function setDate_uzg($date_uzg) {
        $this->date_uzg = $date_uzg;
    }

    public function getId_serv() {
        return $this->id_serv;
    }

    public function setId_serv($id_serv) {
        $this->id_serv = $id_serv;
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

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getProgram() {
        return $this->program;
    }

    public function setProgram($program) {
        $this->program = $program;
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

    public function getCena() {
        return $this->cena;
    }

    public function setCena($cena) {
        $this->cena = $cena;
    }

    public function getCena_() {
        return $this->cena_;
    }

    public function setCena_($cena_) {
        $this->cena_ = $cena_;
    }

    public function getDesc() {
        return $this->desc;
    }

    public function setDesc($desc) {
        $this->desc = $desc;
    }

    public function getMail() {
        return $this->mail;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getContact() {
        return $this->contact;
    }

    public function setContact($contact) {
        $this->contact = $contact;
    }

    public function getKotm() {
        return $this->kotm;
    }

    public function setKotm($kotm) {
        $this->kotm = $kotm;
    }

    public function setPromoteDate_add($date) {
        $this->promote_date_add = $date;
    }

    public function setPromoteDate_end($date) {
        $this->promote_date_end = $date;
    }

    public function setKategoria_name($kategoria_name) {
        $this->kategoria_name = $kategoria_name;
    }

    public function getPromoteDate_add() {
        return $this->promote_date_add;
    }

    public function getPromoteDate_end() {
        return $this->promote_date_end;
    }

    public function getKategoria_name() {
        return $this->kategoria_name;
    }

    public function getObszar_name() {
        return $this->obszar_name;
    }

    public function setObszar_name($obszar_name) {
        $this->obszar_name = $obszar_name;
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

    public function getModuly_names() {
        return $this->moduly_names;
    }

    public function setModuly_names($moduly_names) {
        $this->moduly_names = $moduly_names;
    }

    public function isPromoted() {
        if (isset($this->date_end) && $this->date_end > time())
            return TRUE;
        else
            return FALSE;
    }

}

?>
