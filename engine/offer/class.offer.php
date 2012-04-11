<?php

class Offer {

    private $id_ofe;
    private $id_comm;
    private $id_user;
    private $date_add;
    private $cena;
    private $cenax;
    private $rozl;
    private $inne;
    private $ile_kaw;
    private $date_a;
    private $date_b;
    private $status;
    private $payment;
    private $wlasciciel;

    public function getId_ofe() {
        return $this->id_ofe;
    }

    public function setId_ofe($id_ofe) {
        $this->id_ofe = $id_ofe;
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

    public function getCena() {
        return $this->cena;
    }

    public function setCena($cena) {
        $this->cena = $cena;
    }

    public function getCenax() {
        return $this->cenax;
    }

    public function setCenax($cenax) {
        $this->cenax = $cenax;
    }

    public function getRozl() {
        return $this->rozl;
    }

    public function setRozl($rozl) {
        $this->rozl = $rozl;
    }

    public function getInne() {
        return $this->inne;
    }

    public function setInne($inne) {
        $this->inne = $inne;
    }

    public function getIle_kaw() {
        return $this->ile_kaw;
    }

    public function setIle_kaw($ile_kaw) {
        $this->ile_kaw = $ile_kaw;
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

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getPayment() {
        return $this->payment;
    }

    public function setPayment($payment) {
        $this->payment = $payment;
    }

    /** Właściciel jako obiek typu User dla oferty
     *
     * @return User 
     */
    public function getWlasciciel() {
        return $this->wlasciciel;
    }

    public function setWlasciciel(User $wlasciciel) {
        $this->wlasciciel = $wlasciciel;
    }

}

?>
