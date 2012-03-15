<?php

/**
 *  2011-11-09  na razie tylko zliczanie ofert dla konkretnego zlecenia
 */
class OfferManager {

    public function getOfferCountForCommision(DBC $dbc, Commision $c) {
        $cid = $c->getId_comm();

        if (is_null($cid))
            return null;

        $sql = Query::getOfferCountForCommision($cid);
        $res = $dbc->query($sql);
        if (!$res)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        $res = $res->fetch_assoc();

        return $res['ile'];
    }

    /**
     * Z otrzymanego wiersza ustawia wszystkie parametry oferty i zwraca ją jako obiekt
     * @param type $row
     * @return Offer 
     */
    public function getOfferFromRow($row) {
        $o = new Offer();

        $o->setId_ofe($row['id_ofe']);
        $o->setId_comm($row['id_comm']);
        $o->setId_user($row['id_user']);
        $o->setDate_add($row['date_add']);
        $o->setCena($row['cena']);
        $o->setCenax($row['cenax']);
        $o->setRozl($row['rozl']);
        $o->setInne($row['inne']);
        $o->setIle_kaw($row['ile_kaw']);
        $o->setDate_a($row['date_a']);
        $o->setDate_b($row['date_b']);
        $o->setStatus($row['ofe_status']);

        return $o;
    }

    //, a następnie tworzy 
    /*     * funkcja pobiera z bazy za pomocą SQL wszystkie dane ofert z dołączonymi danymi użytkowników , którzy je dodali
     *
     * @param DBC $dbc
     * @return tablica tablica obiektów typu Offer z danymi ofert i użytkówników je dodających 
     */
    public function getOffersAndOwners(DBC $dbc) {

        $sql = Query::getCommsOffersAndOwners(true); //pobranie ofert z użytkownikami
        $r = $dbc->query($sql);

        $oferty = array();
        $um = new UserManager();
        $i = 0;


        if ($r != FALSE) {
            while ($r_ofe = $r->fetch_assoc()) {
                $oferty[$i] = $this->getOfferFromRow($r_ofe);                    //każda pozycja w tablicy to obiekt z ofertą (pełne dane) 
                $oferty[$i]->setWlasciciel($um->getUserFromRow($r_ofe));        //pole właściciel to obiekt User (wszystkie dane użytkownika)
                $i++;
            }
        }
        return $oferty;
    }

    //funkcja sprawdzająca czy był wybór ofert dla danego zlecenia
    public function getStatusOffersChoiceForComm($oferty, $id_comm) {
        $flag = false; //flaga informująca czy były oferty , ale nie zostały wybrane, czy nie było ich wcale
        for ($i = 0; $i < count($oferty); $i++) {
            if ($oferty[$i]->getId_comm() == $id_comm) {
                if ($oferty[$i]->getStatus() == 1) {
                    return MSG::waitingForChoice();
                } elseif ($oferty[$i]->getStatus() == 2) {
                    return MSG::offerChosen();
                }
                $flag = true;   //flaga oznaczająca, że były oferty, ale nie została żadana wybrana
            }
        }
        if ($flag == true)
            return MSG::offerUnchosen();    //przypadek gdy były oferty , ale nie została żadna wybrana
        else
            return MSG::noOffer();                //przypadek gdy nie było ofert
    }

    //funkcja sprawdzająca czy opłacono oferty przez trenera
    public function getStatusOffersPaymentForComm($oferty, $id_comm) {

        return 'Funkcja jeszcze nie działa!';
        //return 'Trener jeszcze nie zapłacił';
    }

}

?>
