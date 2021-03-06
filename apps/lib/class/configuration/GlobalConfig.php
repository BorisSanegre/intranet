<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of conf
 *
 * @author bs4300280
 */
class GlobalConfig {

    /**
     * Nom du script PHP chargé de répartir les post-traotements.
     */
    const DISPATCHER_SCRIPTNAME = "dispatcher";
    const DISPATCHER_VARNAME = "dispatcher";
    const DISPATCHER_ACTION_VIEW_RECORD = "view_record";
    const INITIALIZED_TRUE = TRUE;
    const INITIALIZED_FALSE = FALSE;
    const VARNAME_EXEC_DEBUG_TIME_START = "exec_debug_time_start";
    const VARNAME_GLOBALCONFIG_IN_PHP_SESSION = "globalConfig";
    const VARNAME_IS_GLOBALCONFIG_INITIALIZED = "isGlobalConfigInitialized";
    const VARNAME_IS_DATABASE_INITIALIZED = "isDatabaseInitialized";
    const APPS_LOG_DIR = "log";
    const APPS_LOG_FILE_MAIL_TRANSACTION = "mail-transactions";
    const APPS_LOG_HISTORY_DIR = "log/history";

    /**
     * Le fichier est sous la forme "mail-S" + N°de la semaine du 2 digit + ".log"
     */
    const APPS_LOG_HISTORY_FILE_MAIL = "mail";

    /**
     * @var EnvironmentConf
     */
    private $conf = NULL;
    private $needBuildConf = NULL;

    /**
     * Utilisateur actuellement authentifié sur le site.
     * @var UserModel 
     */
    private $authenticatedUser = NULL;

    function __construct() {

        /**
         * Par défaut, on estime qu'il n'est pas nécessaire de reconstruire la 
         * configuration de la session.
         */
        $this->setNeedBuildConf(FALSE);

        /**
         * Si la GlobalConfig n'existe pas en session PHP, alors il faut
         * la reconstruire.
         */
        if (!self::getIsGlobalConfigExistInPhpSession()) {
            $this->setNeedBuildConf(TRUE);
        } else {
            /**
             * Sinon, on restaure la Configuration précédemment sauvegardée
             * dans la session PHP
             */
            $this->setConf($_SESSION[self::VARNAME_GLOBALCONFIG_IN_PHP_SESSION]->getConf());

            if ($_SESSION[self::VARNAME_GLOBALCONFIG_IN_PHP_SESSION]->getAuthenticatedUser() == NULL) {

                $this->setAuthenticatedUser(new UserModel);
            } else {
                $this->setAuthenticatedUser($_SESSION[self::VARNAME_GLOBALCONFIG_IN_PHP_SESSION]->getAuthenticatedUser());
            }

            /**
             * Si le mode Debug de session est activé, on reconstruit
             * tout de même la configuration de l'environnement.
             */
            if ($this->getConf()->getExecDebugEnable()) {
                $this->setNeedBuildConf(TRUE);
            }
        }

        /**
         * Si il s'est précédemment révélé nécessaire de reconstruire
         * la configuration de l'environnement, alors on le réalise.
         */
        if ($this->getNeedBuildConf()) {
            $this->buildEnvironmentConf();
        }
    }

    /**
     * Sauvegarde de la GlobalConfig dans la session PHP
     * @param GlobalConfig $paramGlobalConfig
     */
    static function saveGlobalConfToPhpSession(GlobalConfig $paramGlobalConfig) {
        $_SESSION[GlobalConfig::VARNAME_GLOBALCONFIG_IN_PHP_SESSION] = $paramGlobalConfig;
    }

    function openDatabaseConnexion() {
        /**
         * Ouverture de la connexion MySQL  
         */
        mysql_connect($this->getConf()->getMysqlServerName()
                , $this->getConf()->getMysqlDatabaseAuthentificationUsername()
                , $this->getConf()->getMysqlDatabaseAuthentificationPassword()
                , ""
                , MYSQL_CLIENT_COMPRESS)
        ;
        mysql_select_db($this->getConf()->getMysqlDatabaseName());
        mysql_query("SET NAMES utf8");
    }

    /**
     * Chargement de la description de la base de données en mémoire.
     * Attention, cette partie coûte du temps d'exécution.
     * Elle ne sera exécutée qu'une seule fois par session
     * La connexion à la base MySQL est prérequise.
     */
    function buildDatabaseDescription() {

        if (!GlobalConfig::getDatabaseDescriptionIsInitialized() ||
                $this->getConf()->getSessionDebugEnable()
        ) {
            DatabaseDescription::buildDatabaseDescription($this->getConf()->getMysqlDatabaseName());

            /**
             * Liste des modules public
             */
            $req = "SELECT * FROM intranet_modules "
                    . "WHERE public_intranet_modules='1' "
                    . "ORDER BY classement_intranet_modules DESC"
            ;
            $_SESSION["intranet_module_public"] = DatabaseOperation::convertSqlResultWithoutKeyToArray(DatabaseOperation::query($req));

            $req = "SELECT * FROM intranet_modules";
            $result = DatabaseOperation::query($req);
            while ($rows = mysql_fetch_array($result)) {
                $_SESSION["intranet_modules"][$rows["nom_intranet_modules"]]["id_intranet_modules"] = $rows["id_intranet_modules"];
                $_SESSION["intranet_modules"][$rows["nom_intranet_modules"]]["nom_intranet_modules"] = $rows["nom_intranet_modules"];
                $_SESSION["intranet_modules"][$rows["nom_intranet_modules"]]["nom_usuel_intranet_modules"] = $rows["nom_usuel_intranet_modules"];
                $_SESSION["intranet_modules"][$rows["nom_intranet_modules"]]["version_intranet_modules"] = $rows["version_intranet_modules"];
                $_SESSION["intranet_modules"][$rows["nom_intranet_modules"]]["visible_intranet_modules"] = $rows["visible_intranet_modules"];
                $_SESSION["intranet_modules"][$rows["nom_intranet_modules"]]["classement_intranet_modules"] = $rows["classement_intranet_modules"];
                $_SESSION["intranet_modules"][$rows["nom_intranet_modules"]]["public_intranet_modules"] = $rows["public_intranet_modules"];
                $_SESSION["intranet_modules"][$rows["nom_intranet_modules"]]["css_intranet_module"] = $rows["css_intranet_module"];
            }
            $this->setDatabaseIsInitializedToTrue();
        } //Fin des enregistrements MySQL en session
    }

    function buildEnvironmentConf() {
        /*
          Initialisation des variables de sessions et de connexions:
         */

        //Variables relatives aux environnements:
        switch (filter_input(INPUT_SERVER, 'SERVER_NAME')) {

            //Environnement Codeur
            case EnvironmentConf::SITE_COD:

                $envToInit = new EnvironmentCod();
                break;

            //Environnement Développement
            Case EnvironmentConf::SITE_DEV:

                $envToInit = null;
                break;

            //Environnement Production
            Case EnvironmentConf::SITE_PRD:

                $envToInit = null;
                break;

            default:
                echo EnvironmentConf::ENVIRONMENT_DONT_EXIST_MESSAGE;
                $envToInit = null;
        }
        //Initialisation de la configuration
        $this->setConf($envToInit->getConf());

        /**
         * A chaque ouverture de script et si le paramètre de debug est activé,
         * Chronométrage du temps d'exécution du script.
         */
        if ($this->getConf()->getExecDebugEnable()) {
            $_SESSION[GlobalConfig::VARNAME_EXEC_DEBUG_TIME_START] = microtime(true);
        }

        //Sauvegarde de la configuration dans la session:
        $this->setConfIsInitializedToTrue();
    }

    /**
     * 
     * @return UserModel
     */
    function getAuthenticatedUser() {
        return $this->authenticatedUser;
    }

    /**
     * 
     * @param UserModel $authenticatedUser
     */
    function setAuthenticatedUser(UserModel $authenticatedUser) {
        $this->authenticatedUser = $authenticatedUser;
    }

    function getNeedBuildConf() {
        return $this->needBuildConf;
    }

    function setNeedBuildConf($needBuildConf) {
        $this->needBuildConf = $needBuildConf;
    }

    static function getIsGlobalConfigExistInPhpSession() {
        /**
         * La configuration globale est-elle initialisée ?
         * On récupère cette information stockée dans la session PHP.
         */
        return gettype($_SESSION[self::VARNAME_GLOBALCONFIG_IN_PHP_SESSION]) == "object";
    }

    static function getConfIsInitialized() {
        /**
         * La configuration globale est-elle initialisée ?
         * On récupère cette information stockée dans la session PHP.
         */
        return $_SESSION[self::VARNAME_IS_GLOBALCONFIG_INITIALIZED];
    }

    function setConfIsInitialized($confIsInitialized) {
        $_SESSION[self::VARNAME_IS_GLOBALCONFIG_INITIALIZED] = $confIsInitialized;
    }

    function setConfIsInitializedToTrue() {
        $this->setConfIsInitialized(self::INITIALIZED_TRUE);
    }

    function setConfIsInitializedToFalse() {
        $this->setConfIsInitialized(self::INITIALIZED_FALSE);
    }

    static function getDatabaseDescriptionIsInitialized() {
        /**
         * La configuration base de données est-elle initialisée ?
         * On récupère cette information stockée dans la session PHP.
         */
        return $_SESSION[self::VARNAME_IS_DATABASE_INITIALIZED];
    }

    function setDatabaseIsInitialized($databaseIsInitialized) {
        $_SESSION[self::VARNAME_IS_DATABASE_INITIALIZED] = $databaseIsInitialized;
    }

    function setDatabaseIsInitializedToTrue() {
        $this->setDatabaseIsInitialized(self::INITIALIZED_TRUE);
    }

    function setDatabaseIsInitializedToFalse() {
        $this->setDatabaseIsInitialized(self::INITIALIZED_FALSE);
    }

    function getConf() {
        return $this->conf;
    }

    function setConf(EnvironmentConf $conf) {
        $this->conf = $conf;
    }

    //Constantes
//    const ENV_COD = "developpeur";
//    const ENV_DEV = "developpement";
//    const ENV_REC = "recette";
//    const ENV_PRD = "production";
//    const SITE_COD = "127.0.0.1"; //Parfois "localhost", parfois "127.0.0.1" ...
//    const SITE_DEV = "dev-fta05401.grpldc.com";
//    const SITE_REC = "rec-fta05401.grpldc.com";
//    const SITE_PRD = "prd-fta05401.grpldc.com";
//    const SITE_TITLE = "Intranet Groupe LDC";
//    const LDAP_DEBUG = false;
//    const DOC_APIGEN_DIR = "doc/apigen";
    //Variables
    //A classer par ordre alphabérique
//    public $session_debug = false;
//    public $exec_debug = false;
//    public $exec_environnement = null;
//    public $intranet_title = null;
//    public $ldap_server_adress = null;
//    public $ldap_service_enable = null;
//    public $mysql_database_name = null;
//    public $mysql_database_user_name = null; //Suivant environnement
//    public $mysql_database_user_password = null; //Suivant environnement
//    public $mysql_database_host = null;  //Suivant environnement
//    public $mysql_table_authentification = null;
//    public $project_name_simple = null;
//    public $site = null;
//    public $site_webroot = null;       //Suivant environnement
//    public $site_subdir = null;
//    public $smtp_developemnent_email_info_redirection = null;
//    public $smtp_developemnent_email_user_redirection = null;
//    public $smtp_server_adress = null;
//    public $smtp_service_enable = null; //Suivant environnement
}

?>
