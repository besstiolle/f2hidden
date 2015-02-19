<?php
$lang['backbuttontext'] = 'Retour';
$lang['confirmresetstyle'] = 'Êtes-vous de vouloir réinitialiser la feuille de style à son contenu par défaut ?';
$lang['confirmresettemplate'] = 'Êtes-vous sûr(e) de vouloir réinitialiser le gabarit à son contenu par défaut ?';
$lang['confirmuninstall'] = 'Êtes-vous sûr(e de vouloir désinstaller le module ?';
$lang['defaultlinktext'] = 'Imprimer cette page';
$lang['description'] = 'Ce module permet de personnaliser l\'affichage de pages pour impression pour CMSMS.';
$lang['friendlyname'] = 'Impression';
$lang['help'] = '<b>Que fait ce module ?</b>
<br />
Il permet d\'insérer un lien dans votre page ou votre gabarit pour afficher une page imprimable.
<br />
Noter que, à moins que le paramètre <i>includetemplate = true</i> soit utilisé, le module permet l\'impression uniquement du contenu des pages du site.

<br /><br />
<b>Comment l\'utiliser ?</b>
<br />
Installer le module, puis aller dans l\'administration et modifier éventuellement les préférences des gabarits pour les liens et la page d\'impression.
<br />
Dans votre page où votre gabarit insérez à l\'endroit désiré cette syntaxe &nbsp;:<br />
<pre>
{cms_module module=\'CMSPrinting\' <i>params</i>}
</pre>
<br />ou simplement,<br />
<pre>
{print <i>params</i>}
</pre>
<br />pour utiliser le plugin d\'impression.
<br />';
$lang['help_class'] = 'Classe pour le lien, par défaut&nbsp; \'noprint\'';
$lang['help_class_img'] = 'Classe de la balise img si le paramètre showbutton est défini';
$lang['help_includetemplate'] = 'Si la valeur est \'true\' les options d\'impression sont pour l\'ensemble du gabarit, et pas seulement pour le contenu principal. Cela nécessite d\'utiliser une feuille de style CSS si le Mediatype \'Print\' est activé.';
$lang['help_linktemplate'] = 'Applicable à l\'action par défaut ce paramètre permet de spécifier un gabarit, autre que celui par défaut, à utiliser lors de l\'affichage du lien d\'impression.';
$lang['help_more'] = 'Placer des options supplémentaires pour la balise < a > lien < /a >';
$lang['help_onlyurl'] = 'Génèrer seulement l\'url, pas le lien complet';
$lang['help_printtemplate'] = 'Applicable à l\'action par défaut, ce paramètre permet de spécifier un gabarit de page, autre que celui par défaut, à utiliser lors du formatage pour l\'impression.';
$lang['help_popup'] = 'Définir ce paramètre en \'true\' et la page à imprimer s\'ouvrira dans une autre fenêtre';
$lang['help_script'] = 'Définir ce paramètre en \'true\' et du code JavaScript sera utilisé pour ouvrir automatiquement le dialogue d\'impression';
$lang['help_showbutton'] = 'Définir ce paramètre en \'true\' pour afficher un bouton graphique';
$lang['help_src_img'] = 'Afficher ce fichier image au lieu du défaut';
$lang['help_text'] = 'Remplacer le texte par défaut du lien impression texte';
$lang['linktemplate'] = 'Gabarit de lien';
$lang['notemplatecontent'] = 'Aucun contenu dans le nouveau gabarit...';
$lang['overridestyle'] = 'Feuille de style de remplacement';
$lang['overridestylereset'] = 'La feuille de style de remplacement a été réinitialisée avec succès';
$lang['overridestylesaved'] = 'La feuille de style de remplacement a été sauvegardée avec succès';
$lang['postinstall'] = 'Le module a été installé avec succès';
$lang['postuninstall'] = 'Le module a été désinstallé avec succès';
$lang['printtemplate'] = 'Gabarit d\'impression';
$lang['reset'] = 'Réinitialiser par défaut';
$lang['save'] = 'Sauvegarder';
$lang['savesettings'] = 'Sauvegarder les paramètres';
$lang['savetemplate'] = 'Sauvegarder le gabarit';
$lang['stylesheet'] = 'Feuille de style&nbsp;';
$lang['template'] = 'Gabarit&nbsp;';
$lang['templatereset'] = 'Le gabarit a été réinitialisé avec succès';
$lang['templatesaved'] = 'Le gabarit a été mis à jour avec succès';
$lang['type_CMSPrinting'] = 'CMSPrinting&nbsp;';
$lang['type_link'] = '&nbsp;Gabarit de lien';
$lang['type_print'] = '&nbsp;Gabarit impression';
$lang['upgraded'] = 'A été mis à jour à la version %s';
?>