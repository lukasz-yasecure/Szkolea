<?php

/**
 * przechowywanie listy kategorii obszarow i tematyk (nazwy idy i info co jest aktywne w tej chwili) na potrzebny left menu oraz selectow w formularzach
 *
 *  2011-09-28  ostatni wglad
 *  2011-10-20  jak to dziala ? addK getK isActK
 *              getK zwraca tablice albo null
 *              dodam obsluge modulow (bez elementu aktywnego, bo to bylo do left menu tylko)
 */
class Categories {

    private $kNames = array();
    private $kIds = array();
    private $kNr = 0; // iterator
    private $kActId = -1;
    private $oNames = array();
    private $oIds = array();
    private $oNr = 0; // iterator
    private $oActId = -1;
    private $tNames = array();
    private $tIds = array();
    private $tNr = 0; // iterator
    private $tActId = -1;
    private $mNames = array();
    private $mIds = array();
    private $mNr = 0; // iterator

    public function addK($k, $id, $act = false) {
        $this->kNames[] = $k;
        $this->kIds[] = $id;

        if ($act)
            $this->kActId = count($this->kNames) - 1;
    }

    public function getK() {
        if ($this->kNr == count($this->kNames))
            return null;
        else {
            $this->kNr++;
            return array($this->kNames[$this->kNr - 1], $this->kIds[$this->kNr - 1]);
        }
    }

    public function isActK() {
        if ($this->kNr - 1 == $this->kActId)
            return true;
        else
            return false;
    }

    public function addO($k, $id, $act = false) {
        $this->oNames[] = $k;
        $this->oIds[] = $id;

        if ($act)
            $this->oActId = count($this->oNames) - 1;
    }

    public function getO() {
        if ($this->oNr == count($this->oNames))
            return null;
        else {
            $this->oNr++;
            return array($this->oNames[$this->oNr - 1], $this->oIds[$this->oNr - 1]);
        }
    }

    public function isActO() {
        if ($this->oNr - 1 == $this->oActId)
            return true;
        else
            return false;
    }

    public function addT($k, $id, $act = false) {
        $this->tNames[] = $k;
        $this->tIds[] = $id;

        if ($act)
            $this->tActId = count($this->tNames) - 1;
    }

    public function getT() {
        if ($this->tNr == count($this->tNames))
            return null;
        else {
            $this->tNr++;
            return array($this->tNames[$this->tNr - 1], $this->tIds[$this->tNr - 1]);
        }
    }

    public function isActT() {
        if ($this->tNr - 1 == $this->tActId)
            return true;
        else
            return false;
    }

    public function addM($k, $id) {
        $this->mNames[] = $k;
        $this->mIds[] = $id;
    }

    public function getM() {
        if ($this->mNr == count($this->mNames))
            return null;
        else {
            $this->mNr++;
            return array($this->mNames[$this->mNr - 1], $this->mIds[$this->mNr - 1]);
        }
    }

    public function resetNrO() {
        $this->oNr = 0;
    }

    public function resetNrT() {
        $this->tNr = 0;
    }

}

?>
