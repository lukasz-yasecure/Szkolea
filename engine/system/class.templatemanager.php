<?php

/**
 * Zwraca nowe szablony uzupelnione odpowiednimi danymi.
 *
 *  2011-09-21  getRemindFormTemplate() +
 *              getRemindMailTemplate() +
 *              getPasswordChangeFormTemplate() +
 *  2011-09-22  getRegisterFormTemplate() + podmianki zmiennych
 *  2011-09-26  getCommGroupJoinFormTemplate()
 *  2011-09-27  getSearchTemplate()
 *              getLoginbarTemplate()
 *              getUserbarTemplate()
 *              getIndexTemplate()
 *  2011-09-28  getLeftMenuTemplate()
 *              getLeftMenuListTemplate
 *  2011-09-29  getResultsListTemplate
 *              getResultsTemplate
 *  2011-10-10  getLoginFormTemplate
 *  2011-10-11  getLoginFormTemplate + BackURL
 *              getAddCommFormTemplate - bez RFD
 *  2011-10-31  getAddCommFormTemplate + setDays setLong
 *  2011-11-04  getAddServFormTemplate
 *  2011-11-08  poprawilem getResultsListTemplate na razie dla Commisions
 *  2011-11-10  wyswietlaja sie zlecenie i uslugi
 */
class TemplateManager
{
    /**
     *
     * @param System $sys
     * @param ActivationLink $al
     * @return ActivationMailTemplate
     * @throws NoTemplateFile jesli nie ma pliku html z szablonem
     */
    public function getActivationMailTemplate(System $sys, ActivationLink $al)
    {
        $path = $sys->getTemplateActivationMailPath();
        
        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $content = file_get_contents($path);
        $amt = new ActivationMailTemplate($content);
        $amt->setLink($al->getLink());
        return $amt;
    }

    /**
     *
     * @param System $sys
     * @return RemindFormTemplate
     * @throws NoTemplateFile
     */
    public function getRemindFormTemplate(System $sys)
    {
        $path = $sys->getTemplateRemindFormPath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $rft = new RemindFormTemplate(file_get_contents($path));
        return $rft;
    }

    /**
     *
     * @param System $sys
     * @param RemindLink $rl
     * @return RemindMailTemplate
     * @throws NoTemplateFile
     */
    public function getRemindMailTemplate(System $sys, RemindLink $rl)
    {
        $path = $sys->getTemplateRemindMailPath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $content = file_get_contents($path);
        $rmt = new RemindMailTemplate($content);
        $rmt->setLink($rl->getLink());
        return $rmt;
    }

    /**
     *
     * @param System $sys
     * @param string $content
     * @param string $bfec
     * @param string skrypty
     * @return MainTemplate
     * @throws NoTemplateFile
     */
    public function getMainTemplate(System $sys, $content, $bfec, $skrypty = '')
    {
        $path = $sys->getTemplateMainPath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $mt = new MainTemplate(file_get_contents($path));
        $mt->setMain($content);
        $mt->setBFEC($bfec);
        $mt->setSkrypty($skrypty);
        $mt->setFooter(file_get_contents('view/html/foot.html'));
        return $mt;
    }

    /**
     *
     * @param System $sys
     * @return PasswordChangeFormTemplate
     */
    public function getPasswordChangeFormTemplate(System $sys)
    {
        $path = $sys->getTemplatePasswordChangePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $pcft = new PasswordChangeFormTemplate(file_get_contents($path));
        return $pcft;
    }

    /**
     *
     * @param System $sys
     * @return RegisterFormTemplate
     * @throws NoTemplateFile
     */
    public function getRegisterFormTemplate(System $sys)
    {
        $path = $sys->getTemplateRegisterFormPath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $rft = new RegisterFormTemplate(file_get_contents($path));
        $rft->setEmail(RFD::get('regForm', 'email'));
        $rft->setUkind(RFD::get('regForm', 'ukind'));
        $rft->setOs_name(RFD::get('regForm', 'os_name'));
        $rft->setOs_surname(RFD::get('regForm', 'os_surname'));
        $rft->setOs_street(RFD::get('regForm', 'os_street'));
        $rft->setOs_house_number(RFD::get('regForm', 'os_house_number'));
        $rft->setOs_postcode(RFD::get('regForm', 'os_postcode'));
        $rft->setOs_city(RFD::get('regForm', 'os_city'));
        $rft->setOs_phone(RFD::get('regForm', 'os_phone'));
        $rft->setF_name(RFD::get('regForm', 'f_name'));
        $rft->setF_surname(RFD::get('regForm', 'f_surname'));
        $rft->setF_company(RFD::get('regForm', 'f_company'));
        $rft->setF_position(RFD::get('regForm', 'f_position'));
        $rft->setF_street(RFD::get('regForm', 'f_street'));
        $rft->setF_house_number(RFD::get('regForm', 'f_house_number'));
        $rft->setF_postcode(RFD::get('regForm', 'f_postcode'));
        $rft->setF_city(RFD::get('regForm', 'f_city'));
        $rft->setF_phone(RFD::get('regForm', 'f_phone'));
        $rft->setF_nip(RFD::get('regForm', 'f_nip'));
        $rft->setF_regon(RFD::get('regForm', 'f_regon'));
        $rft->setF_krs(RFD::get('regForm', 'f_krs'));
        $rft->setOs_woj(RFD::get('regForm', 'os_woj'));
        $rft->setF_woj(RFD::get('regForm', 'f_woj'));
        return $rft;
    }

    /**
     *
     * @param System $sys
     * @return ProfileEditFormTemplate
     * @throws NoTemplateFile
     */
    public function getProfileEditFormTemplate(System $sys,User $gu,User $u)
    {
        $path = Pathes::getPathTemplateProfileEditForm();
        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');
        $pft = new ProfileEditFormTemplate(file_get_contents($path));
        // osobiste
        is_null($os_name=RFD::get('profEditForm','os_name'))?$pft->setOs_name($os_name=$gu->getOs_name()):$pft->setOs_name($os_name);
        is_null($os_surname=RFD::get('profEditForm','os_surname'))?$pft->setOs_surname($os_surname=$gu->getOs_surname()):$pft->setOs_surname($os_surname);
        is_null($os_street=RFD::get('profEditForm','os_street'))?$pft->setOs_street($os_street=$gu->getOs_street()):$pft->setOs_street($os_street);
        is_null($os_house_number=RFD::get('profEditForm','os_house_number'))?$pft->setOs_house_number($os_house_number=$gu->getOs_house_number()):$pft->setOs_house_number($os_house_number);
        is_null($os_postcode=RFD::get('profEditForm','os_postcode'))?$pft->setOs_postcode($os_postcode=$gu->getOs_postcode()):$pft->setOs_postcode($os_postcode);
        is_null($os_city=RFD::get('profEditForm','os_city'))?$pft->setOs_city($os_city=$gu->getOs_city()):$pft->setOs_city($os_city);
        is_null($os_woj=RFD::get('profEditForm','os_woj'))?$pft->setOs_woj($os_woj=$gu->getOs_woj()):$pft->setOs_woj($os_woj);
        is_null($os_phone=RFD::get('profEditForm','os_phone'))?$pft->setOs_phone($os_phone=$gu->getOs_phone()):$pft->setOs_phone($os_phone);
        // firmowe
        is_null($f_name=RFD::get('profEditForm','f_name'))?$pft->setF_name($f_name=$gu->getF_name()):$pft->setF_name($f_name);
        is_null($f_surname=RFD::get('profEditForm','f_surname'))?$pft->setF_surname($f_surname=$gu->getF_surname()):$pft->setF_surname($f_surname);
        is_null($f_position=RFD::get('profEditForm','f_position'))?$pft->setF_position($f_position=$gu->getF_position()):$pft->setF_position($f_position);
        is_null($f_company=RFD::get('profEditForm','f_company'))?$pft->setF_company($f_company=$gu->getF_company()):$pft->setF_company($f_company);
        is_null($f_street=RFD::get('profEditForm','f_street'))?$pft->setF_street($f_street=$gu->getF_street()):$pft->setF_street($f_street);
        is_null($f_house_number=RFD::get('profEditForm','f_house_number'))?$pft->setF_house_number($f_house_number=$gu->getF_house_number()):$pft->setF_house_number($f_house_number);
        is_null($f_postcode=RFD::get('profEditForm','f_postcode'))?$pft->setF_postcode($f_postcode=$gu->getF_postcode()):$pft->setF_postcode($f_postcode);
        is_null($f_city=RFD::get('profEditForm','f_city'))?$pft->setF_city($f_postcode=$gu->getF_city()):$pft->setF_city($f_city);
        is_null($f_woj=RFD::get('profEditForm','f_woj'))?$pft->setF_woj($f_woj=$gu->getF_woj()):$pft->setF_woj($f_woj);
        is_null($f_phone=RFD::get('profEditForm','f_phone'))?$pft->setF_phone($f_phone=$gu->getF_phone()):$pft->setF_phone($f_phone);
        is_null($f_regon=RFD::get('profEditForm','f_regon'))?$pft->setF_regon($f_regon=$gu->getF_regon()):$pft->setF_regon($f_regon);
        is_null($f_nip=RFD::get('profEditForm','f_nip'))?$pft->setF_nip($f_nip=$gu->getF_nip()):$pft->setF_nip($f_nip);
        is_null($f_krs=RFD::get('profEditForm','f_krs'))?$pft->setF_krs($f_krs=$gu->getF_krs()):$pft->setF_krs($f_krs);
        return $pft;
    }

    /**
     *
     * @param System $sys
     * @return CommGroupJoinFormTemplate
     * @throws NoTemplateFile
     */
    public function getCommGroupJoinFormTemplate(System $sys, $grupa)
    {
        $path = $sys->getCommGroupJoinFormTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $cgjft = new CommGroupJoinFormTemplate(file_get_contents($path));
        $cgjft->setGrupa($grupa);
        return $cgjft;
    }

    /**
     *
     * @param System $sys
     * @param string $bar_content
     * @return SearchTemplate
     * @throws NoTemplateFile
     */
    public function getSearchTemplate(System $sys, Categories $c, $bar_content)
    {
        $path = $sys->getSearchTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $st = new SearchTemplate(file_get_contents($path));
        $st->setBar($bar_content);
        $st->setCategories($c, RFD::get('searchForm', 'cat'));
        $st->setObszary($c, RFD::get('searchForm', 'subcat'));
        $st->setTematyki($c, RFD::get('searchForm', 'subsubcat'));
        $st->setWoj(RFD::get('searchForm', 'woj'));
        $st->setDates(RFD::get('searchForm', 'date_a'), RFD::get('searchForm', 'date_b'));
        $st->setPlace(RFD::get('searchForm', 'place'));
        $st->setCeny(RFD::get('searchForm', 'cena_min'), RFD::get('searchForm', 'cena_max'));
        $st->setWord(RFD::get('searchForm', 'word'));
        $st->setWhat(RFD::get('searchForm', 'what'));
        return $st;
    }

    /**
     *
     * @param System $sys
     * @return LoginbarTemplate
     * @throws NoTemplateFile
     */
    public function getLoginbarTemplate(System $sys)
    {
        $path = $sys->getLoginbarTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $lt = new LoginbarTemplate(file_get_contents($path));
        return $lt;
    }

    /**
     *
     * @param System $sys
     * @param string $name
     * @return UserbarTemplate
     * @throws NoTemplateFile
     */
    public function getUserbarTemplate(System $sys, $name)
    {
        $path = $sys->getUserbarTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $ut = new UserbarTemplate(file_get_contents($path));
        $ut->setUsername($name);
        return $ut;
    }

    /**
     *
     * @param System $sys
     * @param string $search
     * @param string $left_menu
     * @param string $results
     * @return IndexTemplate
     * @throws NoTemplateFile
     */
    public function getIndexTemplate(System $sys, $search, $left_menu, $results)
    {
        $path = $sys->getIndexTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $it = new IndexTemplate(file_get_contents($path));
        $it->setSearch($search);
        $it->setLeftMenu($left_menu);
        $it->setResults($results);
        return $it;
    }

    /**
     *
     * @param System $sys
     * @param CategoryManager $cm
     * @param LeftMenuListTemplate $lmlt
     * @return LeftMenuTemplate
     */
    public function getLeftMenuTemplate(System $sys, CategoryManager $cm, LeftMenuListTemplate $lmlt)
    {
        $path = $sys->getLeftMenuPath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $lmt = new LeftMenuTemplate(file_get_contents($path));

        $commsStyle = '';
        $servsStyle = '';

        if(isset($_GET['what']) && $_GET['what'] == 'comms')
        {
            $commsStyle = 'active';
            $servsStyle = 'disactive';
        }
        else if(isset($_GET['what']) && $_GET['what'] == 'servs')
        {
            $commsStyle = 'disactive';
            $servsStyle = 'active';
        }
        else
        {
            $_GET['what'] = 'comms';
            $commsStyle = 'active';
            $servsStyle = 'disactive';
        }

        $lmt->setCommsStyle($commsStyle);
        $lmt->setServsStyle($servsStyle);
        $lmt->setList($lmlt->getContent());
        $lmt->setWht($_GET['what']);
        return $lmt;
    }

    /**
     *
     * @param System $sys
     * @param Categories $c
     * @param string $what
     * @return LeftMenuListTemplate
     */
    public function getLeftMenuListTemplate(System $sys, Categories $c, $what)
    {
        $path = $sys->getLeftMenuListTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $lmlt = new LeftMenuListTemplate(file_get_contents($path));

        while(!is_null($t = $c->getK()))
        {
            $a = $c->isActK() ? 'active' : 'disactive';
            $lmlt->addPosition('kategoria', $a, $what.'&id='.$t[1], $t[0], $t[1]);
        }

        while(!is_null($t = $c->getO()))
        {
            $a = $c->isActO() ? 'active' : 'disactive';
            $lmlt->addPosition('obszar', $a, $what.'&id='.$t[1], $t[0], $t[1]);
        }

        while(!is_null($t = $c->getT()))
        {
            $a = $c->isActT() ? 'active' : 'disactive';
            $lmlt->addPosition('tematyka', $a, $what.'&id='.$t[1], $t[0], $t[1]);
        }

        return $lmlt;
    }

    /**
     *
     * @param System $sys
     * @return ResultsListTemplate
     * @throws NoTemplateFile
     */
    public function getResultsListTemplate(System $sys, Results $r)
    {
        if($r->areCommisionsSet()) $path = $sys->getResultsRowCommTemplatePath();
        else $path = $sys->getResultsRowServTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $rlt = new ResultsListTemplate(file_get_contents($path));

        $colors = array('#f9f9f9', '#ececec', '#f9f9f9', '#ececec');
        $i = 0;


        if($r->areCommisionsSet())
        {
            while(!is_null($c = $r->getComm()))
            {
                $rlt->addComm($colors[$i%4], $colors[(++$i)%4], 'img/icons/free-for-job.png', $c->getKategoria_name(), $c->getTematyka_name(), $c->getId_comm(), $c->getPlace(), $c->getParts_count(), $c->getCena_min(), $c->getCena_max(), UF::getDoKonca($c->getDate_end()), $c->getModuly_names());
            }
        }
        else
        {
            while(!is_null($s = $r->getServ()))
            {
                $rlt->addServ($colors[$i%4], $colors[(++$i)%4], 'img/icons/free-for-job.png', $s->getKategoria_name(), $s->getName(), $s->getId_serv(), $s->getPlace(), $s->getCena(), UF::getDoKonca($s->getDate_end()), $s->getModuly_names(), $s->getProgram());
            }
        }
        return $rlt;
    }

    public function getResultsListTemplateForProfile(System $sys, Results $r, $profile = null)
    {
        if(is_null($profile)) $path = 'view/html/index_results_row_comm_profile.html';
        else if($profile == 'offer') $path = 'view/html/index_results_row_comm_profile_offer.html';
        else if($profile == 'moje') $path = 'view/html/index_results_row_comm_profile_moje.html';
        else if($profile == 'biore') $path = 'view/html/index_results_row_comm_profile_biore.html';

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $rlt = new ResultsListTemplate(file_get_contents($path));

        $colors = array('#f9f9f9', '#ececec', '#f9f9f9', '#ececec');
        $i = 0;


        if($r->areCommisionsSet())
        {
            while(!is_null($c = $r->getComm()))
            {
                $rlt->addComm($colors[$i%4], $colors[(++$i)%4], 'img/icons/free-for-job.png', $c->getKategoria_name(), $c->getTematyka_name(), $c->getId_comm(), $c->getPlace(), $c->getParts_count(), $c->getCena_min(), $c->getCena_max(), UF::getDoKonca($c->getDate_end()), $c->getModuly_names());
            }
        }
        else
        {
            while(!is_null($s = $r->getServ()))
            {
                $rlt->addServ($colors[$i%4], $colors[(++$i)%4], 'img/icons/free-for-job.png', $s->getKategoria_name(), $s->getName(), $s->getId_serv(), $s->getPlace(), $s->getCena(), UF::getDoKonca($s->getDate_end()), $s->getModuly_names(), $s->getProgram());
            }
        }
        return $rlt;
    }

    /**
     *
     * @param System $sys
     * @param ResultsListTemplate $rlt
     * @return ResultsTemplate
     * @throws NoTemplateFile
     */
    public function getResultsTemplate(System $sys, ResultsListTemplate $rlt)
    {
        $path = '';
        if($rlt->comms()) $path = $sys->getResultsTableCommsTemplatePath();
        else $path = $sys->getResultsTableServsTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $rt = new ResultsTemplate(file_get_contents($path));
        $rt->setWyniki($rlt->getContent());
        return $rt;
    }

    public function getResultsTemplateForProfile(System $sys, ResultsListTemplate $rlt, $profile = null)
    {
        if(is_null($profile)) $path = 'view/html/index_results_table_comms_profile.html';
        else if($profile == 'offer') $path = 'view/html/index_results_table_comms_profile_offer.html';
        else if($profile == 'moje') $path = 'view/html/index_results_table_comms_profile_moje.html';
        else if($profile == 'biore') $path = 'view/html/index_results_table_comms_profile_biore.html';

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $rt = new ResultsTemplate(file_get_contents($path));
        $rt->setWyniki($rlt->getContent());
        return $rt;
    }

    /**
     *
     * @param System $sys
     * @return LoginFormTemplate
     * @throws NoTemplateFile
     */
    public function getLoginFormTemplate(System $sys)
    {
        $path = $sys->getLoginFormTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $lft = new LoginFormTemplate(file_get_contents($path));
        $lft->setEmail(RFD::get('logForm', 'email'));
        $lft->setAction($sys->getScriptLoginPath());
        return $lft;
    }

    /**
     *
     * @param System $sys
     * @param Categories $c
     * @return AddCommFormTemplate
     * @throws NoTemplateFile
     */
    public function getAddCommFormTemplate(System $sys, Categories $c)
    {
        $path = $sys->getAddCommTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $acft = new AddCommFormTemplate(file_get_contents($path));
        $acft->setCategories($c, RFD::get('addCommForm', 'cat'));
        $acft->setObszary($c, RFD::get('addCommForm', 'subcat'));
        $acft->setTematyki($c, RFD::get('addCommForm', 'subsubcat'));
        $acft->setModuly($c, RFD::get('addCommForm', 'moduly'));
        $acft->setLong(RFD::get('addCommForm', 'long'));
        $acft->setDays(RFD::get('addCommForm', 'days'));
        $acft->setDates(RFD::get('addCommForm', 'date_a'), RFD::get('addCommForm', 'date_b'), RFD::get('addCommForm', 'date_c'), RFD::get('addCommForm', 'date_d'));
        $acft->setExpire(RFD::get('addCommForm', 'expire'));
        $acft->setPlace(RFD::get('addCommForm', 'place'));
        $acft->setWoj(RFD::get('addCommForm', 'woj'));
        $acft->setCeny(RFD::get('addCommForm', 'cena_min'), RFD::get('addCommForm', 'cena_max'));
        $acft->setPart0(RFD::get('addCommForm', 'part0'));
        $acft->setParticipants(RFD::get('addCommForm', 'parts'));
        return $acft;
    }

    public function getAddServFormTemplate(System $sys, Categories $c)
    {
        $path = $sys->getAddServTemplatePath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $asft = new AddServFormTemplate(file_get_contents($path));
        $asft->setCategories($c, RFD::get('addServForm', 'cat'));
        $asft->setObszary($c, RFD::get('addServForm', 'subcat'));
        $asft->setTematyki($c, RFD::get('addServForm', 'subsubcat'));
        $asft->setModuly($c, RFD::get('addServForm', 'moduly'));
        $asft->setName(RFD::get('addServForm', 'name'));
        $asft->setProgram(RFD::get('addServForm', 'program'));
        $asft->setDates(RFD::get('addServForm', 'date_uzg'), RFD::get('addServForm', 'date_a'), RFD::get('addServForm', 'date_b'));
        $asft->setCena(RFD::get('addServForm', 'cena'));
        $asft->setCena_(RFD::get('addServForm', 'cena_'));
        $asft->setDesc(RFD::get('addServForm', 'desc'));
        $asft->setMail(RFD::get('addServForm', 'mail'));
        $asft->setContact(RFD::get('addServForm', 'contact'));
        $asft->setPhone(RFD::get('addServForm', 'phone'));
        $asft->setWoj(RFD::get('addServForm', 'woj'));
        $asft->setPlace(RFD::get('addServForm', 'place'));
        return $asft;
    }

    public function getCommTemplate(System $sys, Commision $c)
    {
        $path = $sys->getTemplateCommPath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $ct = new CommTemplate(file_get_contents($path));
        $ct->setId_comm($c->getId_comm());
        $ct->setDate_end($c->getDate_end());
        $ct->setCeny($c->getCena_min(), $c->getCena_max());
        $ct->setDays($c->getDays());
        $ct->setLong($c->getLong());
        $ct->setParts_count($c->getParts_count());
        $ct->setPlace($c->getPlace(), $c->getWoj());
        $ct->setTerminy($c->getDate_a(), $c->getDate_b(), $c->getDate_c(), $c->getDate_d());
        $ct->setKategoria_name($c->getKategoria_name());
        $ct->setObszar_name($c->getObszar_name());
        $ct->setTematyka_name($c->getTematyka_name());
        $ct->setModuly_names($c->getModuly_names());
        $ct->setOferty($c->getOferty());
        return $ct;
    }

    public function getServTemplate(System $sys, Service $s, $user_logged)
    {
        $path = $sys->getTemplateServPath();

        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');

        $st = new ServTemplate(file_get_contents($path));
        $st->setId_serv($s->getId_serv());
        $st->setModuly_names($s->getModuly_names());
        $st->setProgram($s->getProgram());
        $st->setDate_end($s->getDate_end());
        $st->setKategoria_name($s->getKategoria_name());
        $st->setObszar_name($s->getObszar_name());
        $st->setTematyka_name($s->getTematyka_name());
        $st->setTerminy($s->getDate_a(), $s->getDate_b());
        $st->setPlace($s->getPlace(), $s->getWoj());
        $st->setCena($s->getCena(), $s->getCena_());
        $st->setInfo($s->getDesc());
        $st->setKontakt($s->getMail(), $s->getPhone(), $s->getContact(), $user_logged);
        $st->setName($s->getName());
        return $st;
    }
    
    /**
     * Tworzenie szablonu katalogu zlecen z uzupelnionymi danymi
     * 
     * @throws NoTemplateFile 
     */
    public function getCatalog($what, DBC $dbc, CategoryManager $catm, CommisionManager $cm, ServiceManager $sm)
    {
        $path_main = ($what == 'comms') ? Pathes::getPathTemplateCatalogMainComms() : Pathes::getPathTemplateCatalogMainServs();
        $path_k = Pathes::getPathTemplateCatalogKategoria();
        $path_o = Pathes::getPathTemplateCatalogObszar();
        $path_t = Pathes::getPathTemplateCatalogTematyka();
        
        if(!file_exists($path_k))
            throw new NoTemplateFile($path_k.' plik nie istnieje!');
        if(!file_exists($path_o))
            throw new NoTemplateFile($path_o.' plik nie istnieje!');
        if(!file_exists($path_t))
            throw new NoTemplateFile($path_t.' plik nie istnieje!');
        if(!file_exists($path_main))
            throw new NoTemplateFile($path_main.' plik nie istnieje!');
        

        //zaczytywanie wszystkich kategorii obszarow i tematyk do obiektu Categories
        $c = $catm->getCategoriesForCatalogs($dbc);
        
        $CatsSums = 0;
        $SubcatsSums = 0;
        $SubsubcatsSums = 0;

        //zliczamy wszystko w 3 krokach
        if($what == 'comms')
        {
            $CatsSums = $cm->getCatsSums($dbc);
            $SubcatsSums = $cm->getSubcatsSums($dbc);
            $SubsubcatsSums = $cm->getSubsubcatsSums($dbc);
        }
        else if($what == 'servs')
        {
            //zliczamy wszystko w 3 krokach
            $CatsSums = $sm->getServsSums($dbc);
            $SubcatsSums = $sm->getSubservsSums($dbc);
            $SubsubcatsSums = $sm->getSubsubservsSums($dbc);
        }
        
        $tempK = file_get_contents($path_k);
        $tempO = file_get_contents($path_o);
        $tempT = file_get_contents($path_t);
        
        $t1 = '';
        //wyświetlamy wszystko za pośrednictwem szablonów
        while ($k = $c->getK()) {

            $t1 .= str_replace(array('{%what%}', '{%id%}', '{%kategoria%}', '{%ile%}'), array($what, $k[1], $k[0], empty($CatsSums[$k[1]]) ? 0 : $CatsSums[$k[1]]), $tempK);

            $t2 = '';
            //przydzielanie obszarów do kategorii
            while (($o = $c->getO())) {
                if ((strpos($o[1], $k[1] . '_')) === 0) {

                    $t2 .= str_replace(array('{%what%}', '{%id%}', '{%obszar%}', '{%ile%}'), array($what, $o[1], $o[0], empty($SubcatsSums[$o[1]]) ? 0 : $SubcatsSums[$o[1]]), $tempO);


                    $t3 = '';
                    //przydzielanie tematyk do obszarów
                    while (($t = $c->getT())) {
                        if ((strpos($t[1], $o[1] . '_')) === 0) {


                            $t3 .= str_replace(array('{%what%}', '{%id%}', '{%tematyka%}', '{%ile%}'), array($what, $t[1], $t[0], empty($SubsubcatsSums[$t[1]]) ? 0 : $SubsubcatsSums[$t[1]]), $tempT);
                        }
                    }
                    $t2 = str_replace('{%cat_t.html%}', $t3, $t2);
                }
                $c->resetNrT(); //reset wartości
            }
            $t1 = str_replace('{%cat_oit.html%}', $t2, $t1);



            $c->resetNrO(); //reset wartości
        }
        $t1 = str_replace('{%cat.html%}', $t2, $t1);
        
        return str_replace('{%catalog%}', $t1, file_get_contents($path_main));
    }
}

?>
