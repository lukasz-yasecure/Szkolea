<?php

/**
 * Kontener dla:
 *  - dane logowania i dostepu do bazy
 *  - adres mailowy szkolea
 *  - lista dostepnych akcji w aplikacji
 *  - sciezki do szablonow
 *  - sciezki do klas (modulow)
 *  - sciezki do skryptow
 *
 *  2011-09-21  nowe Template*Path
 *              action + remind_check
 *              action + remind_pass_change
 *  2011-09-22  action + register_form
 *              action + register_check
 *  2011-09-24  action + activation_check
 *  2011-09-26  zmienilem skrypt aktywacyjny na activation.php
 *              dodalem activation_expired_days
 *              action + comm_group_join
 *              action + comm_view
 *              CommGroupJoinFormTemplate
 *  2011-09-27  action + index_view
 *              SearchTemplate
 *              LoginbarTemplate
 *              UserbarTemplate
 *              IndexTemplate
 *              LeftMenuTemplate
 *  2011-09-28  LeftMenuListTemplate
 *  2011-09-29  getResultsTableCommsTemplatePath
 *              getResultsTableServsTemplatePath
 *              getResultsRowCommTemplatePath
 *              getResultsRowServTemplatePath
 *  2011-09-30  action + login
 *              action + logout
 *  2011-10-03  action + comm_group_join_check
 *  2011-10-10  getScriptLoginPath()
 *              action + login, login_check
 *              getScriptIndexPath()
 *              getLoginFormTemplatePath()
 *  2011-10-11  action + add_comm , add_comm_check
 *              getScriptAddCommPath
 *              + $back do getScriptLoginPath
 *              action + activation_resend
 *              getAddCommTemplatePath
 *  2011-10-20  pogrupowane klasy, ladowanie z folderow
 *              class_dirs
 *  2011-11-04  add_serv , add_serv_check + template, script
 *  2011-11-08  doszła nowa klasa z konwerterem z daty z kalendarza na timestampa UF
 *              dodalem script commision
 *  2011-11-09  oferty
 *  2011-11-10  serv template
 *
 */
class System
{
    private $base_url;
    private $activation_expired_days = 7; // ile dni jest wazna aktywacja

    private $db_host = 'localhost';
    private $db_login = 'root';
    private $db_pass = 'xcAW21';
    private $db_dbname = 'szkolea';

    private $mail_szkolea = 'szkolea.pl <no-reply@szkolea.pl>';

    private $actual_action;
    private $actions = array(
        'activation_send' => '', // tutaj bedzie 'nazwa_akcji' => 'klasy do require'
        'activation_check' => '',
        'remind_form' => '',
        'remind_send' => '',
        'remind_check' => '',
        'remind_pass_change' => '',
        'register_form' => '',
        'register_check' => '',
        'comm_group_join' => '',
        'index_view' => '',
        'login' => '',
        'login_check' => '',
        'logout' => '',
        'comm_group_join_check' => '',
        'add_comm' => '',
        'add_comm_check' => '',
        'activation_resend' => '',
        'add_serv' => '',
        'add_serv_check' => '',
        'comm' => '',
        'serv' => '',
        'profile' => '',
        'observe' => '',
        'comm_join' => '',
        'comm_offer' => '',
        'comm_offer_check' => ''
    );

    private $template_path = 'view/html/';
    private $template_main = 'main.html';
    private $template_activation_mail = 'activation_mail.html';
    private $template_remind_mail = 'remind_mail.html';
    private $template_remind_form = 'remind_form.html';
    private $template_pass_change_form = 'pass_change_form.html';
    private $template_register_form = 'register_form.html';
    private $template_comm_group_join_form = 'comm_group_join.html';
    private $template_index = 'index_tmp.html';
    private $template_search = 'search.html';
    private $template_login = 'log.html';
    private $template_loginbar = 'loginbar.html';
    private $template_userbar = 'userbar.html';
    private $template_left_menu = 'index_left_menu.html';
    private $template_left_menu_list = 'index_left_menu_list.html';
    private $template_results_table_comms = 'index_results_table_comms.html';
    private $template_results_table_servs = 'index_results_table_servs.html';
    private $template_results_row_comm = 'index_results_row_comm.html';
    private $template_results_row_serv = 'index_results_row_serv.html';
    private $template_add_comm = 'add_comm.html';
    private $template_add_serv = 'add_serv.html';
    private $template_comm = 'comm.html';
    private $template_serv = 'serv.html';


    private $class_path = 'engine/';
    private $class_dirs = array('activation/', 'categories/', 'commisions/', 'index/', 'login/', 'register/', 'remind/', 'system/', 'user/', 'services/', 'offer/', 'profile/');
    private $class_basic = array('class.log.php', 'class.uf.php', 'class.excmanager.php', 'classes.exceptions.php', 'class.pathes.php');
    private $class_std = 'class.std.php';

    private $script_path = '';
    private $script_index = 'index.php';
    private $script_activation = 'activation.php';
    private $script_remind = 'remind.php';
    private $script_login = 'log.php';
    private $script_add_comm = 'addcomm.php';
    private $script_add_serv = 'addserv.php';
    private $script_commision = 'comm.php';
    private $script_service = 'serv.php';

    private $js_path = 'js/';

    /**
     *
     * @param string $action
     * @param bool $debug
     * @throws BasicModuleDoesNotExist jesli brakuje ktorejs klasy z podstawowych
     * @throws NoDefinitionForAction jesli nie ma takiej akcji zdefiniowanej w systemie
     * @throws ModuleDoesNotExist jesli nie mozna zaladowac klasy
     */
    public function  __construct($action, $debug = false)
    {
        date_default_timezone_set('Europe/Warsaw');
        ini_set('display_errors', 1);
        header('content-Type: text/html; charset=utf8');

        $this->base_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/';

        if(($m = $this->loadBasicModules()) !== true) throw new BasicModuleDoesNotExist('Niedostepny modul: '.$m);
        if(!$this->loadModules($action)) throw new NoDefinitionForAction('Brak definicji akcji: '.$action.' !');
        if($debug)
        {
            if(($m = $this->loadSTD()) !== true) throw new ModuleDoesNotExist('Niedostepny modul: '.$m);
        }

        //UF::basicHttpAuth();
    }

    /**
     *
     * @return bool
     */
    private function loadBasicModules()
    {
        foreach($this->class_basic as $m)
        {
            if(file_exists($this->class_path.$m)) require_once($this->class_path.$m);
            else return $this->class_path.$m;
        }

        return true;
    }

    public function getAction()
    {
        return $this->actual_action;
    }

    /**
     * Na razie wczytuje wszystkie klasy.
     * Bedzie wczytywac tylko klasy wymagane przez akcje.
     *
     * @param string $action
     * @return bool
     */
    private function loadModules($action)
    {
        if(isset($this->actions[$action]))
        {
            $this->actual_action = $action;

            foreach($this->class_dirs as $d)
            {
                $dir = opendir($this->class_path.$d);
                while(false !== ($file = readdir($dir)))
                {
                    if($file != '.' && $file != '..') require_once($this->class_path.$d.$file);
                }
                closedir($dir);
            }

            return true;
        }
        else return false;
    }

    /**
     *
     * @return bool
     */
    private function loadSTD()
    {
        if(file_exists($this->class_path.$this->class_std))
        {
            require_once($this->class_path.$this->class_std);
            return true;
        }
        else return $this->class_path.$this->class_std;
    }

    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function getDb_host() {
        return $this->db_host;
    }

    public function getDb_login() {
        return $this->db_login;
    }

    public function getDb_pass() {
        return $this->db_pass;
    }

    public function getDb_dbname() {
        return $this->db_dbname;
    }

    public function getFatalError()
    {
        echo 'Błąd krytyczny - powiadom administratora!';
        exit();
    }

    public function getMailSzkolea()
    {
        return $this->mail_szkolea;
    }

    public function getActivationExpiredDays()
    {
        return $this->activation_expired_days;
    }

    //
    // Pathes
    //

    public function getTemplateActivationMailPath()
    {
        return $this->template_path.$this->template_activation_mail;
    }

    public function getTemplateRemindMailPath()
    {
        return $this->template_path.$this->template_remind_mail;
    }

    public function getTemplateCommPath()
    {
        return $this->template_path.$this->template_comm;
    }

    public function getTemplateServPath()
    {
        return $this->template_path.$this->template_serv;
    }

    public function getTemplateRemindFormPath()
    {
        return $this->template_path.$this->template_remind_form;
    }

    public function getTemplateMainPath()
    {
        return $this->template_path.$this->template_main;
    }

    public function getTemplatePasswordChangePath()
    {
        return $this->template_path.$this->template_pass_change_form;
    }

    public function getTemplateRegisterFormPath()
    {
        return $this->template_path.$this->template_register_form;
    }
    
    public function getScriptActivationPath()
    {
        return $this->base_url.$this->script_path.$this->script_activation;
    }

    public function getScriptRemindPath()
    {
        return $this->base_url.$this->script_path.$this->script_remind;
    }

    public function getScriptLoginPath()
    {
        return $this->base_url.$this->script_path.$this->script_login;
    }

    public function getScriptAddCommPath()
    {
        return $this->base_url.$this->script_path.$this->script_add_comm;
    }

    public function getScriptCommisionPath($id = null)
    {
        if(is_null($id)) return $this->base_url.$this->script_path.$this->script_commision;
        else return $this->base_url.$this->script_path.$this->script_commision.'?id='.$id;
    }

    public function getScriptServicePath($id = null)
    {
        if(is_null($id)) return $this->base_url.$this->script_path.$this->script_service;
        else return $this->base_url.$this->script_path.$this->script_service.'?id='.$id;
    }

    public function getScriptAddServPath()
    {
        return $this->base_url.$this->script_path.$this->script_add_serv;
    }

    public function getScriptIndexPath()
    {
        return $this->base_url.$this->script_path.$this->script_index;
    }

    public function getCommGroupJoinFormTemplatePath()
    {
        return $this->template_path.$this->template_comm_group_join_form;
    }

    public function getSearchTemplatePath()
    {
        return $this->template_path.$this->template_search;
    }

    public function getLoginbarTemplatePath()
    {
        return $this->template_path.$this->template_loginbar;
    }

    public function getUserbarTemplatePath()
    {
        return $this->template_path.$this->template_userbar;
    }

    public function getAddCommTemplatePath()
    {
        return $this->template_path.$this->template_add_comm;
    }

    public function getAddServTemplatePath()
    {
        return $this->template_path.$this->template_add_serv;
    }

    public function getIndexTemplatePath()
    {
        return $this->template_path.$this->template_index;
    }

    public function getLeftMenuPath()
    {
        return $this->template_path.$this->template_left_menu;
    }

    public function getLeftMenuListTemplatePath()
    {
        return $this->template_path.$this->template_left_menu_list;
    }

    public function getResultsTableCommsTemplatePath()
    {
        return $this->template_path.$this->template_results_table_comms;
    }

    public function getResultsTableServsTemplatePath()
    {
        return $this->template_path.$this->template_results_table_servs;
    }

    public function getResultsRowCommTemplatePath()
    {
        return $this->template_path.$this->template_results_row_comm;
    }

    public function getResultsRowServTemplatePath()
    {
        return $this->template_path.$this->template_results_row_serv;
    }

    public function getLoginFormTemplatePath()
    {
        return $this->template_path.$this->template_login;
    }
}

?>
