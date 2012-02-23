<?php

class PackageManager {

    private $uslugi;
    private $oferty;
    private $wizyt_znaki;
    private $wizyt_logo;
    private $wizyt_www;
    private $baner;
    private $mailing;


    public function __construct(DBC $dbc, $id_user) {



        $sql = Query::getActivePackagesForUser($id_user);

        $r = $dbc->query($sql);
        if (!$r)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($r->num_rows <= 0)
            throw new BrakAktywnychPakietow;
        else {

            while ($set = $r->fetch_assoc()) {


                $this->uslugi += $set['uslugi'];
                $this->oferty += $set['oferty'];

                if ($this->wizyt_znaki < $set['wizyt_znaki'])
                    $this->wizyt_znaki = $set['wizyt_znaki'];
                if ($this->wizyt_logo < $set['wizyt_logo'])
                    $this->wizyt_logo = $set['wizyt_logo'];
                if ($this->wizyt_www < $set['wizyt_www'])
                    $this->wizyt_www = $set['wizyt_www'];
                if ($this->baner < $set['baner'])
                    $this->baner = $set['baner'];;
                if ($this->mailing < $set['mailing'])
                    $this->mailing = $set['mailing'];
            }
        }
    }

    public function dodajPakietUzytkownikowi(DBC $dbc, $id_user, $pakiet) {

        $sql = Query::setPackageForUser($id_user, $pakiet);
        $dbc->query($sql);
    }

    public function pobierzPakiet(DBC $dbc, $id_pakietu) {

        $sql = Query::getPackage($id_pakietu);

        $r = $dbc->query($sql);

        $result = $r->fetch_array();

        if (!$r)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($r->num_rows <= 0)
            throw new BladPobieraniaPakietu;
        return $result;
    }

    public function czyMoznaDodacUslugi() {
        if ($this->uslugi <= 0)
            throw new NieMoznaDodawacUslug;
    }

    public function czyMoznaDodacOferty() {
        if ($this->oferty <= 0)
            throw new NieMoznaDodawacOfert;
    }

    public function iIleZnakowWizytowka() {
        return $this->wizyt_znaki;
    }

    public function czyMoznaDodacLogo() {
        if ($this->wizyt_logo <= 0)
            throw new NieMoznaDodacLogo;
    }

    public function czyMoznaDodacWWW() {
        if ($this->wizyt_www <= 0)
            throw new NieMoznaDodacWWW;
    }

    public function czyMoznaDodacBaner() {
        if ($this->baner <= 0)
            throw new NieMoznaDodacBanera;
    }

    public function czyMoznaWlaczycMailing() {
        if ($this->mailing <= 0)
            throw new NieMoznaWlaczycMailingu;
    }

}

?>
