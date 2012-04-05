<?php

class PackageManager {

    private $uslugi;
    private $oferty;
    private $wizyt_znaki;
    private $wizyt_logo;
    private $wizyt_www;
    private $baner;
    private $mailing;
    private $wizyt_opis;
    private $wizyt_url;
    private $logo_link;

    public function pobierzInformacjePakietow(DBC $dbc, $id_user) {

        $sql = Query::getActivePackagesForUser($id_user);

        $r = $dbc->query($sql);
        if (!$r)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($r->num_rows <= 0)
            throw new BrakAktywnychPakietow;
        else {

            while ($set = $r->fetch_assoc()) {

                $this->uslugi += is_null($set['uslugi']) ? 1 : $set['uslugi'];
                $this->oferty += is_null($set['oferty']) ? 1 : $set['oferty'];

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

            return TRUE;
        }
    }

    public function pobierzAktywnePakiety(DBC $dbc, $id_user) {

        $sql = Query::getActivePackagesForUser($id_user);

        $r = $dbc->query($sql);
        if (!$r)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($r->num_rows <= 0)
            throw new BrakAktywnychPakietow;
        else {

            while ($set = $r->fetch_assoc()) {
                $result[] = $set;   //wrzucamy wszystkie pobrane pakiety do tablicy każdy jako oddzielny wiersz
            }
            return $result;
        }
    }

    public function dodajPakietUzytkownikowi(DBC $dbc, $id_user, $pakiet) {

        $sql = Query::setPackageForUser($id_user, $pakiet);
        $dbc->query($sql);
        if ($dbc->affected_rows != 1) // obsługa błedu gdy ilość zmienionych wierszy inna niż 1
            throw new NieDodanoPakietu;
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
            return false;
        else
            return true;
    }

    public function czyMoznaDodacWWW() {
        if ($this->wizyt_www <= 0)
            return false;
        else
            return true;
    }

    public function czyMoznaDodacBaner() {
        if ($this->baner <= 0)
            return FALSE;
        else
            return TRUE;
    }

    public function czyMoznaWlaczycMailing() {
        if ($this->mailing <= 0)
            return FALSE;
        else
            return TRUE;
    }

    public function usunUslugeUzytkownikowi(DBC $dbc, $id_user) {
        $pakiet = $this->pobierzAktywnePakiety($dbc, $id_user);

        //szukamy na pobranej liście aktywnych pakietów pierwszego , który ma dostępne usługi - kolejność wg. ważności pakietów gwarantuje nam (SQL: ORDER BY date_end)
        for ($i = 0; $i < sizeof($pakiet); $i++) {

            if ($pakiet[$i]['uslugi'] > 0) {
                $sql = Query::decreaseServicesForUser($id_user, $pakiet[$i]['id_pakietu']);    //zmniejszamy ilosc uslug odpowiedniemy pakietowi uzytkownika
                $dbc->query($sql);
                break;
            } else if (is_null($pakiet[$i]['uslugi'])) {
                break;
            }
        }
    }

    //szukamy na pobranej liście aktywnych pakietów pierwszego , który ma dostępnę oferty - kolejność wg. ważności pakietów gwarantuje nam (SQL: ORDER BY date_end)
    public function usunOferteUzytkownikowi(DBC $dbc, $id_user) {
        $pakiet = $this->pobierzAktywnePakiety($dbc, $id_user);

        for ($i = 0; $i < sizeof($pakiet); $i++) {

            if ($pakiet[$i]['oferty'] > 0) {
                $sql = Query::decreaseCommsForUser($id_user, $pakiet[$i]['id_pakietu']);    //zmniejszamy ilosc ofert odpowiedniemy pakietowi uzytkownika
                $dbc->query($sql);
                break;
            } else if (is_null($pakiet[$i]['oferty'])) {
                break;
            }
        }
    }

    public function sprawdzWizytowke(DBC $dbc, $id_user) {  //sprawdza czy wizytkówka istnieje już w bazie
        $sql = Query::getCardForUser($id_user);

        $r = $dbc->query($sql);
        $result = $r->fetch_array();

        if (!$r || $r->num_rows <= 0)
            return FALSE;
        else
            return TRUE;
    }

    public function pobierzWizytowke(DBC $dbc, $id_user) {  //pobieramy dane do wizytówki
        $sql = Query::getCardForUser($id_user);

        $r = $dbc->query($sql);

        while ($set = $r->fetch_assoc()) {

            $this->wizyt_opis = $set['opis'];
            $this->wizyt_url = $set['www'];
            $this->logo_link = $set['logo'];
        }
    }

    public function pobierzOpis() {
        return $this->wizyt_opis;
    }

    public function pobierzURL() {
        return $this->wizyt_url;
    }

    public function pobierzLogoLink() {
        return $this->logo_link;
    }

}

?>
