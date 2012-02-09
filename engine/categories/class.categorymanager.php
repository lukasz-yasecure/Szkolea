<?php

class EmptyList extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}

/**
 * tworzy liste kategorii/obszarow/tematyk na potrzeby left menu
 *
 *  2011-09-28  ostatni wglad
 *  2011-11-04  getListOfAllKOTM dodalem $source zeby nie bralo zawsze z addCommForm
 *  2011-11-08  dodałem metody: getNameOf Kategoria, Obszar, Tematyka, Modul
 */
class CategoryManager {

    /**
     *  Dla LEFT MENU
     *
     * @param DBC $dbc
     * @param string $id
     * @return Categories
     * @throws DBQueryException
     * @throws EmptyList
     */
    public function getCategories(DBC $dbc, $id = null) {
        while (1) { // petla bo jesli okaze sie ze w ID jest jakas dziwna cyfra to jedziemy jeszcze raz tylko z obcietym ID
            $c = new Categories();

            if (is_null($id)) { // nie ma ID czyli normalna strona glowna
                $sql = Query::getAllCats();
                $result = $dbc->query($sql);
                if (!$result)
                    throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                if ($result->num_rows <= 0)
                    throw new EmptyList();
                while ($t = $result->fetch_assoc()) {
                    $c->addK($t['cat'], $t['id_cat']);
                }
            } else { // jest jakies ID
                $ids = explode('_', $id);
                $countIds = count($ids);

                if ($countIds == 1) { // wybrano kategorie
                    $sql = Query::getCatName($ids[0]);
                    $result = $dbc->query($sql);
                    if (!$result)
                        throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                    if ($result->num_rows <= 0) {
                        $id = null;
                        continue;
                    }
                    $row = $result->fetch_assoc();
                    $c->addK($row['cat'], $row['id_cat'], true);

                    $sql = Query::getAllSubcats($ids[0]);
                    $result = $dbc->query($sql);
                    if (!$result)
                        throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                    while ($t = $result->fetch_assoc()) {
                        $c->addO($t['subcat'], $t['id']);
                    }
                } else if ($countIds == 2) { // wybrano obszar
                    $sql = Query::getCatName($ids[0]);
                    $result = $dbc->query($sql);
                    if (!$result)
                        throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                    if ($result->num_rows <= 0) {
                        $id = null;
                        continue;
                    }
                    $row = $result->fetch_assoc();
                    $c->addK($row['cat'], $row['id_cat'], true);

                    $sql = Query::getSubcatName($ids[0] . '_' . $ids[1]);
                    $result = $dbc->query($sql);
                    if (!$result)
                        throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $c->addO($row['subcat'], $row['id'], true);
                    } else {
                        $id = $ids[0];
                        continue;
                    }

                    $sql = Query::getAllSubsubcats($ids[0] . '_' . $ids[1]);
                    $result = $dbc->query($sql);
                    if (!$result)
                        throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                    while ($t = $result->fetch_assoc()) {
                        $c->addT($t['subsubcat'], $t['id']);
                    }
                } else if ($countIds == 3) { // wybrano tematyke
                    $sql = Query::getCatName($ids[0]);
                    $result = $dbc->query($sql);
                    if (!$result)
                        throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                    if ($result->num_rows <= 0) {
                        $id = null;
                        continue;
                    }
                    $row = $result->fetch_assoc();
                    $c->addK($row['cat'], $row['id_cat'], true);

                    $sql = Query::getSubcatName($ids[0] . '_' . $ids[1]);
                    $result = $dbc->query($sql);
                    if (!$result)
                        throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $c->addO($row['subcat'], $row['id'], true);
                    } else {
                        $id = $ids[0];
                        continue;
                    }

                    $sql = Query::getAllSubsubcats($ids[0] . '_' . $ids[1]);
                    $result = $dbc->query($sql);
                    if (!$result)
                        throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                    while ($t = $result->fetch_assoc()) {
                        $act = false;
                        if ($id == $t['id'])
                            $act = true;
                        $c->addT($t['subsubcat'], $t['id'], $act);
                    }
                }
            }

            return $c;
        }
    }

    public function getCategoriesForCatalogs(DBC $dbc) {

        $c = new Categories();


        $sql = Query::getAllCats();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            throw new EmptyList();
        while ($t = $result->fetch_assoc()) {
            $c->addK($t['cat'], $t['id_cat']);
        }

        $sql = Query::getAllSubcats();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            throw new EmptyList();
        while ($t = $result->fetch_assoc()) {
            $c->addO($t['subcat'], $t['id']);
        }

        $sql = Query::getAllSubsubcats();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            throw new EmptyList();
        while ($t = $result->fetch_assoc()) {
            $c->addT($t['subsubcat'], $t['id']);
        }

        return $c;
    }

    /**
     * Lista kategorii na potrzeby formularzy addcomm/addserv
     *
     * @param DBC $dbc
     * @param string $source addCommForm addServForm
     * @return Categories
     * @throws DBQueryException
     */
    public function getListOfAllKOTM(DBC $dbc, $source) {
        $sql = Query::getAllCats();
        $result = $dbc->query($sql);

        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);

        $c = new Categories();

        if ($result->num_rows > 0) {
            while ($t = $result->fetch_assoc()) {
                $c->addK($t['id_cat'], $t['cat']);
            }
        }

        if (!is_null(RFD::get($source, 'cat'))) {
            $sql = Query::getAllSubcats(RFD::get($source, 'cat'));
            $result = $dbc->query($sql);

            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);

            if ($result->num_rows > 0) {
                while ($t = $result->fetch_assoc()) {
                    $c->addO($t['id'], $t['subcat']);
                }
            }
        }

        if (!is_null(RFD::get($source, 'subcat'))) {
            $sql = Query::getAllSubsubcats(RFD::get($source, 'subcat'));
            $result = $dbc->query($sql);

            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);

            if ($result->num_rows > 0) {
                while ($t = $result->fetch_assoc()) {
                    $c->addT($t['id'], $t['subsubcat']);
                }
            }
        }

        if (!is_null(RFD::get($source, 'subsubcat'))) {
            $sql = Query::getAllModuls(RFD::get($source, 'subsubcat'));
            $result = $dbc->query($sql);

            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);

            if ($result->num_rows > 0) {
                while ($t = $result->fetch_assoc()) {
                    $c->addM($t['id'], $t['modul']);
                }
            }
        }

        return $c;
    }

    public function getNameOfKategoria(DBC $dbc, $id) {
        $sql = Query::getCatName($id);
        $res = $dbc->query($sql);
        if (!$res)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        $res = $res->fetch_assoc();
        return $res['cat'];
    }

    public function getNameOfObszar(DBC $dbc, $id) {
        $sql = Query::getSubcatName($id);
        $res = $dbc->query($sql);
        if (!$res)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        $res = $res->fetch_assoc();
        return $res['subcat'];
    }

    public function getNameOfTematyka(DBC $dbc, $id) {
        $sql = Query::getTematykaName($id);
        $res = $dbc->query($sql);
        if (!$res)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        $res = $res->fetch_assoc();
        return $res['subsubcat'];
    }

    public function getNameOfModul(DBC $dbc, $id) {
        $sql = Query::getModulName($id);
        $res = $dbc->query($sql);
        if (!$res)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        $res = $res->fetch_assoc();
        return $res['modul'];
    }

    public function getNameOf(DBC $dbc, $id) {
        $t = explode('_', $id);
        $i = count($t);

        try {
            if ($i == 1)
                $r = $this->getNameOfKategoria($dbc, $id);
            else if ($i == 2)
                $r = $this->getNameOfObszar($dbc, $id);
            else if ($i == 3)
                $r = $this->getNameOfTematyka($dbc, $id);
            else if ($i == 4)
                $r = $this->getNameOfModul($dbc, $id);
        } catch (Exception $e) {
            throw $e;
        }

        return $r;
    }

    public function getNamesOf(DBC $dbc, $id) {
        $t = explode('_', $id);
        $i = count($t);

        $r = array_fill(0, 3, null);

        try {
            $r[0] = $this->getNameOfKategoria($dbc, $t[0]);
            if ($i > 1) {
                $r[1] = $this->getNameOfObszar($dbc, $t[0] . '_' . $t[1]);
                if ($i > 2) {
                    $r[2] = $this->getNameOfTematyka($dbc, $t[0] . '_' . $t[1] . '_' . $t[2]);
                    $r[3] = 3;
                }
                else
                    $r[3] = 2;
            }
            else
                $r[3] = 1;
        } catch (Exception $e) {
            throw $e;
        }

        return $r;
    }

    public function getCatsSums(DBC $dbc) {

        $sql = Query::CatsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            throw new EmptyList();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['kategoria_id']] = $r['CatsSums'];
        }
        return $Sums;
    }

    public function getSubcatsSums(DBC $dbc) {
                $sql = Query::SubcatsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            throw new EmptyList();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['obszar_id']] = $r['SubcatsSums'];
        }
        return $Sums;
        
    }

    public function getSubsubcatsSums(DBC $dbc) {
                $sql = Query::SubsubcatsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            throw new EmptyList();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['tematyka']] = $r['SubsubcatsSums'];
        }
        return $Sums;
        
    }
    

          
        public function getServsSums(DBC $dbc) {

        $sql = Query::ServsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            throw new EmptyList();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['kategoria_id']] = $r['ServsSums'];
        }
        return $Sums;
    }

    public function getSubservsSums(DBC $dbc) {
                $sql = Query::SubservsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            throw new EmptyList();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['obszar_id']] = $r['SubservsSums'];
        }
        return $Sums;
        
    }

    public function getSubsubservsSums(DBC $dbc) {
                $sql = Query::SubsubservsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            throw new EmptyList();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['tematyka']] = $r['SubsubservsSums'];
        }
        return $Sums;
        
    }

}

?>