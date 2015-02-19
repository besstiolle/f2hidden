<?php
$lang['description'] = 'Ce module fournit un moyen simple et facile de générer le code HTML nécessaire pour une navigation de site Web direct et dynamique à partir de la structure des pages de CMSMS™. Il fournit un filtrage flexible et des capacités de création de gabarits pour construire des navigations de site Web puissant, rapide et attrayant sans interaction avec l\'éditeur de contenu.';
$lang['friendlyname'] = 'CMSMS™ Navigation Builder';
$lang['help'] = '<h3>Que fait ce module&nbsp;?</h3>
  <p>Le module "Navigator" permet de gérer les menus de navigation du contenu et des gabarits de CMSMS™. Ce module fournit des fonctionnalités de filtrage étendues pour permettre la construction de nombreuses navigations sur la base de différents critères, avec une simple hiérarchie des données pour générer des navigations avec une flexibilité totale.</p>
  <p>Ce module n\'a pas d\'interface d\'administration propre, il utilise donc le DesignManager pour générer les gabarits des menus.</p>
<h3>Comment l\'utiliser ?</h3>
<p>Insérez simplement la balise dans votre gabarit <code>{Navigator}</code>. Le module accepte de nombreux paramètres pour modifier son comportement et filtrer les données.</p>
<h3>Que dois-je faire sur les gabarits ?</h3>
<p>C\'est la puissance de CMSMS™. Les menus de navigations peuvent être construits en utilisant automatiquement les données de votre hiérarchie de contenu, et un gabarit Smarty. Il n\'est pas nécessaire de modifier un objet de navigation à chaque fois qu\'une page de contenu est ajouté ou supprimée du système. En outre, les gabarits de navigation peuvent facilement inclure des fonctionnalités JavaScript ou avancées et peuvent être partagés entre les utilisateurs de sites Web.</p>
<p>Le module est distribué avec quelques modèles, qui ne sont que des exemples. Vous êtes libre et encouragé à copier et modifier les gabarits à votre goût. le style de la navigation se fait en éditant une feuille de style CMSMS™. Les feuilles de style ne sont pas incluses avec le module Navigator.</p>
<h3>L\'objet node :</h3>
  <p>Chaque gabarit Navigator est un tableau "object node" qui correspondent aux critères spécifiés sur la balise. Ci-dessous une description des membres de l\'objet node :</p>
<ul>
			<li>$node->id -- ID de l\'élément</li>
			<li>$node->url -- URL de l\'élément</li>
			<li>$node->accesskey -- Access Key, si définie</li>
			<li>$node->tabindex -- Tab Index, si défini</li>
			<li>$node->titleattribute -- Description ou attribut titre, si défini</li>
			<li>$node->hierarchy -- Position hiérarchique (exemple : 1.3.3)</li>
  <li>$node->default -- TRUE si ce node se réfère à l\'objet de contenu par défaut.</li>
			<li>$node->menutext -- Texte du menu</li>
			<li>$node->raw_menutext -- Texte du menu sans convertir les entités HTML</li>
			<li>$node->alias -- Alias de la page</li>
			<li>$node->extra1 -- Ce champ contient la valeur de la propriété de page extra1, sauf si le paramètre loadprops, contenu dans la balise (tag) menu, est réglé pour ne PAS charger les propriétés.</li>
			<li>$node->extra2 -- Ce champ contient la valeur de la propriété de page extra2, sauf si le paramètre loadprops , contenu dans la balise (tag) menu, est réglé pour ne PAS charger les propriétés.</li>
			<li>$node->extra3 -- Ce champ contient la valeur de la propriété de page extra3, sauf si le paramètre loadprops , contenu dans la balise (tag) menu, est réglé pour ne PAS charger les propriétés.</li>
			<li>$node->image -- Ce champ contient la valeur de la propriété de l\'image dans la page (si non vide), sauf si le paramètre loadprops , contenu dans la balise (tag) menu, est réglé pour ne PAS charger les propriétés.</li>
			<li>$node->thumbnail -- Ce champ contient la valeur de la propriété de page de la vignette de l\'image(si non vide), sauf si le paramètre loadprops , contenu dans la balise (tag) menu, est réglé pour ne PAS charger les propriétés.</li>
			<li>$node->target -- Cette zone contient la cible du lien (si non vide), sauf si le paramètre loadprops , contenu dans la balise (tag) menu, est réglé pour ne PAS charger les propriétés.</li>
  <li>$node->created -- Date de création de l\'élément</li>
  <li>$node->modified -- Date de modification de l\'élément</li>
			<li>$node->parent -- Renvoie true (vrai) si cet élément est le parent de la page actuelle</li>
			<li>$node->current -- Renvoie true (vrai) si cet élément est la page sélectionnée</li>
	<li>\$node->has_children -- TRUE si le node a des enfants (if this node has any children at all).</li>
	<li>\$node->children -- un tableau objets représentant les nodes enfants de ce node affichables. Non défini si le nœud n\'a pas d\'enfants à afficher.</li>
</ul>
<h3>Exemples :</h3>
   <br />- Affiche une navigation simple à seulement 2 niveaux, en utilisant le modèle par défaut :<br />
     <pre><code>{Navigator number_of_levels=2}</code></pre>
   
     <br />- Affiche une navigation simple à deux niveaux en commençant par les enfants de la page en cours, en utilisant le modèle par défaut :<br />
     <pre><code>{Navigator number_of_levels=2 start_page=$page_alias}</code></pre>
  
   <br />- Affiche une navigation simple à deux niveaux en commençant par les enfants de la page courante, en utilisant le modèle par défaut :<br />
     <pre><code>{Navigator number_of_levels=2 childrenof=$page_alias}</code></pre>
  
   <br />- Affiche une navigation simple à deux niveaux à partir de la page en cours, et les pages suivantes, en utilisant le modèle par défaut :<br />
     <pre><code>{Navigator number_of_levels=2 start_page=$page_alias}</code></pre>

   <br />- Affiche une navigation à deux niveaux à partir de la page en cours, de ses pairs, et les pages suivantes, en utilisant le modèle par défaut :<br />
     <pre><code>{Navigator start_page=$page_alias show_root_siblings=1}</code></pre>

   <br />- Affiche une navigation des menus spécifiques et leurs enfants, en Utilisant le gabarit "MyMenu" :<br />
     <pre><code>{Navigator items=\'alias1,alias2,alias3\' number_of_levels=20 template=mymenu }</code></pre>';
$lang['help_action'] = 'Spécifier l\'action du module. Ce module prend en charge deux actions :
<ul>
<li><em>default</em> - Utilisé pour construire une navigation primaire. (cette action est implicite si aucune action n\'est spécifié).</li>
<li>breadcrumbs - Utilisé pour construire une mini navigation (fil d\'Ariane)comprenant le chemin depuis la racine du site jusqu\'à la page en cours.</li>
</ul>';
$lang['help_collapse'] = 'Si activée, seuls les éléments directement liés à la page courante active seront en sortie.';
$lang['help_childrenof'] = 'Cette option affichera le menu uniquement des éléments qui sont descendants depuis l\'ID ou l\'alias de la page sélectionnée. Exemple : <code>{menu childrenof=$page_alias}</code> affichera uniquement les enfants de la page courante.';
$lang['help_excludeprefix'] = 'Exclure tous les éléments (et leurs enfants) dont l\'alias de page correspond à l\'un des préfixes spécifiés (le séparateur sera la virgule). Ce paramètre ne doit pas être utilisé en conjonction avec le paramètre includeprefix.';
$lang['help_includeprefix'] = 'Inclure seulement les éléments auquel l\'alias de page correspond à l\'un des préfixes (séparés par des virgules). Ce paramètre ne peut pas être combiné avec le paramètre excludeprefix.';
$lang['help_items'] = 'Spécifier une liste des alias, séparés par des virgules, que ce menu doit afficher.';
$lang['help_loadprops'] = 'Utiliser ce paramètre lorsque vous n\'utilisez PAS les propriétés avancées  dans votre gabarit de gestionnaire de menu. Ce paramètre permet de désactiver le chargement de toutes les propriétés des contenus de tous les nœuds (tel que extra1, image, thumbnail, etc). Cette opération réduira considérablement le nombre de requêtes nécessaires à la construction d\'un menu en contrepartie la consommation en mémoire augmente, mais cela permet de gérer des menus plus avancés.';
$lang['help_nlevels'] = 'Alias pour number_of_levels (nombre de niveaux)';
$lang['help_number_of_levels'] = 'Ce réglage limite la profondeur du menu généré au nombre spécifié de niveaux. Par défaut la valeur de ce paramètre est illimité pour montrer tous les niveaux enfants, sauf si vous utilisez simultanément le paramètre "items" dans ce cas number_of_levels est par défaut égal à 1';
$lang['help_root'] = 'Utilisé uniquement dans l\'action breadcrumbs ce paramètre indique que le fil d\'Ariane n\'aille pas plus loin vers le haut de l\'arborescence de la page que l\'alias de page spécifié.';
$lang['help_show_all'] = 'Cette option affichera tous les niveaux même s\'ils sont configuré pour ne pas être afficher dans le menu. Il n\'affichera pas les pages inactives.';
$lang['help_show_root_siblings'] = 'Cette option est utile lorsque start_element ou start_page est utilisé. Les autres éléments du même niveau que l\'élément sélectionné seront affichés.';
$lang['help_start_element'] = 'Cette option permet d\'afficher uniquement les éléments à partir d\'un élément donné (start_element), ainsi que les niveaux en-dessous de cet élément.  la valeur doit être égale à la position hiérarchique de l\'élément (exemple : 5.1.2).';
$lang['help_start_level'] = 'Grâce à cette option un menu n\'affichera des éléments qu\'à partir d\'un niveau donné. Prenons un exemple simple : vous avez un premier menu sur une page avec le paramètre number_of_level=\'1\'. Puis un deuxième menu avec start_level=\'2\'. Maintenant, votre second menu affichera ses éléments en fonction de ce qui est sélectionné sur le premier menu.';
$lang['help_start_page'] = 'Cette option permet d\'afficher uniquement les éléments à partir d\'une page donnée (start_page), ainsi que les niveaux en-dessous de cet élément.  la valeur doit être égale à l\'alias de l\'élément.';
$lang['help_template'] = 'Le gabarit à utiliser pour l\'affichage du menu. Le gabarit doit exister dans le module DesignManager sinon une erreur sera affichée. Si ce paramètre n\'est pas spécifié le gabarit par défaut de type Navigator::Navigation sera utilisé.';
$lang['help_start_text'] = 'Utile que dans l\'action breadcrumbs, ce paramètre permet de spécifier un texte facultatif à afficher au début du fil d\'ariane. Un exemple serait "Ici".';
$lang['type_breadcrumbs'] = 'Fil d\'Ariane';
$lang['type_Navigator'] = 'Navigator&nbsp;';
$lang['type_navigation'] = 'Navigation&nbsp;';
$lang['youarehere'] = 'Ici&nbsp;';
?>