<?php

abstract class ModuleConfig {
    /*     * *************************
      VARIABLES GLOBALES DU MODULE
     * ************************* */


    /*
      Permet d'activer le mode de débuggage.
      Par exemple, en activant ce mode, vous pouvez désactiver des chapitres validés
      0 = Le mode est désactivé
      1 = Le mode standard est activé
     */
    const MODE_DEBUG = FALSE;

    /*
      Permet d'activer ou de désactiver le système d'information par mail
      0 = Désactiver
      1 = Activer
     */
    const NOTIFY_MAIL_WORKFLOW = TRUE;


    /*
      Limite maximale du poids net d'un colis en Kg
     */
    const MAX_POIDS_NET_COLIS = 12;

    /*
      Limite maximale du nombre de FTA affichée dans l'index
     */
    const LIMIT_AFFICHAGE_FTA = 50;

    /**
     * Le code article LDC peut-il être utilisé sur plusieurs FTA ?
     * Ce contrôle est effectuée uniquement au moment de l'enregistrement
     * de la FTA.
     */
    const CODE_LDC_UNIQUE = true;

    /**
     * Nombre de jours par défaut utilisé pour calculer l'échéance d'un processus
     */
    const DELAI_ECHEANCE_PROCESSUS_JOUR = 7;

    /**
     * Activer la visualisation des modifications effectuées depuis la version précédente
     */
    const ENABLE_SHOW_DIFF_FTA = true;
}

?>