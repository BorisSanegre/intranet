<?php
/*
Module d'appartenance (valeur obligatoire)
Par défaut, le nom du module est le répetoire courant
*/
//   $module=substr(strrchr(`pwd`, '/'), 1);
//   $module=trim($module);

/*
Si la page peut être appelée depuis n'importe quel module,
décommentez la ligne suivante
*/

//   $module='';

//Sélection du mode de visualisation de la page
switch($output)
{

  case 'visualiser':
       //Inclusions
       include ("../lib/session.php");         //Récupération des variables de sessions
       include ("../lib/functions.php");       //On inclus seulement les fonctions sans construire de page
       include ("functions.php");              //Fonctions du module
       echo "
            <link rel=stylesheet type=text/css href=../lib/css/intra01.css />
            <link rel=stylesheet type=text/css href=visualiser.css />
       ";

  //break;
  case 'pdf':
  break;

  default:
        //Inclusions
//        include ("../lib/session.php");         //Récupération des variables de sessions
//        include ("../lib/debut_page.php");      //Construction d'une nouvelle
        require_once '../inc/main.php';
        print_page_begin($disable_full_page, $menu_file);

//        if (isset($menu))                       //Si existant, utilisation du menu demandé
//        {                                       //en variable
//           include ("./$menu");
//        }
//        else
//        {
//           include ("./menu_principal.inc");    //Sinon, menu par défaut
//        }

}//Fin de la sélection du mode d'affichage de la page

/*************
Début Code PHP
*************/

/*
    Initialisation des variables
*/
   $page_default=substr(strrchr($_SERVER["PHP_SELF"], '/'), '1', '-4');
   $page_action=$page_default."_post.php";
   $page_pdf=$page_default."_pdf.php";
   $action = 'valider';                       //Action proposée à la page _post.php
   $method = 'POST';             //Pour une url > 2000 caractères, ne pas utiliser utiliser GET
   $html_table = "table "              //Permet d'harmoniser les tableaux
               . "border=1 "
               . "width=100% "
               . "class=contenu "
               ;


/*
  Récupération des données MySQL
*/

  //Chargement de l'ancienne fiche
  $id_fta=$id_fta_original;
  mysql_table_load("fta");

  //Informations de la Fiche Originale
  $id_fta_original=$id_fta;
  $id_dossier_fta_original=$id_dossier_fta;
  $id_version_dossier_fta_original=$id_version_dossier_fta;

  //Chargement de la nouvelle fiche
  $id_fta=$id_fta_new;
  mysql_table_load("fta");

  //Informations de la Fiche Originale
  $id_fta_new=$id_fta;
  $id_dossier_fta_new=$id_dossier_fta;
  $id_version_dossier_fta_new=$id_version_dossier_fta;


/*
    Création des objets HTML (listes déroulante, cases à cocher ...etc.)
*/


/***********
Fin Code PHP
***********/


/**************
Début Code HTML
**************/
echo "
     <form method=post action=modification_fiche.php>
     <input type=hidden name=action value=$action>
     <input type=hidden name=id_fta value=$id_fta_new>

     <$html_table>
     <tr class=titre_principal><td>

         Felicitation, vous venez de dupliquer votre fiche technique article !<br>

     </td></tr>
     <tr><td>

         La fiche originale a l'identifiant: $id_fta_original.<br>
         et appartient au Dossier: $id_dossier_fta_original-$id_version_dossier_fta_original<br>
         <br>
         La fiche dupliquée à le numéro: $id_fta_new.<br>
         et appartient au Dossier: $id_dossier_fta_new-$id_version_dossier_fta_new<br>
         <br>
         Vous allez être maintenant redirigé vers cette nouvelle fiche pour la personaliser et la différencier de son original<br>

     </td></tr>
     <tr><td>

         <center>
         <a href=modification_fiche.php?id_fta=$id_fta&id_fta_chapitre_encours=$id_fta_chapitre_encours&synthese_action=modification>
         <font size=\"3\">Suivant >></font></a>
         </center>

     </td></tr>
     </table>

     </form>
     ";

/************
Fin Code HTML
************/

/***********************
Inclusion de fin de page
***********************/
include ("../lib/fin_page.inc");
?>

