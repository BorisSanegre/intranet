<?php
/*
Module d'appartenance (valeur obligatoire)
Par défaut, le nom du module est le répertoire courant
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
//        if (isset($menu))                       //Si existant, utilisation du menu demandé
//        {                                       //en variable
//           include ("./$menu");
//        }
//        else
//        {
//           include ("./menu_principal.inc");    //Sinon, menu par défaut
//        }
      require_once '../inc/main.php';
      print_page_begin($disable_full_page, $menu_file);
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
//   $action = 'valider';                       //Action proposée à la page _post.php
   $method = 'POST';             //Pour une url > 2000 caractères, ne pas utiliser utiliser GET
   $html_table = "table "              //Permet d'harmoniser les tableaux
               . "border=1 "
               . "width=100% "
               . "class=contenu "
               ;

/*
    Récupération des données MySQL
*/

//Droits d'accès
if(${$module."_modification"} >= 1)
{
  $proprietaire=1;
  $html_restricted_box="";
  $bouton_submit="Enregistrer >>";
  }
  else
  {
  $proprietaire=0;
  $html_restricted_box="disabled";
  $bouton_submit="<< Retour";
}

//Titre
if($id_annexe_emballage)
{
  $titre="Modification d'une Fiche Technique Emballage";
  mysql_table_load("annexe_emballage");
  mysql_table_load("annexe_emballage_groupe");
  mysql_table_load("annexe_emballage_groupe_type");
  mysql_table_load("fte_fournisseur");
  $action = 'rewrite';

}else
{
  $titre="Création d'une Fiche Technique Emballage";
  $action = 'insert';

}
/*
if ($date_maj_annexe_emballage=="0000-00-00")
{
  $date_maj_annexe_emballage=date("Y-m-d");
}
 */
//Tableau de données
$bloc="<$html_table>";

        //Activation
        $champ="actif_annexe_emballage";
        $bloc .= "<tr class=contenu><td>".mysql_field_desc("annexe_emballage",$champ)."</td><td>";
        if($$champ)
        {
            $checked="checked";
        }
        else
        {
            $checked="";
        }
        $bloc .= "<input type=\"checkbox\" name=\"actif_annexe_emballage\" value=\"1\" $checked $html_restricted_box />";

        //Groupe de modèle
        $nom_table="annexe_emballage_groupe";
        $champ="nom_annexe_emballage_groupe";
        $id_defaut=$id_annexe_emballage_groupe;
        $nom_liste="id_annexe_emballage_groupe";
        if($proprietaire)
        {
            $value=afficher_table_en_liste_deroulante($nom_table, $champ, $id_defaut, $nom_liste);
        }
        else
        {
            $value=$$champ;
        }
        $bloc .="<tr class=contenu><td>".mysql_field_desc("annexe_emballage_groupe",$champ)."</td><td>"
              . $value
              . "</td></tr>"
              ;

        //Fournisseur
        $nom_table="fte_fournisseur";
        $champ="nom_fte_fournisseur";
        $id_defaut=$id_fte_fournisseur;
        $nom_liste="id_fte_fournisseur";
        if($proprietaire)
        {
            $value=afficher_table_en_liste_deroulante($nom_table, $champ, $id_defaut, $nom_liste);
        }
        else
        {
            $value=$$champ;
        }
        $bloc .="<tr class=contenu><td>".mysql_field_desc($nom_table,$champ)."</td><td>"
              . $value
              . "</td></tr>"
              ;

        //Référence Interne
        $champ="reference_fournisseur_annexe_emballage";
        $bloc .="<tr class=contenu><td>".mysql_field_desc("annexe_emballage",$champ)."</td><td>";
        if($proprietaire)
        {
            $value="<input type=text name=".$champ." value=`".$$champ."` size=50/>";
        }
        else
        {
            $value=$$champ;
        }
        
        $bloc .= $value
              . "</td></tr>"
              ;

        //Poids
        $champ="poids_annexe_emballage";
        $bloc .="<tr class=contenu><td>".mysql_field_desc("annexe_emballage",$champ)."</td><td>";
        if($proprietaire)
        {
            $value="<input type=text name=".$champ." value=`".$$champ."` size=50/>";
        }
        else
        {
            $value=$$champ;
        }
        
        $bloc .= $value
              . "</td></tr>"
              ;

        //Longueur
        $champ="longueur_annexe_emballage";
        $bloc .="<tr class=contenu><td>".mysql_field_desc("annexe_emballage",$champ)."</td><td>";
        if($proprietaire)
        {
            $value="<input type=text name=".$champ." value=`".$$champ."` size=50/>";
        }
        else
        {
            $value=$$champ;
        }
        
        $bloc .= $value
              . "</td></tr>"
              ;

        //Largeur
        $champ="largeur_annexe_emballage";
        $bloc .="<tr class=contenu><td>".mysql_field_desc("annexe_emballage",$champ)."</td><td>";
        if($proprietaire)
        {
            $value="<input type=text name=".$champ." value=`".$$champ."` size=50/>";
        }
        else
        {
            $value=$$champ;
        }
        
        $bloc .= $value
              . "</td></tr>"
              ;

        //Hauteur
        $champ="hauteur_annexe_emballage";
        $bloc .="<tr class=contenu><td>".mysql_field_desc("annexe_emballage",$champ)."</td><td>";
        if($proprietaire)
        {
            $value="<input type=text name=".$champ." value=`".$$champ."` size=50/>";
        }
        else
        {
            $value=$$champ;
        }
        
        $bloc .= $value
              . "</td></tr>"
              ;

        //Epaisseur
        $champ="epaisseur_annexe_emballage";
        $bloc .="<tr class=contenu><td>".mysql_field_desc("annexe_emballage",$champ)."</td><td>";
        if($proprietaire)
        {
            $value="<input type=text name=".$champ." value=`".$$champ."` size=50/>";
        }
        else
        {
            $value=$$champ;
        }
        
        $bloc .= $value
              . "</td></tr>"
              ;

        //Palettisation (si possible)
        $champ="quantite_par_couche_annexe_emballage";
        $bloc .="<tr class=contenu><td>".mysql_field_desc("annexe_emballage",$champ)."</td><td>";
        if($proprietaire)
        {
            $value="<input type=text name=".$champ." value=`".$$champ."` size=50/>";
        }
        else
        {
            $value=$$champ;
        }
        
        $bloc .= $value
              . "</td></tr>"
              ;

        //Palettisation (si possible)
        $champ="nombre_couche_annexe_emballage";
        $bloc .="<tr class=contenu><td>".mysql_field_desc("annexe_emballage",$champ)."</td><td>";
        if($proprietaire)
        {
            $value="<input type=text name=".$champ." value=`".$$champ."` size=50/>";
        }
        else
        {
            $value=$$champ;
        }
        
        $bloc .= $value
              . "</td></tr>"
              ;

        //Date de dernière mise à jour
        $champ="date_maj_annexe_emballage";
        $bloc .= "<tr class=contenu><td  width=\"20%\">".mysql_field_desc("annexe_emballage",$champ)."</td><td>";
        $value=$$champ;
        $bloc.= $value;
        $bloc.="</td></tr>";

        //Listes des FTA utilisant cette FTE
        if($id_annexe_emballage)
        {
            $liste_fta = "";
            $req = "SELECT `fta`.`id_fta`, `fta`.`id_article_agrologic`, access_arti2.LIBELLE "
                 . "FROM `fta_conditionnement`,  `annexe_emballage`,  `fta`,  `access_arti2`, fta_etat "
                 . "WHERE `fta_conditionnement`.`id_annexe_emballage` = `annexe_emballage`.`id_annexe_emballage` "
                 . "AND `fta`.`id_fta` = `fta_conditionnement`.`id_fta` "
                 . "AND `fta`.`id_access_arti2` = `access_arti2`.`id_access_arti2` "
                 . "AND `fta`.`id_fta` = `access_arti2`.`id_fta` "
                 . "AND fta.id_fta_etat=fta_etat.id_fta_etat "
                 . "AND `annexe_emballage`.`id_annexe_emballage` = '".$id_annexe_emballage."' "
                 . "AND abreviation_fta_etat='V' "
                 ;
            $result_fta=DatabaseOperation::query($req);
            if(mysql_num_rows($result_fta))
            {

               $bloc.="<tr></tr><tr class=titre><td>".mysql_field_desc("fta","id_article_agrologic")."</td><td>Liste des Fiches Techniques Articles validée utilisant cet emballage:</td></tr>";
               while($rows_fta=mysql_fetch_array($result_fta))
               {
                   $bloc.="<tr><td align=\"right\">".$rows_fta["id_article_agrologic"]."</td><td>".$rows_fta["LIBELLE"]."</td></tr>";
                   $liste_fta.=$rows_fta["id_fta"].";";

               }//Fin du while
            }//Fin du if
        }//Fin de la liste des FTA

$bloc.="</table>";

/*
     Sélection du mode d'affichage
*/
switch ($output)
{

/*************
Début Code PDF
*************/
case "pdf":
         //Constructeur
         $pdf=new XFPDF();

         //Déclaration des variables de formatages
         $police_standard="Arial";
         $t1_police=$police_standard;
         $t1_style="B";
         $t1_size="12";

         $t2_police=$police_standard;
         $t2_style="B";
         $t2_size="11";

         $t3_police=$police_standard;
         $t3_style="BIU";
         $t3_size="10";

         $contenu_police=$police_standard;
         $contenu_style="";
         $contenu_size="8";

         $chapitre=0;
         $section=0;
         include($page_pdf);
         //$pdf->SetProtection(array("print", "copy"));
         $pdf->Output(); //Read the FPDF.org manual to know the other options

break;
/***********
Fin Code PDF
***********/


/*
    Création des objets HTML (listes déroulante, cases à cocher ...etc.)
*/





/**************
Début Code HTML
**************/
default:

        echo "
             <form method=$method action=$page_action>
             <input type=hidden name=action value=$action>
             <input type=hidden name=selection_groupe value=$selection_groupe>
             <input type=hidden name=selection_fournisseur value=$selection_fournisseur>
             <input type=hidden name=id_annexe_emballage value=$id_annexe_emballage>
             <input type=hidden name=liste_fta value=$liste_fta>

             <$html_table>
             <tr class=titre_principal><td>

                 $titre

             </td></tr>
             <tr><td>

                 $bloc

             </td></tr>
             <tr><td>
                 <center>
                 <input type=submit value='$bouton_submit'>
                 </center>

             </td></tr>
             </table>

             </form>
             ";



/***********************
Inclusion de fin de page
***********************/
include ("../lib/fin_page.inc");

/************
Fin Code HTML
************/

}//Fin du switch de sélection du mode d'affichage

?>