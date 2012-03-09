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
        $o->setStatus($row['status']);

        return $o;
    }

    //funkcja pobiera z bazy za pomocą SQL wszystkie dane ofert z dołączonymi danymi użytkowników , którzy je dodali, a następnie tworzy tablicę obiektów z tymi danymi
    public function OfferToObjectTable(DBC $dbc) {

        $sql = Query::getProfileCommsOffers(true); //pobranie ofert z użytkownikami
        $r = $dbc->query($sql);
        $oferty = array();
        $um = new UserManager();
        $o = new Offer();
        $i = 0;

        while ($r_ofe = $r->fetch_assoc()) {
            $oferty[$i] = $this->getOfferFromRow($r_ofe);                    //każda pozycja w tablicy to obiekt z ofertą (pełne dane) 
            $oferty[$i]->setWlasciciel($um->getUserFromRow($r_ofe));        //pole właściciel to obiekt User (wszystkie dane użytkownika)
            $i++;
        }

        return $oferty;
    }

}

?>
