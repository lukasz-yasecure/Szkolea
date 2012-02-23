<?php

/**
 * kontener danych usera - tworzony tylko i wylacznie przez UserManagera
 *
 *  2011-09-21  isLogged() +
 *              setLoggedStatus() +
 *              isDostawca isKlient isAdmin isActivated +
 */
class User
{
    private $logged = false;

    private $id_user = null;
    private $email = null;
    private $pass = null;
    private $status = null;
    private $date_reg = null;
    private $kind = null;
    private $os_name = null;
    private $os_surname = null;
    private $os_street = null;
    private $os_house_number = null;
    private $os_postcode = null;
    private $os_city = null;
    private $os_woj = null;
    private $os_phone = null;
    private $f_name = null;
    private $f_surname = null;
    private $f_position = null;
    private $f_company = null;
    private $f_street = null;
    private $f_house_number = null;
    private $f_postcode = null;
    private $f_city = null;
    private $f_woj = null;
    private $f_regon = null;
    private $f_nip = null;
    private $f_krs = null;
    private $f_phone = null;

    public function isLogged()
    {
        return $this->logged;
    }

    public function setLoggedStatus($log)
    {
        $this->logged = $log;
    }

    public function isDostawca()
    {
        if($this->kind == 'D') return true;
        else return false;
    }

    public function isKlient()
    {
        if($this->kind == 'K') return true;
        else return false;
    }

    public function isAdmin()
    {
        if($this->kind == 'A') return true;
        else return false;
    }
    
    public function isBanned()
    {
        if($this->status == '2') return true;
        else return false;
    }

    public function isActivated()
    {
        if($this->status == '1') return true;
        else return false;
    }

    public function getId_user() {
        return $this->id_user;
    }

    public function setId_user($id_user) {
        $this->id_user = $id_user;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPass() {
        return $this->pass;
    }

    public function setPass($pass) {
        $this->pass = $pass;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getDate_reg() {
        return $this->date_reg;
    }

    public function setDate_reg($date_reg) {
        $this->date_reg = $date_reg;
    }

    public function getKind() {
        return $this->kind;
    }

    public function setKind($kind) {
        $this->kind = $kind;
    }

    public function getOs_name() {
        return $this->os_name;
    }

    public function setOs_name($os_name) {
        $this->os_name = $os_name;
    }

    public function getOs_surname() {
        return $this->os_surname;
    }

    public function setOs_surname($os_surname) {
        $this->os_surname = $os_surname;
    }

    public function getOs_street() {
        return $this->os_street;
    }

    public function setOs_street($os_street) {
        $this->os_street = $os_street;
    }

    public function getOs_house_number() {
        return $this->os_house_number;
    }

    public function setOs_house_number($os_house_number) {
        $this->os_house_number = $os_house_number;
    }

    public function getOs_postcode() {
        return $this->os_postcode;
    }

    public function setOs_postcode($os_postcode) {
        $this->os_postcode = $os_postcode;
    }

    public function getOs_city() {
        return $this->os_city;
    }

    public function setOs_city($os_city) {
        $this->os_city = $os_city;
    }

    public function getOs_woj() {
        return $this->os_woj;
    }

    public function setOs_woj($os_woj) {
        $this->os_woj = $os_woj;
    }

    public function getOs_phone() {
        return $this->os_phone;
    }

    public function setOs_phone($os_phone) {
        $this->os_phone = $os_phone;
    }

    public function getF_name() {
        return $this->f_name;
    }

    public function setF_name($f_name) {
        $this->f_name = $f_name;
    }

    public function getF_surname() {
        return $this->f_surname;
    }

    public function setF_surname($f_surname) {
        $this->f_surname = $f_surname;
    }

    public function getF_position() {
        return $this->f_position;
    }

    public function setF_position($f_position) {
        $this->f_position = $f_position;
    }

    public function getF_company() {
        return $this->f_company;
    }

    public function setF_company($f_company) {
        $this->f_company = $f_company;
    }

    public function getF_street() {
        return $this->f_street;
    }

    public function setF_street($f_street) {
        $this->f_street = $f_street;
    }

    public function getF_house_number() {
        return $this->f_house_number;
    }

    public function setF_house_number($f_house_number) {
        $this->f_house_number = $f_house_number;
    }

    public function getF_postcode() {
        return $this->f_postcode;
    }

    public function setF_postcode($f_postcode) {
        $this->f_postcode = $f_postcode;
    }

    public function getF_city() {
        return $this->f_city;
    }

    public function setF_city($f_city) {
        $this->f_city = $f_city;
    }

    public function getF_woj() {
        return $this->f_woj;
    }

    public function setF_woj($f_woj) {
        $this->f_woj = $f_woj;
    }

    public function getF_regon() {
        return $this->f_regon;
    }

    public function setF_regon($f_regon) {
        $this->f_regon = $f_regon;
    }

    public function getF_nip() {
        return $this->f_nip;
    }

    public function setF_nip($f_nip) {
        $this->f_nip = $f_nip;
    }

    public function getF_krs() {
        return $this->f_krs;
    }

    public function setF_krs($f_krs) {
        $this->f_krs = $f_krs;
    }

    public function getF_phone() {
        return $this->f_phone;
    }

    public function setF_phone($f_phone) {
        $this->f_phone = $f_phone;
    }
}

?>
