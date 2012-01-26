<?php

/**
 *
 *
 *  2011-10-03  storeCommisionInSession
 *              getCommisionFromSession
 */
class CommisionManager
{
    public function getCommisionFromOldType($t)
    {
        $c = new Commision();
        $c->setCena_min($t['cena']['min']);
        $c->setCena_max($t['cena']['max']);
        $c->setDate_a($t['terminy']['a']);
        $c->setDate_b($t['terminy']['b']);
        $c->setDate_end($t['koniec']);
        $c->setDays($t['dni']);
        $c->setId_comm($t['id']);
        $c->setParts_count($t['zapisanych']);

        return $c;
    }

    /**
     * z podanego wiersza tabeli z bazy robi 1 Commision
     *
     * @param <type> $row
     * @return Commision
     */
    public function getCommisionFromRow($row)
    {
        $c = new Commision();
        $c->setId_comm($row['id_comm']);
        $c->setId_user($row['id_user']);
        $c->setDate_add($row['date_add']);
        $c->setDate_end($row['date_end']);
        $c->setLong($row['long']);
        $c->setDays($row['days']);
        $c->setDate_a($row['date_a']);
        $c->setDate_b($row['date_b']);
        $c->setDate_c($row['date_c']);
        $c->setDate_d($row['date_d']);
        $c->setExpire($row['expire']);
        $c->setPlace($row['place']);
        $c->setWoj($row['woj']);
        $c->setCena_min($row['cena_min']);
        $c->setCena_max($row['cena_max']);
        $c->setParts_count($row['parts_count']);
        $c->setParts($row['parts']);
        $c->setKotm($row['kotm']);
        $c->setKategoria_name($row['kategoria_name']);
        $c->setObszar_name($row['obszar_name']);
        $c->setTematyka_name($row['tematyka_name']);
        $c->setTematyka($row['tematyka']);
        $c->setModuly_names($row['moduly_names']);
        return $c;
    }

    public function storeCommisionInSession(SessionManager $sm, Commision $c)
    {
        $sm->storeCommision($c);
    }

    public function getCommisionFromSession(SessionManager $sm)
    {
        return $sm->getCommision();
    }

    /**
     * zapisuje commision w bazie (+ moduly i zapisanych w oddzielnych tabelach)
     *
     * @param DBC $dbc
     * @param Commision $c
     * @return Commision
     */
    public function saveCommisionInDB(DBC $dbc, Commision $c)
    {
        $sql = Query::saveNewCommisionInDB($c);
        $res = $dbc->query($sql);
        if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);

        $c->setId_comm($dbc->insert_id);
        $sql = Query::saveModulsForCommision($c);
        $res = $dbc->query($sql);
        if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);

        $sql = Query::saveParticipantsForCommision($c);
        $res = $dbc->query($sql);
        if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);

        return $c;
    }

    /**
     * Przy dodawaniu zlecenia przez usera trzeba uzupelnic troche dodatkowych danych
     * a niektore skonwertowac - taki ostatni etap przed wprowadzeniem do bazy
     *
     * @param Commision $c
     * @param DBC $dbc
     * @param CategoryManager $cm
     * @return Commision
     */
    public function completeData(Commision $c, DBC $dbc, CategoryManager $cm)
    {
        try
        {
            $kategoria_name = $cm->getNameOfKategoria($dbc, $c->getCat());
            $obszar_name = $cm->getNameOfObszar($dbc, $c->getSubcat());
            $tematyka_name = $cm->getNameOfTematyka($dbc, $c->getSubsubcat());
            $moduly_names = '';

            $moduly = $c->getModuly();
            if(is_array($moduly))
            {
                $temp = array();
                foreach($moduly as $m)
                {
                    $temp[] = $cm->getNameOfModul($dbc, $m);
                }

                $moduly_names = implode(',', $temp);
            }

            $c->setKategoria_name($kategoria_name);
            $c->setObszar_name($obszar_name);
            $c->setTematyka_name($tematyka_name);
            $c->setModuly_names($moduly_names);
            $c->setKotm($kategoria_name.', '.$obszar_name.', '.$tematyka_name.', '.$moduly_names);
            $c->setDate_a(UF::date2timestamp($c->getDate_a()));
            $c->setDate_b(UF::date2timestamp($c->getDate_b()));
            $c->setDate_c(UF::date2timestamp($c->getDate_c()));
            $c->setDate_d(UF::date2timestamp($c->getDate_d()));
            $c->setDays(implode(',', $c->getDays()));

            $parts = $c->getParts();
            $temp = array();
            if(is_array($parts))
            {
                foreach($parts as $p)
                {
                    $temp[] = implode(' ', $p);
                }

                $temp = implode(', ', $temp);
            }

            $c->setParts($temp);
        }
        catch(Exception $e)
        {
            throw $e;
        }

        return $c;
    }

    /**
     * pobieranie konkretnego zlecenia z bazy na podstawie ID
     *
     * @param DBC $dbc
     * @param <type> $id
     * @return <type>
     */
    public function getCommision(DBC $dbc, $id)
    {
        $sql = Query::getCommision($id);
        $res = $dbc->query($sql);
        if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        return $this->getCommisionFromRow($res->fetch_assoc());
    }
}

?>
