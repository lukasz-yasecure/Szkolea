<?php

/**
 * kontener danych faktury
 *
 */
class Invoice {

    private $id_faktura;
    private $id_user;
    private $typ;
    private $kwota_brutto;
    private $id_pakiet;
    private $id_oferta;
    private $numer_fv;
    private $data_fv;
    private $numer_fpf;
    private $data_fpf;

    public function getId_faktura() {
        return $this->id_faktura;
    }

    public function getId_user() {
        return $this->id_user;
    }

    public function getTyp() {
        return $this->typ;
    }

    public function getKwota_brutto() {
        return $this->kwota_brutto;
    }

    public function getId_pakiet() {
        return $this->id_pakiet;
    }

    public function getId_oferta() {
        return $this->id_oferta;
    }

    public function getNumer_fv() {
        return $this->numer_fv;
    }

    public function getData_fv() {
        return $this->data_fv;
    }

    public function getNumer_fpf() {
        return $this->numer_fpf;
    }

    public function getData_fpf() {
        return $this->data_fpf;
    }

    public function setId_faktura($id_faktura) {
        $this->id_faktura = $id_faktura;
    }

    public function setId_user($id_user) {
        $this->id_user = $id_user;
    }

    public function setTyp($typ) {
        //rozróżnienie za co jest faktura
        if ($typ == 1)
            $this->typ = 'prowizja';
        elseif ($typ == 2)
            $this->typ = 'pakiet';
    }

    public function setKwota_brutto($kwota_brutto) {
        $this->kwota_brutto = $kwota_brutto;
    }

    public function setId_pakiet($id_pakiet) {
        $this->id_pakiet = $id_pakiet;
    }

    public function setId_oferta($id_oferta) {
        $this->id_oferta = $id_oferta;
    }

    public function setNumer_fv($numer_fv) {
        $this->numer_fv = $numer_fv;
    }

    public function setData_fv($data_fv) {
        $this->data_fv = $data_fv;
    }

    public function setNumer_fpf($numer_fpf) {
        $this->numer_fpf = $numer_fpf;
    }

    public function setData_fpf($data_fpf) {
        $this->data_fpf = $data_fpf;
    }

}

?>
