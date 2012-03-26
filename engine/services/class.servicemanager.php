<?php

class ServiceManager {

    /**
     * Przy dodawaniu uslugi przez usera trzeba uzupelnic troche dodatkowych danych
     * a niektore skonwertowac - taki ostatni etap przed wprowadzeniem do bazy
     *
     * @param Service $s
     * @param DBC $dbc
     * @param CategoryManager $cm
     * @return Service
     */
    public function completeData(Service $s, DBC $dbc, CategoryManager $cm) {
        try {
            $kategoria_name = $cm->getNameOfKategoria($dbc, $s->getCat());
            $obszar_name = $cm->getNameOfObszar($dbc, $s->getSubcat());
            $tematyka_name = $cm->getNameOfTematyka($dbc, $s->getSubsubcat());
            $moduly_names = '';

            $moduly = $s->getModuly();
            if (is_array($moduly)) {
                $temp = array();
                foreach ($moduly as $m) {
                    $temp[] = $cm->getNameOfModul($dbc, $m);
                }

                $moduly_names = implode(',', $temp);
            }

            $s->setKategoria_name($kategoria_name);
            $s->setObszar_name($obszar_name);
            $s->setTematyka_name($tematyka_name);
            $s->setModuly_names($moduly_names);
            $s->setKotm($kategoria_name . ', ' . $obszar_name . ', ' . $tematyka_name . ', ' . $moduly_names);
            $s->setDate_a(UF::date2timestamp($s->getDate_a()));
            $s->setDate_b(UF::date2timestamp($s->getDate_b()));
        } catch (Exception $e) {
            throw $e;
        }

        return $s;
    }

    /**
     * Dodawanie Service i powiazanych modulow do bazy (2 tabele atakowane)
     *
     * @param DBC $dbc
     * @param Service $s
     * @return Service 
     */
    public function saveServiceInDB(DBC $dbc, Service $s) {
        $sql = Query::saveNewServiceInDB($s);
        $res = $dbc->query($sql);
        if (!$res)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);

        $s->setId_serv($dbc->insert_id);
        if (!is_null($s->getModuly())) {
            $sql = Query::saveModulsForService($s);
            $res = $dbc->query($sql);
            if (!$res)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        }

        return $s;
    }

    /**
     * pobieranie konkretnego Service z bazy danych
     *
     * @param DBC $dbc
     * @param int $id
     * @return Service
     */
    public function getService(DBC $dbc, $id) {
        $sql = Query::getService($id);
        $res = $dbc->query($sql);
        if (!$res)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        return $this->getServiceFromRow($res->fetch_assoc());
    }

    public function getServiceFromRow($row) {
        $s = new Service();
        $s->setId_serv(isset($row['id_serv']) ? $row['id_serv'] : null);
        $s->setId_user(isset($row['id_user']) ? $row['id_user'] : null);
        $s->setDate_add(isset($row['date_add']) ? $row['date_add'] : null);
        $s->setDate_end(isset($row['date_end']) ? $row['date_end'] : null);
        $s->setName(isset($row['name']) ? $row['name'] : null);
        $s->setProgram(isset($row['program']) ? $row['program'] : null);
        $s->setDate_a(isset($row['date_a']) ? $row['date_a'] : null);
        $s->setDate_b(isset($row['date_b']) ? $row['date_b'] : null);
        $s->setPlace(isset($row['place']) ? $row['place'] : null);
        $s->setWoj(isset($row['woj']) ? $row['woj'] : null);
        $s->setCena(isset($row['cena']) ? $row['cena'] : null);
        $s->setCena_(isset($row['cena_']) ? $row['cena_'] : null);
        $s->setMail(isset($row['mail']) ? $row['mail'] : null);
        $s->setPhone(isset($row['phone']) ? $row['phone'] : null);
        $s->setContact(isset($row['contact']) ? $row['contact'] : null);
        $s->setDesc(isset($row['desc']) ? $row['desc'] : null);
        $s->setKategoria_name(isset($row['kategoria_name']) ? $row['kategoria_name'] : null);
        $s->setObszar_name(isset($row['obszar_name']) ? $row['obszar_name'] : null);
        $s->setTematyka_name(isset($row['tematyka_name']) ? $row['tematyka_name'] : null);
        $s->setTematyka(isset($row['tematyka']) ? $row['tematyka'] : null);
        $s->setModuly_names(isset($row['moduly_names']) ? $row['moduly_names'] : null);
        $s->setKotm(isset($row['kotm']) ? $row['kotm'] : null);
        $s->setPromoteDate_add(isset($row['promote_date_add']) ? $row['promote_date_add'] : null);
        $s->setPromoteDate_end(isset($row['promote_date_end']) ? $row['promote_date_end'] : null);
        return $s;
    }

    /**
     * zwraca liczbe uslug w poszczegolnych kategoriach
     * 
     * @param DBC $dbc
     * @return type
     * @throws DBQueryException 
     */
    public function getServsSums(DBC $dbc) {

        $sql = Query::ServsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
        //throw new EmptyList();
            return array();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['kategoria_id']] = $r['ServsSums'];
        }
        return $Sums;
    }

    /**
     * zwraca liczbe uslug w poszczegolnych obszarach
     * @param DBC $dbc
     * @return type
     * @throws DBQueryException 
     */
    public function getSubservsSums(DBC $dbc) {
        $sql = Query::SubservsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
        //throw new EmptyList();
            return array();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['obszar_id']] = $r['SubservsSums'];
        }
        return $Sums;
    }

    /**
     * zwraca liczbe uslug w poszczegolnych tematykach
     * @param DBC $dbc
     * @return type
     * @throws DBQueryException 
     */
    public function getSubsubservsSums(DBC $dbc) {
        $sql = Query::SubsubservsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
        //throw new EmptyList();
            return array();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['tematyka']] = $r['SubsubservsSums'];
        }
        return $Sums;
    }

    /** Funkcja pobierająca i tworząca tablicę promowanych usług z ich ID i NAZWĄ
     *
     * @param DBC $dbc
     * @return Service[] $promoted_servs - tablica z numerami i nazwami usług
     * @throws DBQueryException 
     */
    public function getPromotedServs(DBC $dbc, $id_user = NULL) {
        $promoted_servs = '';

        //rozpatrujemy przypadek czy dla konkretnego użytkownika, czy dla wszystkich
        if (is_null($id_user)) {
            $sql = Query::getPromotedServs();
        } else {
            $sql = Query::getPromotedServs($id_user);
        }

        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            $promoted_servs = NULL;
        else {
            $i = 0;
            //tworzymy tablicę z usługami jako obiekty
            while ($row = $result->fetch_assoc()) {
                $promoted_servs[$i] = $this->getServiceFromRow($row);
                $i++;
            }
        }
        return $promoted_servs;
    }

    /** Funkcja pobierająca i tworząca tablicę aktywnych usług danego użytkownika
     *
     * @param DBC $dbc
     * @return Service[] $promoted_servs - tablica z usługami jako obiektami
     * @throws DBQueryException 
     */
    public function getActiveServicesForUser(DBC $dbc, $id_user) {
        $user_servs = '';

        $sql = Query::getAllServices($id_user);
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            $user_servs = NULL; //w przypadku braku usług
        else {
            $i = 0;
            //tworzymy tablicę z usługami jako obiekty
            while ($row = $result->fetch_assoc()) {
                $user_servs[$i] = $this->getServiceFromRow($row);
                $i++;
            }
        }
        return $user_servs;
    }

    //wstawianie usługi do promowanych
    public function insertPromotedService(DBC $dbc, $id_serv, $id_user) {
        $sql = Query::insertPromotedService($id_serv, $id_user);
        $result = $dbc->query($sql);

        return $result;
    }

}

?>
