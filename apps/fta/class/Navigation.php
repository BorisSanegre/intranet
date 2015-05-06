<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of navigation
 *
 * @author tp4300008
 */
class Navigation {

    /**
     *
     * FPDF@var <ObjectFta>
     */
    protected static $id_fta;

    /**
     * Objet FTA
     * @var ObjectFta 
     */
    protected static $comeback;
    protected static $html_navigation_bar;
    protected static $html_navigation_core;
//protected static $id_fta_chapitre;
    protected static $id_fta_chapitre_encours;
    protected static $objectFta;
    protected static $synthese_action;
    protected static $id_fta_processus;

//protected static $id_intranet_actions;
//protected static $recordIntranetActions;
//protected static $recordProcessus;

    public static function initNavigation($id_fta, $id_fta_chapitre_encours, $synthese_action, $comeback) {

        self::$id_fta = $id_fta;
//self::$id_fta_chapitre = $id_fta_chapitre;
        self::$id_fta_chapitre_encours = $id_fta_chapitre_encours;
        self::$synthese_action = $synthese_action;
        self::$comeback = $comeback;

        self::$objectFta = new ObjectFta(self::$id_fta);
        /* self::$objectFta->loadCurrentSuiviProjectByChapter(self::$id_fta_chapitre);
          self::$recordChapitre = new DatabaseRecord(
          $table_name = "fta_chapitre", $key_value = self::$id_fta_chapitre_encours
          );
          self::$id_fta_processus = self::$recordChapitre->getFieldValue("id_fta_processus");
          self::$recordProcessus = new DatabaseRecord(
          $table_name = "fta_processus", $key_value = self::$id_fta_processus
          );
          self::$id_intranet_actions = self::$recordProcessus->getFieldValue("id_intranet_actions");
          self::$recordIntranetActions = new DatabaseRecord(
          $table_name = "intranet_actions", $key_value = self::$id_intranet_actions
          );
          self::$is_owner = self::buildIsOwner();
          self::$is_editable = self::buildIsEditable();
          self::$is_correctable = self::buildIsCorrectable();
          self::$taux_validation_processus = self::buildTauxValidationProcessus();
          self::$html_submit_button = self::buildHtmlSubmitButton();
          self::$html_correct_button = self::buildHtmlCorrectButton();

          self::$html_suivi_dossier = self::buildSuiviDossier(); */
        self::$html_navigation_core = self::buildNavigationCore();
        self::$html_navigation_bar = self::buildNavigationAll();
    }

    public static function getHtmlNavigationBar() {
        return self::$html_navigation_bar;
    }

// Elément html réuni et contruie de la barre de navigation
    protected static function buildNavigationAll() {

        $return = self::$html_navigation_core

        ;
        return $return;
    }

    protected static function buildNavigationCore() {

//lien entre les onglets de navigation et leur chapitres
        $return = Navigation::buildNavigationBar();

        return $return;
    }

//    $req = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray(
//                    "SELECT " . SalariesModel::FIELDNAME_PRENOM . "," . SalariesModel::FIELDNAME_NOM
//                    . " FROM " . SalariesModel::TABLENAME
//                    . " WHERE " . SalariesModel::KEYNAME
//                    . "='" . $_SESSION[FtaModel::FIELDNAME_CREATEUR] . "' ");
//
//    foreach ($req as $rows) {
//
//        $createur = $rows[SalariesModel::FIELDNAME_PRENOM] . " " . $rows[SalariesModel::FIELDNAME_NOM];
//    }

    protected static function buildNavigationBar() {

        //Action: "consultation" ou "modification"
        //Barre de navigation de la Fiche Tehnique Article
        //Variables
        $html_table = "table "              //Permet d'harmoniser les tableaux
                . "border=1 "
                . "width=100% "
                . "class=contenu "
        ;



        //Récupère la page en cours
        //$page_default=substr(strrchr($_SERVER["PHP_SELF"], '/'), '1', '-4');
        $arrayFtaEtatAndFta = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray(
                        "SELECT * FROM " . FtaModel::TABLENAME . "," . FtaEtatModel::TABLENAME . " "
                        . " WHERE " . FtaModel::KEYNAME . "=" . $this->getKeyValue()
                        . " AND " . FtaEtatModel::TABLENAME . "." . FtaEtatModel::KEYNAME . "=" . FtaModel::TABLENAME . "." . FtaModel::KEYNAME
        );

        foreach ($arrayFtaEtatAndFta as $rowsFtaEtatAndFta) {

            //Récupération des informations préalables
            $rowsFtaEtatAndFta[FtaModel::KEYNAME] = self::$id_fta;
            self::$objectFta = new ObjectFta(self::$id_fta);




            //Nom de l'assistante de projet responsable:
            $array = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray(
                            "SELECT " . SalariesModel::FIELDNAME_PRENOM . "," . SalariesModel::FIELDNAME_NOM
                            . " FROM " . SalariesModel::TABLENAME
                            . " WHERE " . SalariesModel::KEYNAME
                            . "='" . $rowsFtaEtatAndFta[FtaModel::FIELDNAME_CREATEUR] . "' ");
            foreach ($array as $rows) {
                $createur = $rows[SalariesModel::FIELDNAME_PRENOM] . " " . $rows[SalariesModel::FIELDNAME_NOM];
            }


            //Construction du Menu
            if ($rowsFtaEtatAndFta[FtaModel::FIELDNAME_ACTICLE_AGROLOGIC]) {
                $identifiant = $rowsFtaEtatAndFta[FtaModel::FIELDNAME_ARTICLE_AGROLOGIC];
            } else {
                $identifiant = $rowsFtaEtatAndFta[FtaModel::FIELDNAME_DOSSIER_FTA] . "v" . $_SESSION[FtaModel::FIELDNAME_VERSION_DOSSIER_FTA];
            }
            if ($rowsFtaEtatAndFta[FtaModel::FIELDNAME_LIBELLE]) {
                $nom = $rowsFtaEtatAndFta[FtaModel::FIELDNAME_LIBELLE];
            } else {
                $nom = $rowsFtaEtatAndFta[FtaModel::FIELDNAME_DESIGNATION_COMMERCIALE];
            }
            $menu_navigation = "
                     <$html_table>
                     <tr><td class=titre_principal> <div align=\"left\">
                           $identifiant (LDC: <b><font size=\"2\" color=\"#0000FF\">" . $rowsFtaEtatAndFta[FtaModel::FIELDNAME_CODE_ARTICLE_LDC] . "</font></b>) - $nom &nbsp;&nbsp;&nbsp;&nbsp;<i>(gérée par $createur)</i>
                           </div>
                     </td></tr>
                     <tr class=titre><td>
                     ";
        }
        //Si une action est donnée, alors construction du menu des chapitres    
        $menu_navigation .= Navigation::CheckSyntheseAction();
        //Lien de retour rapide
        /* $menu_navigation.= "</td></tr><tr><td>
          <a href=index.php?id_fta_etat=".$_SESSION["id_fta_etat"]."&nom_fta_etat=".$_SESSION["abreviation_fta_etat"]."&synthese_action=$synthese_action>Retour vers la synthèse</a>
          "; */
        if ($comeback == 1) {
            $_SESSION["comeback_url"] = $_SERVER["HTTP_REFERER"];
            $_GLOBALS["comeback_url"] = $_SESSION["comeback_url"];
        }
        $menu_navigation.= "</td></tr><tr><td>
    <a href=" . $_SESSION["comeback_url"] . "><img src=../lib/images/bouton_retour.png alt=\"\" title=\"Retour à la synthèse\" width=\"18\" height=\"15\" border=\"0\" /> Retour vers la synthèse</a> |
    ";
        //Corps du menu
        $menu_navigation.="
                    <a href=historique.php?id_fta=" . self::$id_fta . "><img src=./images/graphique.png alt=\"\" title=\"Etat d'avancement\" width=\"18\" height=\"15\" border=\"0\" /> Etat d'avancement</a>
                       </td></tr>
                       </table>
                       ";
        return $menu_navigation;
        //return $return raplacera menu_navigation;
    }

    protected static function CheckSyntheseAction() {

        $t_processus_encours = array();
        $t_processus_visible = array();


        self::$objectFta = new ObjectFta(self::$id_fta);
        //self objet fta ne marche pas a corriger
        //Si une action est donnée, alors construction du menu des chapitres
        if (self::$synthese_action) {
            //Etat d'avancement de la FTA et Recherche des processus validés (et donc en lecture-seule)
            $req = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray(
                            "SELECT DISTINCT " . FtaProcessusModel::TABLENAME
                            . ".* FROM " . FtaProcessusModel::TABLENAME
                            . ", " . FtaProcessusCycleModel::TABLENAME
                            . "WHERE " . FtaProcessusCycleModel::TABLENAME . "." . FtaProcessusCycleModel::FIELDNAME_PROCESSUS_INIT
                            . "=" . FtaProcessusModel::TABLENAME . "." . FtaProcessusModel::KEYNAME
                            . "AND " . FtaProcessusCycleModel::FIELDNAME_FTA_ETAT . "=I"
                            . "AND " . FtaProcessusCycleModel::FIELDNAME_CATEGORIE
                            . "='" . self::$objectFta->getFieldValue(ObjectFta::TABLE_FTA_NAME, FtaModel::FIELDNAME_CATEGORIE_FTA) . "' "
            );

            //Balayage de tous les processus
            foreach ($req as $rows) {
                self::$id_fta_processus = $rows[FtaProcessusModel::KEYNAME];
                $taux_validation_processus = fta_processus_validation(self::$id_fta, self::$id_fta_processus);

                //Liste des processus visible(lecture-seule)
                if ($taux_validation_processus == 1) {
                    $t_processus_visible[] = $rows[FtaProcessusModel::KEYNAME];
                }
            }//Fin du balayage
            //Recherche des processus en cours
            //Balayage des cycles des processus (en excluant les processus déjà validés)
            $req = "SELECT DISTINCT " . FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT
                    . "FROM " . FtaProcessusCycleModel::TABLENAME
                    . "," . FtaProcessusModel::TABLENAME
                    . "," . IntranetActionsModel::TABLENAME
                    . "," . IntranetDroitsAccesModel::TABLENAME
                    . "," . IntranetModulesModel::TABLENAME
                    . " WHERE 1 AND ( 1 "
            ;



            //Suppression des processus déjà validé
            $req .= $this->DeleteValidProcess($t_processus_visible);

            //Vérification des droits d'accès de l'utilisateur en cours
            $req .=") "
                    . " AND " . FtaProcessusCycleModel::TABLENAME . "." . FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT
                    . "=" . FtaProcessusModel::TABLENAME . "." . FtaProcessusModel::KEYNAME        //Jointure
                    . " AND " . FtaProcessusModel::TABLENAME . "." . FtaProcessusModel::FIELDNAME_ID_INTRANET_ACTIONS
                    . "=" . IntranetActionsModel::TABLENAME . "." . IntranetActionsModel::KEYNAME          //Jointure
                    . " AND " . IntranetActionsModel::TABLENAME . "." . IntranetActionsModel::KEYNAME
                    . "=" . IntranetDroitsAccesModel::TABLENAME . "." . IntranetDroitsAccesModel::FIELDNAME_ID_INTRANET_ACTIONS  //Jointure
                    . " AND " . IntranetDroitsAccesModel::TABLENAME . "." . IntranetDroitsAccesModel::FIELDNAME_ID_INTRANET_MODULES
                    . "=" . IntranetModulesModel::TABLENAME . "." . IntranetModulesModel::KEYNAME  //Jointure
                    . " AND " . IntranetDroitsAccesModel::FIELDNAME_ID_USER . "=" . $_SESSION["id_user"] //Utilisateur actuellement connecté
                    . " AND " . IntranetModulesModel::FIELDNAME_NOM_INTRANET_MODULES . "=" . FtaModel::TABLENAME
                    . " AND " . IntranetDroitsAccesModel::FIELDNAME_NIVEAU_INTRANET_DROITS_ACCES . "=1"  //L'utilisateur est propriétaire
                    . " AND " . FtaProcessusCycleModel::FIELDNAME_FTA_ETAT . "='" . $objectFta->getFieldValue(ObjectFta::TABLE_ETAT_NAME, "abreviation_fta_etat") . "' "
                    . " AND " . FtaModel::FIELDNAME_CATEGORIE_FTA . "= '" . $objectFta->getFieldValue(ObjectFta::TABLE_FTA_NAME, "id_fta_categorie") . "' "
            ;


            //Finalisation de la requête
            $req .="";


            $result = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray($req);
            foreach ($result as $rows) {
                //Pour chaque processus, on vérifie que tous ces précédents sont validés
                $req = "SELECT * FROM " . FtaProcessusCycleModel::TABLENAME
                        . "WHERE " . FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT
                        . "=" . $rows[FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT]
                        . " AND ( 1 "
                ;

                //Ajout de la restriction des processus validé
                $req .=$this->AddValidProcess($t_processus_visible);

                //Recherche dans le cycle correspondant à l'état en cours de la fiche
                $req_etat = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray(
                                "SELECT " . FtaEtatModel::TABLENAME . "." . FtaEtatModel::FIELDNAME_ABREVIATION
                                . " FROM " . FtaEtatModel::TABLENAME . "," . FtaModel::TABLENAME
                                . " WHERE " . FtaEtatModel::TABLENAME . "." . FtaEtatModel::KEYNAME
                                . "=" . FtaModel::TABLENAME . "." . FtaModel::FIELDNAME_ID_FTA_ETAT
                                . " AND " . FtaModel::TABLENAME . "." . FtaModel::KEYNAME
                                . "= '" . self::$id_fta
                );

                foreach ($req_etat as $rowsEtat) {
                    $abreviation_fta_etat = $rowsEtat[FtaEtatModel::FIELDNAME_ABREVIATION];
                }

                $req .= "AND " . FtaProcessusCycleModel::FIELDNAME_FTA_ETAT
                        . "='" . $abreviation_fta_etat . "' ";

                //Filtrage par catégorie
                //Finalisation de la requête
                $req .=")";

                //Si la requête est vide, c'est que tous les processus précédents sont validés
                //Il est donc un Processus en cours
                $req_temp = DatabaseOperation::query($req);

                if (!mysql_num_rows($req_temp)) {
                    //Ce processus en cours, est-il du type repartie ou centralisé ?
                    $reqType = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray(
                                    "SELECT " . FtaProcessusModel::FIELDNAME_MULTISITE_FTA_PROCESSUS
                                    . " FROM " . FtaProcessusModel::TABLENAME
                                    . "WHERE " . FtaProcessusModel::KEYNAME
                                    . "=" . $rows[FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT]
                    );
                    foreach ($reqType as $rowsType) {
                        $multisite_fta_processus = $rowsType[FtaProcessusModel::FIELDNAME_MULTISITE_FTA_PROCESSUS];
                    }

                    //Oui, il s'agit d'un Processus répartie sur les sites d'assemblage
                    $t_processus_encours = CheckMultiSite($multisite_fta_processus, $rows, $t_processus_encours);
                }
            }//Fin du balayage des processus non-validés
            //Recherche des processus Publics
            //Création de la liste des processus dans la barre de navigation          
            $t_liste_processus = array_merge($t_processus_encours, $t_processus_visible);

            //Ajout des processus n'ayant pas de précédents et donc obligatoirement présent dans le menu de navigation
            $resultAddProces = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray(
                            "SELECT " . FtaProcessusModel::TABLENAME . ".* FROM " . FtaProcessusModel::TABLENAME
                            . " LEFT JOIN " . FtaProcessusCycleModel::TABLENAME
                            . " ON " . FtaProcessusModel::TABLENAME . "." . FtaProcessusModel::KEYNAME
                            . "=" . FtaProcessusCycleModel::TABLENAME
                            . "." . FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT
                            . " WHERE " . FtaProcessusCycleModel::TABLENAME
                            . "." . FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT . " IS NULL"
            );
            foreach ($resultAddProces as $rowsAddProcess) {
                $t_liste_processus[] = $rowsAddProcess[FtaProcessusModel::KEYNAME];
            }

            //Récupération des Chapitres accessible dans le menu de naviguation
            $menu_navigation = RecupChapitre($t_liste_processus);
        }//Fin du controle de $synthese_action

        return $menu_navigation;
    }

    public static function RecupChapitre($paramT_Liste_Processus) {
        $page_default = "modification_fiche";
        if ($paramT_Liste_Processus) {
            /*
             * @todo AMeliorer la req pas *
             */
            $reqRecup = "SELECT * FROM " . FtaChapitreModel::TABLENAME . " LEFT JOIN " . FtaProcessusModel::TABLENAME
                    . " ON " . FtaProcessusModel::TABLENAME . "." . FtaProcessusModel::KEYNAME
                    . "=" . FtaChapitreModel::TABLENAME . "." . FtaChapitreModel::FIELDNAME_ID_PROCESSUS
                    . " WHERE ( "
                    . FtaChapitreModel::TABLENAME . "." . FtaChapitreModel::FIELDNAME_ID_PROCESSUS . "=0"                          //Chapitre public
            ;
            foreach ($paramT_Liste_Processus as $value) {
                $reqRecup .= " OR " . FtaProcessusModel::TABLENAME . "." . FtaProcessusModel::KEYNAME . "=" . $value . " ";
            }
            $reqRecup .=" ) ORDER BY  fta_chapitre.id_fta_chapitre";
            $resultRecup = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray($reqRecup);

            //Balyage des chapitres trouvés
            foreach ($resultRecup as $rowsRecup) {
                $id_fta_chapitre = $rowsRecup[FtaChapitreModel::KEYNAME];
                $nom_usuel_fta_chapitre = $rowsRecup[FtaChapitreModel::FIELDNAME_NOM_USUEL_CHAPITRE];

                //Dans le cas où il n'y a pas de chapitre sélectionné, sélection du premier
                if (!self::$id_fta_chapitre_encours) {
                    self::$id_fta_chapitre_encours = $id_fta_chapitre;
                }
                if (self::$id_fta_chapitre_encours == $id_fta_chapitre) {
                    $b = "<font size = 3 color = #5494EE><b>";
                    $image1 = "[>";
                    $image2 = "<]";
                    $num = 1;
                } else {
                    $image1 = "[>";
                    $image2 = "<]";
                    //Ce chapitre est-il public?
                    if ($rowsRecup[FtaProcessusModel::KEYNAME] == 0) {
                        $b = "<font color=\"#8977A9\">";
                    } else {
                        //Le chapitre est-il validé ?
                        $req1 = "SELECT id_fta_suivi_projet "
                                . "FROM " . FtaSuiviProjetModel::TABLENAME
                                . "WHERE " . FtaSuiviProjetModel::FIELDNAME_ID_FTA . "=" . self::$id_fta
                                . "AND " . FtaSuiviProjetModel::FIELDNAME_ID_FTA_CHAPITRE . "=" . $id_fta_chapitre
                                . "AND " . FtaSuiviProjetModel::FIELDNAME_SIGNATURE_VALIDATION_SUIVI_PROJET . "<>0 "
                        ;
                        $result1 = DatabaseOperation::query($req1);
                        $num = DatabaseOperation::getSqlNumRows($result1);
                        switch ($num) {
                            case 0:  //Chapiter pas encore validé
                                $b = "<font color=\"#FF0000\">";
                                break;

                            case 1:  //Chapitre validé
                                $b = "<font color=\"#00B300\">";
                                break;

                            default: //Anomalie
                                $titre = "Erreur Grave !";
                                $message = "La fonction afficher_navigation() vient de trouver des doublons de validation des chapitres dans la table fta_suivi_projet";
                                afficher_message($titre, $message, $redirection);
                                break;
                        }
                    }//Fin du test public
                }//Fin de la colorisation

                if ($num == 0 and $_SESSION["synthese_action"] == "attente") {
                    
                } else {
                    $menu_navigation .= "<a href=$page_default.php?id_fta=" . self::$id_fta . "&id_fta_chapitre_encours=$id_fta_chapitre&synthese_action=" . self::$synthese_action . ">$b"
                            . $image1 . $nom_usuel_fta_chapitre . $image2
                            . "</a>"
                            . "</b></font> "
                    ;
                }
            }
        }//Fin de la création des chapitres
        return $menu_navigation;
    }

    //Suppression des processus déjà validé
    protected static function DeleteValidProcess($paramProcessusVisible) {
        if ($paramProcessusVisible) {
            foreach ($paramProcessusVisible as $value) {
                $req .= " AND " . " " . FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT . "<>" . $value . " ";
            }
        }
        return $req;
    }

    //Ajout de la restriction des processus validé
    protected static function AddValidProcess($paramProcessusVisible) {
        if ($paramProcessusVisible) {
            foreach ($paramProcessusVisible as $value) {
                $req = " AND " . " " . FtaProcessusCycleModel::FIELDNAME_PROCESSUS_INIT . "<>" . $value . " ";
            }
        }
        return $req;
    }

    protected static function CheckMultiSite($paramMultisite_Fta_Processus, $paramRows, $paramT_Processus_Encours) {

        if ($paramMultisite_Fta_Processus) {

            //Existe-il une configuration de gestion forcée pour ce processus et ce site d'assemblage ?
            $resultGestion = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray(
                            "SELECT " . FtaProcessusMultisiteModel::FIELDNAME_ID_SITE_PROCESSUS_FTA_PROCESSUS_MULTISITE
                            . "FROM " . FtaProcessusMultisiteModel::TABLENAME
                            . "," . FtaModel::TABLENAME
                            . "WHERE " . FtaProcessusMultisiteModel::FIELDNAME_ID_SITE_ASSEMBLAGE_FTA_PROCESSUS_MULTISITE
                            . "=" . FtaModel::FIELDNAME_SITE_ASSEMBLAGE
                            . "AND " . FtaProcessusMultisiteModel::FIELDNAME_ID_SITE_PROCESSUS_FTA_PROCESSUS_MULTISITE
                            . "= '" . $paramRows[FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT] . "' "
                            . "AND " . FtaModel::KEYNAME . "=" . self::$id_fta
            );
            foreach ($resultGestion as $rowsGestion) {
                if ($rowsGestion == TRUE) {
                    $id_geo = $rowsGestion[FtaProcessusMultisiteModel::FIELDNAME_ID_SITE_PROCESSUS_FTA_PROCESSUS_MULTISITE];
                } else {
                    //Sinon, Vérification de l'égalité entre le site d'assemblage de la FTA et le site de Localisation de l'utilisateur
                    $resultEgalite = DatabaseOperation::convertSqlQueryWithAutomaticKeyToArray(
                                    "SELECT " . GeoModel::KEYNAME . " FROM " . FtaModel::TABLENAME
                                    . "," . GeoModel::TABLENAME
                                    . " WHERE " . FtaModel::KEYNAME
                                    . "=" . self::$id_fta
                                    . " AND " . FtaModel::FIELDNAME_SITE_ASSEMBLAGE
                                    . "=" . GeoModel::FIELDNAME_ID_SITE
                    );
                    foreach ($resultEgalite as $rowsEgalite) {
                        if ($rowsEgalite == TRUE) {
                            $id_geo = $rowsEgalite [GeoModel::KEYNAME];
                        }
                    }
                }
                if ($id_geo == $_SESSION["lieu_geo"]) {
                    //L'égalité est respecté, donc ce processus est bien en cours
                    $paramT_Processus_Encours[] = $paramRows[FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT];
                }
            }
        } else {
            //Enregistrement du processus en tant que processus en cours
            $paramT_Processus_Encours[] = $paramRows[FtaProcessusCycleModel::FIELDNAME_PROCESSUS_NEXT];
        }

        return $paramT_Processus_Encours;
    }

}
