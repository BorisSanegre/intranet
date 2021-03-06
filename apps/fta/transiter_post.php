<?php

/*
Module d'appartenance (valeur obligatoire)
Par défaut, le nom du module est le répetoire courant
*/

//$module=substr(strrchr(`pwd`, '/'), 1);
//$module=trim($module);
//
////Inclusions
//include ("../lib/session.php");
//include ("../lib/functions.php");
//include ("./functions.php");
require_once '../inc/main.php';

$globalConfig = new GlobalConfig();

if(!$action)
{
   $titre="Erreur";
   $message="Vous devez choisir une transition";
   $redirection="";
   afficher_message($titre, $message, $redirection);
}
else
{

/*
-----------------
 ACTION A TRAITER
-----------------
-----------------------------------
 Détermination de l'action en cours
-----------------------------------

 Cette page est appelée pour effectuer un traitement particulier
 en fonction de la variable "$action". Ensuite elle redirige le
 résultat vers une autre page.

 Le plus souvent, le traitement est délocalisé sous forme de
 fonction située dans le fichier "functions.php"

*/
     //echo $action."<br>".$id_fta."<br>".$new_commentaire_maj_fta."<br>";
//echo $abreviation_fta_transition;
$liste_global=array();    //Tableau contenant les emails et le nom des destinataire (cf fonction liste_diffusion_transition() )


//Dans le cas où il n'y aurait qu'une seule FTA a valider,
//Le tableau est rempli avec cette unique valeur.
if(!$selection_fta)
{
  $selection_fta[]=$id_fta;
  $envoi_mail_detail=1;    //Permet d'envoi un mail en mode "détaillé"
  $abreviation_fta_transition=$action;
}

//Controle d'intégrité         *************************************************

   //Justification du la transition
   if (
        (
        !$new_commentaire_maj_fta
        or substr($new_commentaire_maj_fta, 0, 1 ) == " "
        )
      and
        $abreviation_fta_transition<>"V"
      )
   {
     $titre="Informations manquantes";
     $message="Vous devez spécifier un commentaire sur la mise à jour.";
     afficher_message($titre, $message, $redirection);
     $error=1;
   }

   //Tableau des chapitres
   //echo $action;
   if($action== "I")
   {
        $req = "SELECT * FROM fta_chapitre ORDER BY nom_usuel_fta_chapitre";
        $result = DatabaseOperation::query($req);
        $ok=0;
        while($rows_chapitre=mysql_fetch_array($result))
        {
            if (${"nom_fta_chapitre-".$rows_chapitre["id_fta_chapitre"]}==1)
            {
                $ok=1;
            }
        }
        if(!$ok)
        {
            $titre="Informations manquantes";
            $message="Vous devez sélectionner au moins un chapitre à mettre à jour.";
            afficher_message($titre, $message, $redirection);
            $error=1;
        }
   }

// Fin du controle d'intégrité *************************************************

//Si pas d'erreur, lancement de la transition
if(!$error)
{

      $_SESSION["log_transition"]="";

      foreach ($selection_fta as $id_fta)
      {
           //Transition de la FTA
           //$abreviation_fta_transition=$action;
           $commentaire_maj_fta=$new_commentaire_maj_fta;
           //echo $abreviation_fta_transition;
//echo $id_fta;
           $t=transition_fta($id_fta, $abreviation_fta_transition, $commentaire_maj_fta );
                   //Codes de retour de la fonction:
                   //   0: FTA correctement transitée
                   //   1: FTA non transité car risque de doublon
                   //   3: Erreur autre

           if($abreviation_fta_transition=="V") //Seules les FTA validées entrent dans un système de diffusion
           {
               switch ($t)
               {
                 case 0:
                        //Récupération de la liste diffusion
                        $liste_destinataire=liste_diffusion_transition($id_fta);

                        if($liste_destinataire)
                        {
                            $liste_global = $liste_global + $liste_destinataire;
                            //Envoi des mails de notification
                            if($envoi_mail_detail)
                            {
                              $id_fta;
                              $liste_diffusion=$liste_destinataire;
                              $commentaire=$new_commentaire_maj_fta;
                              envoi_mail_detail($id_fta, $liste_diffusion, $commentaire);
                            }
                        }
                 break;

                 case 1:
                        $titre="Action vérrouillée";
                        $message="Cette fiche est déjà en cours de modification.";
                        $redirection="";
                        afficher_message($titre, $message, $redirection);
                 break;

                 case 3:
                        $titre="Erreur sur la FTA $id_fta";
                        $message="Impossible de valider cette FTA";
                        $redirection="";
                        afficher_message($titre, $message, $redirection);
                 break;
               }
           }//Fin de la diffusion des FTA Validée

      }//Fin du parcours de la selection des FTA

      //Envoi du mail global d'information (uniquement pour les FTA Validée
      if(!$envoi_mail_detail and $abreviation_fta_transition=="V")
      {
        $selection_fta;
        $liste_diffusion=$liste_global;
        $commentaire=$new_commentaire_maj_fta;
        envoi_mail_global($selection_fta, $liste_diffusion, $subject);

      }


      //Lancement de la passerelle de synchronisation
      if($abreviation_fta_transition=="V")
      {
          //Ouverture de la base ERP Data sync
          if ($globalConfig->getConf()->getExecEnvironment() != EnvironmentConf::ENV_PRD){
             $extension="mdb";
          }
          else{
             $extension="agismde";
          }
          $open_erpdatasync="../access/base_erp_datasync/erp_datasync.".$extension;
          header ("Location: open_erpdatasync.php?open_erpdatasync=$open_erpdatasync");
      }else
      {
        if($t<>1)
        {
          header ("Location: index.php");
        }
      }

}//Fin du traitement

/************
Fin de switch
************/


}//Fin de l'exécution de la page

//include ("./action_bs.php");
//include ("./action_sm.php");

?>

