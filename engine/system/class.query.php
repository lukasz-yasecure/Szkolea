<?php

/**
 * wszystkie zapytania sql uzywane w serwisie
 *
 *  2011-09-21  getUserByEmail() +
 *              getUpdatePassword() +
 *  2011-09-22  storeNewUserInDB(RegisterFormData() +
 *  2011-09-26  getUsersIDByAMK
 *              activateUser
 *              deleteUser
 *              deleteKeyForUser
 *  2011-09-28  escapeUnderscore() +
 *              getAllCats
 *              getAllSubcats
 *              getAllSubsubcats
 *              getAllModuls
 *              getCatName
 *              getSubcatName
 *  2011-10-10  getVerifyUser
 *              storeNewUser... - dzialamy na Password
 *  2011-11-08  dodalem nowe metody do zapisywania zlecenia w bazie
 */
class Query {

    public static function escapeUnderscore($sql) {
        return str_replace('_', '\\_', $sql);
    }

    public static function storeActivationKey($id, $key) {
        $sql = 'INSERT INTO users_activ (`id_user`, `key`, `send_time`) VALUES (' . $id . ', \'' . $key . '\', \'' . time() . '\')';
        return $sql;
    }

    public static function getUser($id) {
        $sql = 'SELECT * FROM users_324 WHERE id_user=' . $id;
        return $sql;
    }

    public static function getUserByEmail($email) {
        $sql = 'SELECT * FROM users_324 WHERE email=\'' . $email . '\'';
        return $sql;
    }

    public static function getUpdatePassword($pass, $id) {
        $sql = 'UPDATE `users_324` SET `pass` = \'' . $pass . '\' WHERE `users_324`.`id_user` = ' . $id;
        return $sql;
    }

    public static function storeNewUserInDB(RegisterFormData $rfd) {
        $sql = 'INSERT INTO `users_324` (
                    `id_user` ,
                    `email` ,
                    `pass` ,
                    `status` ,
                    `date_reg` ,
                    `kind` ,
                    `os_name` ,
                    `os_surname` ,
                    `os_street` ,
                    `os_house_number` ,
                    `os_postcode` ,
                    `os_city` ,
                    `os_woj` ,
                    `os_phone` ,
                    `f_name` ,
                    `f_surname` ,
                    `f_position` ,
                    `f_company` ,
                    `f_street` ,
                    `f_house_number` ,
                    `f_postcode` ,
                    `f_city` ,
                    `f_woj` ,
                    `f_regon` ,
                    `f_nip` ,
                    `f_krs` ,
                    `f_phone`
                    )
                VALUES (
                    NULL ,
                    \'' . $rfd->getEmail() . '\',
                    \'' . $rfd->getPass()->getHash() . '\',
                    \'0\',
                    \'' . time() . '\',
                    \'' . $rfd->getKind() . '\',
                    \'' . $rfd->getOs_name() . '\',
                    \'' . $rfd->getOs_surname() . '\',
                    \'' . $rfd->getOs_street() . '\',
                    \'' . $rfd->getOs_house_number() . '\',
                    \'' . $rfd->getOs_postcode() . '\',
                    \'' . $rfd->getOs_city() . '\',
                    \'' . $rfd->getOs_woj() . '\',
                    \'' . $rfd->getOs_phone() . '\',
                    \'' . $rfd->getF_name() . '\',
                    \'' . $rfd->getF_surname() . '\',
                    \'' . $rfd->getF_position() . '\',
                    \'' . $rfd->getF_company() . '\',
                    \'' . $rfd->getF_street() . '\',
                    \'' . $rfd->getF_house_number() . '\',
                    \'' . $rfd->getF_postcode() . '\',
                    \'' . $rfd->getF_city() . '\',
                    \'' . $rfd->getF_woj() . '\',
                    \'' . $rfd->getF_regon() . '\',
                    \'' . $rfd->getF_nip() . '\',
                    \'' . $rfd->getF_krs() . '\',
                    \'' . $rfd->getF_phone() . '\'
                )';
        return str_replace('\'\'', 'NULL', $sql);
    }

    public static function updateProfileData(ProfileEditFormData $pefd, $u) {
        $sql = 'UPDATE `users_324` SET 
                    `os_name` = \'' . $pefd->getOs_name() . '\',
                    `os_surname` = \'' . $pefd->getOs_surname() . '\',
                    `os_street` =  \'' . $pefd->getOs_street() . '\',
                    `os_house_number` = \'' . $pefd->getOs_house_number() . '\',
                    `os_postcode` = \'' . $pefd->getOs_postcode() . '\',
                    `os_city` =  \'' . $pefd->getOs_city() . '\',
                    `os_woj` = \'' . $pefd->getOs_woj() . '\',
                    `os_phone` = \'' . $pefd->getOs_phone() . '\',
                    `f_name` =  \'' . $pefd->getF_name() . '\',
                    `f_surname` = \'' . $pefd->getF_surname() . '\',
                    `f_position` = \'' . $pefd->getF_position() . '\',
                    `f_company` = \'' . $pefd->getF_company() . '\',
                    `f_street` =  \'' . $pefd->getF_street() . '\',
                    `f_house_number` = \'' . $pefd->getF_house_number() . '\',
                    `f_postcode` = \'' . $pefd->getF_postcode() . '\',
                    `f_city` = \'' . $pefd->getF_city() . '\',
                    `f_woj` = \'' . $pefd->getF_woj() . '\',
                    `f_regon` = \'' . $pefd->getF_regon() . '\',
                    `f_nip` = \'' . $pefd->getF_nip() . '\',
                    `f_krs` = \'' . $pefd->getF_krs() . '\',
                    `f_phone` = \'' . $pefd->getF_phone() . '\'
                    WHERE `users_324`.`id_user` = ' . $u->getId_user();
        return str_replace('\'\'', 'NULL', $sql);
    }

    public static function saveNewCommisionInDB(Commision $c) {
        $sql = 'INSERT INTO `commisions` (
                `id_comm` ,
                `id_user` ,
                `date_add` ,
                `date_end` ,
                `long` ,
                `days` ,
                `date_a` ,
                `date_b` ,
                `date_c` ,
                `date_d` ,
                `expire` ,
                `place` ,
                `woj` ,
                `cena_min` ,
                `cena_max` ,
                `parts_count` ,
                `parts` ,
                `kotm` ,
                `kategoria_name` ,
                `kategoria_id` ,
                `obszar_name`,
                `obszar_id` ,
                `tematyka_name` ,
                `tematyka` ,
                `moduly_names`
                )
                VALUES (
                    NULL,
                    \'' . $c->getId_user() . '\',
                    \'' . $c->getDate_add() . '\',
                    \'' . $c->getDate_end() . '\',
                    \'' . $c->getLong() . '\',
                    \'' . $c->getDays() . '\',
                    \'' . $c->getDate_a() . '\',
                    \'' . $c->getDate_b() . '\',
                    \'' . $c->getDate_c() . '\',
                    \'' . $c->getDate_d() . '\',
                    \'' . $c->getExpire() . '\',
                    \'' . $c->getPlace() . '\',
                    \'' . $c->getWoj() . '\',
                    \'' . $c->getCena_min() . '\',
                    \'' . $c->getCena_max() . '\',
                    \'' . $c->getParts_count() . '\',
                    \'' . $c->getParts() . '\',
                    \'' . $c->getKotm() . '\',
                    \'' . $c->getKategoria_name() . '\',
                    \'' . $c->getCat() . '\',
                    \'' . $c->getObszar_name() . '\',
                    \'' . $c->getSubcat() . '\',
                    \'' . $c->getTematyka_name() . '\',
                    \'' . $c->getTematyka() . '\',
                    \'' . $c->getModuly_names() . '\'
                )';

        return str_replace('\'\'', 'NULL', $sql);
    }

    public static function saveModulsForCommision(Commision $c) {
        $sql = 'INSERT INTO `comm_moduls` (
            `id_comm` ,
            `id_mod`
            )
            VALUES ';

        $moduly = $c->getModuly();
        $id = $c->getId_comm();
        if (is_array($moduly)) {
            foreach ($moduly as $m) {
                $sql.= '(\'' . $id . '\', \'' . $m . '\'),';
            }
        }

        return substr($sql, 0, -1);
    }

    public static function saveParticipantsForCommision(Commision $c) {
        $sql = 'INSERT INTO `commisions_group` (
            `id_comm` ,
            `id_user` ,
            `date_add`
            )
            VALUES ';

        $parts_count = $c->getParts_count();
        $id_comm = $c->getId_comm();
        $id_user = $c->getId_user();

        for ($i = 0; $i < $parts_count; $i++) {
            $sql.= '(\'' . $id_comm . '\', \'' . $id_user . '\', \'' . time() . '\'),';
        }

        return substr($sql, 0, -1);
    }

    public static function saveNewServiceInDB(Service $s) {
        $sql = 'INSERT INTO `services` (
                `id_serv` ,
                `id_user` ,
                `date_add` ,
                `date_end` ,
                `name` ,
                `program` ,
                `date_a` ,
                `date_b` ,
                `place` ,
                `woj` ,
                `cena` ,
                `cena_` ,
                `desc` ,
                `mail` ,
                `phone` ,
                `contact` ,
                `kotm` ,
                `kategoria_name` ,
                `kategoria_id` ,
                `obszar_name` ,
                `obszar_id` ,
                `tematyka_name` ,
                `tematyka` ,
                `moduly_names`
                )
                VALUES (
                    NULL,
                    \'' . $s->getId_user() . '\',
                    \'' . $s->getDate_add() . '\',
                    \'' . $s->getDate_end() . '\',
                    \'' . $s->getName() . '\',
                    \'' . nl2br($s->getProgram()) . '\',
                    \'' . $s->getDate_a() . '\',
                    \'' . $s->getDate_b() . '\',
                    \'' . $s->getPlace() . '\',
                    \'' . $s->getWoj() . '\',
                    \'' . $s->getCena() . '\',
                    \'' . $s->getCena_() . '\',
                    \'' . nl2br($s->getDesc()) . '\',
                    \'' . $s->getMail() . '\',
                    \'' . $s->getPhone() . '\',
                    \'' . $s->getContact() . '\',
                    \'' . $s->getKotm() . '\',
                    \'' . $s->getKategoria_name() . '\',
                          \'' . $s->getCat() . '\',
                    \'' . $s->getObszar_name() . '\',
                          \'' . $s->getSubcat() . '\',
                    \'' . $s->getTematyka_name() . '\',
                    \'' . $s->getTematyka() . '\',
                    \'' . $s->getModuly_names() . '\'
                )';
        return str_replace('\'\'', 'NULL', $sql);
    }

    public static function saveModulsForService(Service $s) {
        $sql = 'INSERT INTO `serv_moduls` (
            `id_serv` ,
            `id_mod`
            )
            VALUES ';

        $moduly = $s->getModuly();
        $id = $s->getId_serv();
        if (is_array($moduly)) {
            foreach ($moduly as $m) {
                $sql.= '(\'' . $id . '\', \'' . $m . '\'),';
            }
        }

        return substr($sql, 0, -1);
    }

    public static function getUsersIDByAMK($key_code) {
        $sql = 'SELECT id_user, send_time FROM `users_activ` WHERE `key`=\'' . $key_code . '\'';
        return $sql;
    }

    public static function activateUser($user_id) {
        $sql = 'UPDATE `users_324` SET `status` = \'1\' WHERE `users_324`.`id_user` = ' . $user_id;
        return $sql;
    }

    public static function deleteUser($user_id) {
        $sql = 'DELETE FROM `users_324` WHERE `users_324`.`id_user` = ' . $user_id;
        return $sql;
    }

    public static function deleteKeyForUser($user_id) {
        $sql = 'DELETE FROM `users_activ` WHERE `users_activ`.`id_user` = ' . $user_id;
        return $sql;
    }

    public static function getAllCats() {
        $sql = 'SELECT * FROM `cats_569` ORDER BY cat ASC';
        return $sql;
    }

    public static function getAllSubcats($id = '') {
        $sql = '';


        if ($id != '') {
            $id = Query::escapeUnderscore($id . '_');
            $sql = 'SELECT * FROM `subcats_569` WHERE `id` LIKE \'' . $id . '%\' ORDER BY subcat ASC';
        }
        else
            $sql = 'SELECT * FROM `subcats_569` ORDER BY id ASC';

        return $sql;
    }

    public static function getAllSubsubcats($id = '') {
        $sql = '';
        if ($id != '') {
            $id = Query::escapeUnderscore($id . '_');
            $sql = 'SELECT * FROM `subsubcats_569` WHERE `id` LIKE \'' . $id . '%\' ORDER BY subsubcat ASC';
        }
        else
            $sql = 'SELECT * FROM `subsubcats_569` ORDER BY id ASC';

        return $sql;
    }

    public static function getAllModuls($id = '') {
        $sql = '';
        $id = Query::escapeUnderscore($id . '_');

        if ($id != '')
            $sql = 'SELECT * FROM `moduls_569` WHERE `id` LIKE \'' . $id . '%\' ORDER BY modul ASC';
        else
            $sql = 'SELECT * FROM `moduls_569` ORDER BY id ASC';

        return $sql;
    }

    public static function getCatName($id) {
        $sql = 'SELECT * FROM `cats_569` WHERE `id_cat`=' . $id;
        return $sql;
    }

    public static function getSubcatName($id) {
        $sql = 'SELECT * FROM `subcats_569` WHERE `id`=\'' . $id . '\'';
        return $sql;
    }

    public static function getTematykaName($id) {
        $sql = 'SELECT * FROM `subsubcats_569` WHERE `id`=\'' . $id . '\'';
        return $sql;
    }

    public static function getModulName($id) {
        $sql = 'SELECT * FROM `moduls_569` WHERE `id`=\'' . $id . '\'';
        return $sql;
    }

    public static function getVerifyUser($email, $pass) {
        $sql = 'SELECT * FROM `users_324` WHERE email=\'' . $email . '\' AND pass=\'' . $pass . '\'';
        return $sql;
    }

    public static function getAllCommisions() {
        $sql = 'SELECT * FROM `commisions` WHERE date_end > ' . time() . ' ORDER BY date_end ASC';
        return $sql;
    }

    public static function getCommisionsForSearch(Search $s) {
        $sql = 'SELECT * FROM `commisions` WHERE';

        if (!is_null($s->getT()))
            $sql.= ' `tematyka` LIKE \'' . $s->getT() . '\' AND';
        else if (!is_null($s->getO()))
            $sql.= ' `tematyka` LIKE \'' . $s->getO() . '%\' AND';
        else if (!is_null($s->getK()))
            $sql.= ' `tematyka` LIKE \'' . $s->getK() . '%\' AND';
        if (!is_null($s->getWoj()))
            $sql.= ' `woj`=\'' . $s->getWoj() . '\' AND';
        if (!is_null($s->getPlace()))
            $sql.= ' `place`=\'' . $s->getPlace() . '\' AND';
        if (!is_null($s->getCena_min()))
            $sql.= ' `cena_max`>=\'' . $s->getCena_min() . '\' AND';
        if (!is_null($s->getCena_max()))
            $sql.= ' `cena_max`<=\'' . $s->getCena_max() . '\' AND';
        if (!is_null($s->getData_a()) && !is_null($s->getData_b()))
            $sql.= ' (`date_a`>=\'' . $s->getData_a() . '\' AND `date_b`<=\'' . $s->getData_b() . '\') OR (`date_c`>=\'' . $s->getData_a() . '\' AND `date_d`<=\'' . $s->getData_b() . '\') AND';
        else if (!is_null($s->getData_a()))
            $sql.= ' (`date_a`>=\'' . $s->getData_a() . '\' OR `date_c`>=\'' . $s->getData_a() . '\') AND';
        else if (!is_null($s->getData_b()))
            $sql.= ' (`date_b`<=\'' . $s->getData_b() . '\' OR `date_d`>=\'' . $s->getData_b() . '\') AND';
        if (!is_null($s->getWord()))
            $sql.= ' `kotm` LIKE \'%' . $s->getWord() . '%\' AND';

        $sql.= ' date_end > ' . time() . ' ORDER BY date_end ASC';
        return $sql;
    }

    public static function getServicesForSearch(Search $s) {
        $sql = 'SELECT * FROM `services` WHERE';

        if (!is_null($s->getT()))
            $sql.= ' `tematyka` LIKE \'' . $s->getT() . '\' AND';
        else if (!is_null($s->getO()))
            $sql.= ' `tematyka` LIKE \'' . $s->getO() . '%\' AND';
        else if (!is_null($s->getK()))
            $sql.= ' `tematyka` LIKE \'' . $s->getK() . '%\' AND';
        if (!is_null($s->getWoj()))
            $sql.= ' `woj`=\'' . $s->getWoj() . '\' AND';
        if (!is_null($s->getPlace()))
            $sql.= ' `place`=\'' . $s->getPlace() . '\' AND';
        if (!is_null($s->getCena_min()))
            $sql.= ' `cena`>=\'' . $s->getCena_min() . '\' AND';
        if (!is_null($s->getCena_max()))
            $sql.= ' `cena`<=\'' . $s->getCena_max() . '\' AND';
        if (!is_null($s->getData_a()))
            $sql.= ' `date_a`>=\'' . $s->getData_a() . '\' AND';
        if (!is_null($s->getData_b()))
            $sql.= ' `date_b`<=\'' . $s->getData_b() . '\' AND';
        if (!is_null($s->getWord()))
            $sql.= ' `kotm` LIKE \'%' . $s->getWord() . '%\' OR `desc` LIKE \'%' . $s->getWord() . '%\' OR `name` LIKE \'%' . $s->getWord() . '%\' AND';

        $sql.= ' date_end > ' . time() . ' ORDER BY date_end ASC';
        return $sql;
    }

    public static function getCommisionsForLeftMenu(Search $s) {
        $sql = 'SELECT * FROM `commisions` WHERE';

        if (!is_null($s->getKot_id()))
            $sql.= ' `tematyka` LIKE \'' . $s->getKot_id() . '%\' AND';

        $sql.= ' date_end > ' . time() . ' ORDER BY date_end ASC';
        return $sql;
    }

    public static function getServicesForLeftMenu(Search $s) {
        $sql = 'SELECT * FROM `services` WHERE';

        if (!is_null($s->getKot_id()))
            $sql.= ' `tematyka` LIKE \'' . $s->getKot_id() . '%\' AND';

        $sql.= ' date_end > ' . time() . ' ORDER BY date_end ASC';
        return $sql;
    }

    public static function getAllServices() {
        $sql = 'SELECT * FROM `services` WHERE date_end > ' . time() . ' ORDER BY date_end ASC';
        return $sql;
    }

    public static function getCommision($id) {
        $sql = 'SELECT * FROM `commisions` WHERE `id_comm`=' . $id;
        return $sql;
    }

    public static function getService($id) {
        $sql = 'SELECT * FROM `services` WHERE `id_serv`=' . $id;
        return $sql;
    }

    public static function getOfferCountForCommision($id) {
        $sql = 'SELECT COUNT(*) ile FROM `commisions_ofe` WHERE `id_comm`=' . $id;
        return $sql;
    }

    public static function getOfferForCommAll($id_comm) {
        $sql = 'SELECT * FROM `commisions_ofe` WHERE `id_comm`= ' . $id_comm;
        return $sql;
    }

    public static function getOfferForComm($id) {
        $sql = 'SELECT * FROM `commisions_ofe` WHERE `id_comm`= ' . $id . ' AND `status`!= 3';
        return $sql;
    }

    public static function getObserveAddForComm($uid, $id) {
        return 'INSERT INTO `observe_comms` (`id_user`, `id_obs`) VALUES (\'' . $uid . '\', \'' . $id . '\')';
    }

    public static function getObserveAddForCommKOT($uid, $id) {
        return 'INSERT INTO `observe_comms_kot` (`id_user`, `id_obs`) VALUES (\'' . $uid . '\', \'' . $id . '\')';
    }

    public static function getObserveAddForServKOT($uid, $id) {
        return 'INSERT INTO `observe_servs_kot` (`id_user`, `id_obs`) VALUES (\'' . $uid . '\', \'' . $id . '\')';
    }

    public static function getObserveCommUsers($id) {
        $sql = 'SELECT * FROM observe_comms WHERE `id_obs`=' . $id;
        return $sql;
    }

    public static function getGroupCommUsers($id) {
        $sql = 'SELECT * FROM commisions_group WHERE `id_comm`=' . $id;
        return $sql;
    }

    public static function getOfferAdd(Offer $o) {
        $sql = "INSERT INTO `commisions_ofe` (
                    `id_ofe` ,
                    `id_comm` ,
                    `id_user` ,
                    `date_add` ,
                    `cena` ,
                    `cenax` ,
                    `rozl` ,
                    `inne` ,
                    `ile_kaw` ,
                    `date_a` ,
                    `date_b`
                )
                VALUES (
                    NULL ,
                    '" . $o->getId_comm() . "',
                    '" . $o->getId_user() . "',
                    '" . $o->getDate_add() . "',
                    '" . $o->getCena() . "',
                    '" . $o->getCenax() . "',
                    '" . $o->getRozl() . "',
                    '" . (!is_null($o->getInne()) ? implode(';', $o->getInne()) : "NULL") . "',
                    '" . (!is_null($o->getIle_kaw()) ? $o->getIle_kaw() : "NULL") . "',
                    '" . UF::date2timestamp($o->getDate_a()) . "',
                    '" . UF::date2timestamp($o->getDate_b()) . "'
                )";

        return str_replace("'NULL'", 'NULL', $sql);
    }

    public static function getOfferAccept($ofe) {
        return 'SELECT * FROM `commisions_ofe` WHERE `id_ofe` = ' . $ofe; // pobierane dane wybranej oferty
    }

    public static function getOfferAcceptNo($ofe) {
        return 'UPDATE `commisions_ofe` SET `status`=3 WHERE `id_ofe` = ' . $ofe; // odnowienie danych odrzuconych ofert
    }

    public static function getOfferAcceptYes($ofe) {
        return 'UPDATE `commisions_ofe` SET `status` = 2 WHERE `id_ofe` = ' . $ofe; // odnowienie danych wybranej oferty
    }

    public static function getOfferAcceptYesAfter($id, $ofe) {
        return 'SELECT * FROM `commisions_ofe` WHERE `id_comm` = ' . $id . ' AND `id_ofe` != ' . $ofe; // wyświetlane wszystkie oferty z wyjątkiem wybranej 
    }

    public static function CatsSums() {
        $sql = 'SELECT kategoria_id, COUNT( commisions.kategoria_id ) AS CatsSums FROM commisions GROUP BY kategoria_id';
        return $sql;
    }

    public static function SubcatsSums() {
        $sql = 'SELECT obszar_id, COUNT( commisions.obszar_id ) AS SubcatsSums FROM commisions GROUP BY obszar_id';
        return $sql;
    }

    public static function SubsubcatsSums() {
        $sql = 'SELECT tematyka, COUNT( commisions.tematyka ) AS SubsubcatsSums FROM commisions GROUP BY tematyka';
        return $sql;
    }

    public static function ServsSums() {
        $sql = 'SELECT kategoria_id, COUNT( services.kategoria_id ) AS ServsSums FROM services GROUP BY kategoria_id';
        return $sql;
    }

    public static function SubservsSums() {
        $sql = 'SELECT obszar_id, COUNT( services.obszar_id ) AS SubservsSums FROM services GROUP BY obszar_id';
        return $sql;
    }

    public static function SubsubservsSums() {
        $sql = 'SELECT tematyka, COUNT( services.tematyka ) AS SubsubservsSums FROM services GROUP BY tematyka';
        return $sql;
    }

    public static function CommListForAdmin() {
        $sql = 'SELECT C.id_comm AS id_zlec, U.os_name AS imie, U.os_surname AS nazwisko, U.id_user AS id_usera, COUNT(CG.id_user) AS ilosc_dop, U2.os_name AS imie_dop, U2.os_surname AS nazwisko_dop, U2.id_user AS id_dop
FROM commisions C INNER JOIN users_324 U ON C.id_user = U.id_user INNER JOIN commisions_group CG ON CG.id_comm = C.id_comm INNER JOIN users_324 U2 ON U2.id_user= CG.id_user GROUP BY CG.id_comm, CG.id_user';
        return $sql;
    }

    public static function deleteComm($from, $id_comm) {
        $sql = 'DELETE FROM ' . $from . ' WHERE ' . $from . '.id_comm = ' . $id_comm;
        return $sql;
    }

    public static function setUserBanned($id_user) {
        $sql = 'UPDATE users_324 SET status = 2 WHERE users_324.id_user =' . $id_user;
        return $sql;
    }

    public static function getActivePackagesForUser($id_user) {
        $sql = 'SELECT users_packages.* , packages.nazwa, packages.cena_brutto, packages.wizyt_znaki, packages.wizyt_www, packages.wizyt_logo, packages.wizyt_wyrozn, packages.baner, packages.uslugi_wyrozn, packages.mailing, packages.waznosc FROM users_packages LEFT JOIN packages ON users_packages.id_pakietu = packages.id_pakietu WHERE id_user =' . $id_user . ' AND date_end < NOW() ORDER BY date_end';
        return $sql;
    }

    public static function getPackage($id_pakietu) {
        $sql = 'SELECT * FROM packages WHERE id_pakietu =' . $id_pakietu;
        return $sql;
    }

    public static function setPackageForUser($id_user, $pakiet) {
        //wstawienie nulli za puste pola
        if (!(Valid::isNatural($pakiet['uslugi'])))
            $pakiet['uslugi'] = 'NULL';
        if (!(Valid::isNatural($pakiet['oferty'])))
            $pakiet['oferty'] = 'NULL';


        $sql = 'INSERT INTO `users_packages` (`id_user`, `id_pakietu`, `uslugi`, `oferty`, `date_begin`, `date_end`, `id_faktury`, `id_proforma`) VALUES (' . $id_user . ', ' . $pakiet['id_pakietu'] . ', ' . $pakiet['uslugi'] . ', ' . $pakiet['oferty'] . ', ' . time() . ', ' . ( time() + $pakiet['waznosc'] * 86400 ) . ', 66, 66)';
        return $sql;
    }

    public static function decreaseServicesForUser($id_user, $id_pakietu) {
        $sql = 'UPDATE users_packages SET uslugi = (uslugi-1) WHERE users_packages.id_user =' . $id_user . ' AND users_packages.id_pakietu =' . $id_pakietu;
        return $sql;
    }

    public static function decreaseCommsForUser($id_user, $id_pakietu) {
        $sql = 'UPDATE users_packages SET oferty = (oferty-1) WHERE users_packages.id_user =' . $id_user . ' AND users_packages.id_pakietu =' . $id_pakietu;
        return $sql;
    }

    public static function setCardForUser($id_user, $opis, $www) {

        $sql = 'UPDATE users_wizyts SET opis ="' . $opis . '", www ="' . $www . '" WHERE users_wizyts.id_user =' . $id_user;
        return $sql;
    }

    public static function setNewCardForUser($id_user, $opis, $www, $logo) {

        $sql = 'INSERT INTO users_wizyts (`id_user`, `opis`, `www`, `logo`) VALUES ("' . $id_user . '", "' . $opis . '", "' . $www . '", "' . $logo . '")';
        return $sql;
    }

    public static function setLogoForUser($id_user, $logo) {

        $sql = 'UPDATE users_wizyts SET logo ="' . $logo . '" WHERE users_wizyts.id_user =' . $id_user;
        return $sql;
    }

    public static function getCardForUser($id_user) {

        $sql = 'SELECT * FROM users_wizyts WHERE id_user =' . $id_user;
        return $sql;
    }

    public static function getCronComm() {
        return 'SELECT * FROM commisions WHERE finished=0 AND date_end < ' . time();
    }

    public static function setCronFinished($id_comm) {
        return 'UPDATE commisions SET finished=1 WHERE id_comm=' . $id_comm;
    }

    public static function getProfileNamesForCatalog($fraza) {

        $sql = 'SELECT users_324.id_user, (CASE WHEN f_company IS NULL THEN os_surname ELSE f_company END) AS nazwa, users_wizyts.opis, users_wizyts.www, users_wizyts.logo FROM users_324 LEFT JOIN users_packages ON users_324.id_user=users_packages.id_user LEFT JOIN users_wizyts ON users_324.id_user=users_wizyts.id_user WHERE kind = "D" AND (f_company LIKE "' . $fraza . '%" OR os_surname LIKE "' . $fraza . '%") ORDER BY Nazwa';
        return $sql;
    }

    public static function getProfilePremiumCardsForCatalog() {

        $sql = 'SELECT users_324.id_user, (CASE WHEN f_company IS NULL THEN os_surname ELSE f_company END) AS nazwa, users_wizyts.opis, users_wizyts.www, users_wizyts.logo FROM users_324 LEFT JOIN users_packages ON users_324.id_user=users_packages.id_user LEFT JOIN users_wizyts ON users_324.id_user=users_wizyts.id_user WHERE kind = "D" AND users_packages.date_end >' . time() . ' AND users_packages.id_pakietu=5 ORDER BY Nazwa';
        return $sql;
    }

}

?>
