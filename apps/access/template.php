<?
/*
Module d'appartenance (valeur obligatoire)
Par défaut, le nom du module est le répertoire courant
*/
   $module = substr(strrchr(`pwd`, '/'), 1);

/*
Si la page peut être appelée depuis n'importe quel module,
décommentez la ligne suivante
*/

//   $module='';

/*********
Inclusions
*********/
include ("../lib/session.php");         //Récupération des variables de sessions
include ("../lib/debut_page.php");      //Affichage des éléments commun à l'Intranet
include ("functions.php");              //Inclusion des fonctions propres au module

if (isset($menu))                       //Si existant, utilisation du menu demandé
   {include ("./$menu");}               //en variable
else
   {include ("./menu_principal.inc");}  //Sinon, menu par défaut


/*************
Début Code PHP
*************/

/*
    Initialisation des variables
*/
   $page_action=substr(strrchr($_SERVER[PHP_SELF], '/'), '1', '-4')."_post.php";
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
     <form method=$method action=$page_action>
     <input type=hidden name=action value=$action>

     <$html_table>
     <tr class=titre_principal><td>

         Module Modèle à partir duquel vous pouvez créer de nouveaux modules<br>
         Il constitue la ''SPECIFICATION'' à respecter.<br>

     </td></tr>
     <tr><td>

         &nbsp&nbsp&nbsp&nbsp
         Voici le Module permettant de créer d'autres modules.<br>
         <br>
         Comment installer un nouveau module ?<br>
         - Copier /template<br>
         - Déclarer le module dans la table MYSQL `intranet_modules`<br>
         - Configurer les droits d'accès<br>
         - Effectuer la migration de intranet.dev.agis.fr vers intranet.agis.fr<br>
         <br>
         Pensez à optimiser, centraliser et commenter votre code au maximum !<br>
         Boris - 2003.08.12<br>
         <br>
         <br>
         <a href='readme.txt'>Télécharger le ficher readme.txt</a>

     </td></tr>
     <tr><td>

         <center>
         <input type=submit value='Enregistrer'>
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

