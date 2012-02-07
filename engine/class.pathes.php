<?php

class Pathes
{
    public static $base_url = 'http://localhost/szkolea/';

    public static $template_path = 'view/html/';
    public static $template_main = 'main.html';
    public static $template_activation_mail = 'activation_mail.html';
    public static $template_remind_mail = 'remind_mail.html';
    public static $template_remind_form = 'remind_form.html';
    public static $template_pass_change_form = 'pass_change_form.html';
    public static $template_register_form = 'register_form.html';
    public static $template_comm_group_join_form = 'comm_group_join.html';
    public static $template_index = 'index_tmp.html';
    public static $template_search = 'search.html';
    public static $template_login = 'log.html';
    public static $template_loginbar = 'loginbar.html';
    public static $template_userbar = 'userbar.html';
    public static $template_left_menu = 'index_left_menu.html';
    public static $template_left_menu_list = 'index_left_menu_list.html';
    public static $template_results_table_comms = 'index_results_table_comms.html';
    public static $template_results_table_servs = 'index_results_table_servs.html';
    public static $template_results_row_comm = 'index_results_row_comm.html';
    public static $template_results_row_serv = 'index_results_row_serv.html';
    public static $template_add_comm = 'add_comm.html';
    public static $template_add_serv = 'add_serv.html';
    public static $template_comm = 'comm.html';
    public static $template_serv = 'serv.html';
    public static $template_profile_k = 'profile_k.html';
    public static $template_profile_edit_form_k = 'profile_k_edit_form.html';
    public static $template_profile_u = 'profile_u.html';
    public static $template_profile_edit_form_u = 'profile_edit_form_u.html';

    public static $script_path = '';
    public static $script_index = 'index.php';
    public static $script_activation = 'activation.php';
    public static $script_remind = 'remind.php';
    public static $script_login = 'log.php';
    public static $script_add_comm = 'addcomm.php';
    public static $script_add_serv = 'addserv.php';
    public static $script_commision = 'comm.php';
    public static $script_service = 'serv.php';

    public static function getTemplateActivationMailPath()
    {
        return Pathes::$template_path.Pathes::$template_activation_mail;
    }

    public static function getTemplateRemindMailPath()
    {
        return Pathes::$template_path.Pathes::$template_remind_mail;
    }

    public static function getTemplateCommPath()
    {
        return Pathes::$template_path.Pathes::$template_comm;
    }

    public static function getTemplateServPath()
    {
        return Pathes::$template_path.Pathes::$template_serv;
    }

    public static function getTemplateRemindFormPath()
    {
        return Pathes::$template_path.Pathes::$template_remind_form;
    }

    public static function getTemplateMainPath()
    {
        return Pathes::$template_path.Pathes::$template_main;
    }

    public static function getTemplatePasswordChangePath()
    {
        return Pathes::$template_path.Pathes::$template_pass_change_form;
    }

    public static function getTemplateRegisterFormPath()
    {
        return Pathes::$template_path.Pathes::$template_register_form;
    }

    public static function getScriptActivationPath()
    {
        return Pathes::$base_url.Pathes::$script_path.Pathes::$script_activation;
    }

    public static function getScriptRemindPath()
    {
        return Pathes::$base_url.Pathes::$script_path.Pathes::$script_remind;
    }

    public static function getScriptLoginPath()
    {
        return Pathes::$base_url.Pathes::$script_path.Pathes::$script_login;
    }

    public static function getScriptAddCommPath()
    {
        return Pathes::$base_url.Pathes::$script_path.Pathes::$script_add_comm;
    }

    public static function getScriptCommisionPath($id = null)
    {
        if(is_null($id)) return Pathes::$base_url.Pathes::$script_path.Pathes::$script_commision;
        else return Pathes::$base_url.Pathes::$script_path.Pathes::$script_commision.'?id='.$id;
    }

    public static function getScriptServicePath($id = null)
    {
        if(is_null($id)) return Pathes::$base_url.Pathes::$script_path.Pathes::$script_service;
        else return Pathes::$base_url.Pathes::$script_path.Pathes::$script_service.'?id='.$id;
    }

    public static function getScriptAddServPath()
    {
        return Pathes::$base_url.Pathes::$script_path.Pathes::$script_add_serv;
    }

    public static function getScriptIndexPath()
    {
        return Pathes::$base_url.Pathes::$script_path.Pathes::$script_index;
    }

    public static function getCommGroupJoinFormTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_comm_group_join_form;
    }

    public static function getSearchTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_search;
    }

    public static function getLoginbarTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_loginbar;
    }

    public static function getUserbarTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_userbar;
    }

    public static function getAddCommTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_add_comm;
    }

    public static function getAddServTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_add_serv;
    }

    public static function getIndexTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_index;
    }

    public static function getLeftMenuPath()
    {
        return Pathes::$template_path.Pathes::$template_left_menu;
    }

    public static function getLeftMenuListTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_left_menu_list;
    }

    public static function getResultsTableCommsTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_results_table_comms;
    }

    public static function getResultsTableServsTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_results_table_servs;
    }

    public static function getResultsRowCommTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_results_row_comm;
    }

    public static function getResultsRowServTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_results_row_serv;
    }

    public static function getLoginFormTemplatePath()
    {
        return Pathes::$template_path.Pathes::$template_login;
    }

    public static function getPathTemplateProfileK()
    {
        return Pathes::$template_path.Pathes::$template_profile_k;
    }
    
    public static function getPathTemplateProfileEditFormK()
    {
        return Pathes::$template_path.Pathes::$template_profile_edit_form_k;
    }

    public static function getPathTemplateProfileU()
    {
        return Pathes::$template_path.Pathes::$template_profile_u;
    }
    
    public static function getPathTemplateProfileEditFormU()
    {
        return Pathes::$template_path.Pathes::$template_profile_edit_form_u;
    }
}

?>
