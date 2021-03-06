# Mise à jour de la structure de la table "fta" pour qu'elle accueil les données "access_arti2"

ALTER TABLE `fta` 
ADD `date_creation` date DEFAULT '0000-00-00',
ADD `CODE_ARTICLE` int(11) DEFAULT NULL,
ADD `code_article_client` varchar(50) DEFAULT NULL,
ADD `code_article_ldc` int(11) DEFAULT NULL,
ADD `LIBELLE` varchar(50) DEFAULT NULL,
ADD `LIBELLE_CLIENT` varchar(50) DEFAULT NULL,
ADD `NB_UNIT_ELEM` float DEFAULT NULL,
ADD `NB_UV_PAR_US1` float DEFAULT NULL,
ADD `Poids_ELEM` float DEFAULT NULL,
ADD `REGROUPEMENT` int(11) DEFAULT NULL,
ADD `UL2` int(11) DEFAULT NULL,
ADD `RGR2` int(11) DEFAULT NULL,
ADD `Unite_Facturation` smallint(6) DEFAULT NULL,
ADD `Rayon` smallint(6) DEFAULT NULL,
ADD `actif` tinyint(1) DEFAULT '-1',
ADD `Site_de_production` tinyint(4) NOT NULL DEFAULT '0',
ADD `Duree_de_vie` smallint(6) DEFAULT NULL,
ADD `Duree_de_vie_technique` smallint(6) DEFAULT NULL,
ADD `code_barre_specifique` tinyint(1) NOT NULL DEFAULT '0',
ADD `transfert_PF` tinyint(1) DEFAULT NULL,
ADD `Zone_picking` varchar(10) DEFAULT NULL,
ADD `fiche_palette_specifique` smallint(4) NOT NULL DEFAULT '0',
ADD `TARIF` double DEFAULT NULL,
ADD `pvc_article` float DEFAULT '0',
ADD `pvc_article_kg` float DEFAULT '0',
ADD `FAMILLE_BUDGET` double DEFAULT NULL,
ADD `FAMILLE_ARTICLE` int(11) DEFAULT NULL,
ADD `id_access_familles_gammes` int(11) DEFAULT '0',
ADD `Cout_Denree` float DEFAULT NULL,
ADD `Cout_Emballage` float DEFAULT NULL,
ADD `Cout_Autre` float DEFAULT NULL,
ADD `Cout_PF` float DEFAULT NULL,
ADD `FAMILLE_MKTG` int(11) DEFAULT NULL,
ADD `Composition` mediumtext,
ADD `composition1` mediumtext,
ADD `libelle_multilangue` varchar(65) DEFAULT NULL,
ADD `K_etat` int(11) DEFAULT NULL,
ADD `EAN_UVC` bigint(13) unsigned DEFAULT NULL,
ADD `EAN_COLIS` varchar(14) DEFAULT NULL,
ADD `EAN_PALETTE` varchar(14) NOT NULL DEFAULT '',
ADD `nouvel_article` int(11) DEFAULT NULL,
ADD `k_gestion_lot` tinyint(4) NOT NULL DEFAULT '1',
ADD `activation_codesoft_arti2` tinyint(4) NOT NULL DEFAULT '1',
ADD `id_etiquette_codesoft_arti2` tinyint(4) NOT NULL DEFAULT '0',
ADD UNIQUE KEY `access_arti2_CODE_ARTICLE` (`CODE_ARTICLE`),
ADD KEY `code_article_ldc` (`code_article_ldc`),
ADD KEY `EAN_UVC` (`EAN_UVC`),
ADD KEY `EAN_COLIS` (`EAN_COLIS`),
ADD KEY `FAMILLE_MKTG` (`FAMILLE_MKTG`),
ADD KEY `FAMILLE_ARTICLE` (`FAMILLE_ARTICLE`),
ADD KEY `FAMILLE_BUDGET` (`FAMILLE_BUDGET`),
ADD KEY `id_access_familles_gammes` (`id_access_familles_gammes`)
;

# Mise à jour des données de la table "fta" pour qu'elle intègre toutes les
# données de la table access_arti2

UPDATE fta, access_arti2 SET
fta.`date_creation`=access_arti2.`date_creation`,
fta.`CODE_ARTICLE`=access_arti2.CODE_ARTICLE,
fta.`code_article_client`=access_arti2.`code_article_client`,
fta.`code_article_ldc`=access_arti2.`code_article_ldc`,
fta.`LIBELLE`=access_arti2.`LIBELLE`,
fta.`LIBELLE_CLIENT`=access_arti2.`LIBELLE_CLIENT`,
fta.`NB_UNIT_ELEM`=access_arti2.`NB_UNIT_ELEM`,
fta.`NB_UV_PAR_US1`=access_arti2.`NB_UV_PAR_US1`,
fta.`Poids_ELEM`=access_arti2.`Poids_ELEM`,
fta.`REGROUPEMENT`=access_arti2.`REGROUPEMENT`,
fta.`UL2`=access_arti2.`UL2`,
fta.`RGR2`=access_arti2.`RGR2`,
fta.`Unite_Facturation`=access_arti2.`Unite_Facturation`,
fta.`Rayon`=access_arti2.`Rayon`,
fta.`actif`=access_arti2.`actif`,
fta.`Site_de_production`=access_arti2.`Site_de_production`,
fta.`Duree_de_vie`=access_arti2.`Duree_de_vie`,
fta.`Duree_de_vie_technique`=access_arti2.`Duree_de_vie_technique`,
fta.`code_barre_specifique`=access_arti2.`code_barre_specifique`,
fta.`transfert_PF`=access_arti2.`transfert_PF`,
fta.`Zone_picking`=access_arti2.`Zone_picking`,
fta.`fiche_palette_specifique`=access_arti2.`fiche_palette_specifique`,
fta.`TARIF`=access_arti2.`TARIF`,
fta.`pvc_article`=access_arti2.`pvc_article`,
fta.`pvc_article_kg`=access_arti2.`pvc_article_kg`,
fta.`FAMILLE_BUDGET`=access_arti2.`FAMILLE_BUDGET`,
fta.`FAMILLE_ARTICLE`=access_arti2.`FAMILLE_ARTICLE`,
fta.`id_access_familles_gammes`=access_arti2.`id_access_familles_gammes`,
fta.`Cout_Denree`=access_arti2.`Cout_Denree`,
fta.`Cout_Emballage`=access_arti2.`Cout_Emballage`,
fta.`Cout_Autre`=access_arti2.`Cout_Autre`,
fta.`Cout_PF`=access_arti2.`Cout_PF`,
fta.`FAMILLE_MKTG`=access_arti2.`FAMILLE_MKTG`,
fta.`Composition`=access_arti2.`Composition`,
fta.`composition1`=access_arti2.`composition1`,
fta.`libelle_multilangue`=access_arti2.`libelle_multilangue`,
fta.`K_etat`=access_arti2.`K_etat`,
fta.`EAN_UVC`=access_arti2.`EAN_UVC`,
fta.`EAN_COLIS`=access_arti2.`EAN_COLIS`,
fta.`EAN_PALETTE`=access_arti2.`EAN_PALETTE`,
fta.`nouvel_article`=access_arti2.`nouvel_article`,
fta.`k_gestion_lot`=access_arti2.`k_gestion_lot`,
fta.`activation_codesoft_arti2`=access_arti2.`activation_codesoft_arti2`,
fta.`id_etiquette_codesoft_arti2`=access_arti2.`id_etiquette_codesoft_arti2`
WHERE fta.id_fta=access_arti2.id_fta
;
