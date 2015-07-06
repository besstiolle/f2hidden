<?php
$lang['act_cachecleaned'] = 'Cache vid&eacute;';
$lang['add_row'] = 'Ajouter une ligne';
$lang['alias'] = 'Alias&nbsp;';
$lang['aliases'] = 'Alias&nbsp;';
$lang['ask_clear_image_cache'] = '&Ecirc;tes-vous s&ucirc;r de vouloir supprimer TOUTES les images en cache ?';
$lang['clear_all'] = 'Supprimer tous les fichiers';
$lang['clear_now'] = 'Supprimer maintenant';
$lang['delete'] = 'Effacer';
$lang['embedding'] = 'Images int&eacute;gr&eacute;es';
$lang['embedding_mode'] = 'Mode d&#039;int&eacute;gration des images (au sein du code HTML)';
$lang['embed_sizelimit'] = 'Taille limite des images int&eacute;gr&eacute;es (Ko) ';
$lang['embed_types'] = 'Type(s) d&#039;image(s) int&eacute;grable(s) ';
$lang['error_alias_duplicatename_atrow'] = 'Nom d&#039;alias dupliqu&eacute; pour l&#039;alias de la ligne %d';
$lang['error_alias_noname_atrow'] = 'Aucun nom n&#039;a &eacute;t&eacute; sp&eacute;cifi&eacute; pour l&#039;alias &agrave; la ligne %d';
$lang['error_alias_nooptions_atrow'] = 'Aucune option n&#039;a &eacute;t&eacute; sp&eacute;cifi&eacute;e pour l&#039;alias &agrave; la ligne %d';
$lang['error_invalid_age'] = 'Pr&eacute;f&eacute;rence d&#039;&acirc;ge invalide... Aucune op&eacute;ration r&eacute;alis&eacute;e';
$lang['error_missingparam'] = 'Un param&egrave;tre requis est manquant ou invalide : %s';
$lang['error_invalidparam'] = 'Param&egrave;tre invalide dans l&#039;appel : %s';
$lang['error_srcnotfound'] = 'Le fichier suivant n&#039;a pas &eacute;t&eacute; trouv&eacute; : %s';
$lang['error_remotefile'] = 'Probl&egrave;me de r&eacute;cup&eacute;ration du fichier distant depuis %s';
$lang['error_unknownfilter'] = 'Traitement d&#039;images - Filtre inconnu : %s';
$lang['friendlyname'] = 'Traitement d&#039;images (CG)';
$lang['general'] = 'G&eacute;n&eacute;ral';
$lang['info_aliases'] = 'Les alias peuvent &ecirc;tre utilis&eacute;s via les options alias1 &agrave; alias5 des balises CGSmartImage pour combiner plusieurs options fr&eacute;quemment utilis&eacute;es.';
$lang['info_croptofit_default_loc'] = 'Specify the default location from which to &quot;crop to fit&quot; this will effect all crop to fit operations unless the location parameter on the filter_croptofit attribute overrides it.  Default location is &quot;center&quot;';
$lang['image_url_hascachedir'] = 'L&#039;URL ci-dessus est-elle le r&eacute;pertoire de cache (_CGSmartImage) ?';
$lang['image_url_prefix'] = 'Pr&eacute;fixe de l&#039;URL de l&#039;image ';
$lang['info_cache_age'] = 'Sp&eacute;cifie l&#039;&acirc;ge maximum d&#039;un fichier-image (en jours) avant qu&#039;il soit supprim&eacute; et reg&eacute;n&eacute;r&eacute;. Ceci est utile si vous avez chang&eacute; certains param&egrave;tres de g&eacute;n&eacute;ration d&#039;images, ou pour r&eacute;guler l&#039;espace disque utilis&eacute;. Une valeur de &quot;0&quot; d&eacute;sactive la suppression automatique.';
$lang['info_embed_mode'] = 'Ce param&egrave;tre d&eacute;finit la mani&egrave;re dont le module va g&eacute;n&eacute;rer l&#039;affichage :

<ul><li>Aucun : une balise HTML &quot;img&quot; classique sera utilis&eacute;e et l&#039;image ne sera pas int&eacute;gr&eacute;e dans la source</li>
<li>Smart : le syst&egrave;me va int&eacute;grer ou non l&#039;image dans le code selon son poids, son type et le navigateur qui affiche la page</li>
</ul><br />
Les autres modes r&eacute;aliseront une int&eacute;gration de l&#039;image bas&eacute;e sur le poids ou le type de celle-ci.';
$lang['info_embed_sizelimit'] = 'Si l&#039;int&eacute;gration est bas&eacute;e sur le poids de l&#039;image, pr&eacute;cisez ici le poids maximum (en kilo-octets) pour les images &agrave; int&eacute;grer (32ko est une valeur recommand&eacute;e).';
$lang['info_embed_types'] = 'Si l&#039;int&eacute;gration est bas&eacute;e sur le type de l&#039;image, pr&eacute;cisez ici les types d&#039;images qui devront &ecirc;tre int&eacute;gr&eacute;es, en les s&eacute;parant par des virgules. Exemple : &quot;.jpg,.png,.gif&quot;. La casse (majuscule/minuscule) n&#039;est pas prise en compte.';
$lang['info_image_url_hascachedir'] = 'Si votre CDN (r&eacute;seau de contenu) pointe directement vers le r&eacute;pertoire des images en cache, alors vous devrez peut-&ecirc;tre passer cette option &agrave; &quot;Oui&quot;';
$lang['info_image_url_prefix'] = 'Sp&eacute;cifie l&#039;URL &agrave; ajouter en pr&eacute;fixe aux images g&eacute;n&eacute;r&eacute;es. Par d&eacute;faut, c&#039;est l&#039;URL du r&eacute;pertoire &quot;uploads&quot;, mais si vous avez votre propre r&eacute;seau de contenu (Content Delivery Network), ou plusieurs domaines pointant vers le m&ecirc;me r&eacute;pertoire, vous voudrez peut-&ecirc;tre sp&eacute;cifier une adresse diff&eacute;rente ici.';
$lang['max_cache_age'] = '&Acirc;ge maximum des fichiers en cache (en jours) ';
$lang['moddescription'] = 'Un module de g&eacute;n&eacute;ration d&#039;images et de balises-images pour CMSMS';
$lang['msg_aliases_updated'] = 'Les alias ont &eacute;t&eacute; mis &agrave; jour';
$lang['msg_cachecleaned'] = 'R&eacute;pertoire cache vid&eacute;. %d fichiers supprim&eacute;s';
$lang['msg_cacheremoved'] = 'Cache supprim&eacute;';
$lang['msg_prefsupdated'] = 'Pr&eacute;f&eacute;rences modifi&eacute;es';
$lang['none'] = 'Aucun';
$lang['options'] = 'Options&nbsp;';
$lang['param_alias'] = 'Le panneau d&#039;administration du module vous permet de cr&eacute;er plusieurs alias de commande qui combinent des mod&egrave;les d&#039;arguments fr&eacute;quemment utilis&eacute;s, sous un nom. Pour utiliser ces alias, utilisez un argument de la forme suivante : alias##=min_alias. Par ex : alias1=foo alias2=foo.';
$lang['param_alt'] = 'Utilis&eacute; lors de la cr&eacute;ation d&#039;un tag img, sp&eacute;cifie la valeur de l&#039;attribut alt. Notez que, si ce n&#039;est pas sp&eacute;cifi&eacute;, une valeur sera automatiquement calcul&eacute;e pour cet attribut de sort que la plupart des tags img soient valides. Vous pouvez inhiber ce calcul automatique avec la param&egrave;tre &amp;quot;noauto&amp;quot;';
$lang['param_class'] = 'Utilis&eacute; lors de la cr&eacute;ation d&#039;un tag <img>. Permet d&#039;ajouter une ou plusieurs classes CSS au tag, par exemple : class=&quot;fancybox thumbnail&quot;';
$lang['param_filter_blur'] = '(toutes valeurs) Sp&eacute;cifiez une valeur pour ce param&egrave;tre pour ajouter un effet de flou sur l&#039;image';
$lang['param_filter_brightness'] = '(entier) Augmente la luminosit&eacute; de l&#039;image g&eacute;n&eacute;r&eacute;e selon l&#039;entier sp&eacute;cifi&eacute;';
$lang['param_filter_colorize'] = '(r,g,b[,alpha]) Identique &agrave; &quot;filter_greyscale&quot; sauf que vous pouvez sp&eacute;cifier la couleur et la valeur alpha (transparence)';
$lang['param_filter_contrast'] = '(entier) Modifie le contraste de l&#039;image g&eacute;n&eacute;r&eacute;e selon l&#039;entier sp&eacute;cifi&eacute;';
$lang['param_filter_crop'] = '(pourcentage[,alignement_horizontal,alignement_vertical]) - Effectue un recadrage sur l&#039;image sp&eacute;cifi&eacute;e. Les param&egrave;tres de recadrage sont sp&eacute;cifi&eacute;s sous forme d&#039;une liste de param&egrave;tres s&eacute;par&eacute;s par une virgule. La premi&egrave;re valeur (obligatoire) est un pourcentage de la taille originale de l&#039;image. Le seconds param&egrave;tre (optionnel) doit &ecirc;tre choisi parmi &#039;l&#039;, &#039;c&#039;, ou &#039;r&#039; (respectivement pour gauche(left), centre(center), droit(right)). Le troisi&egrave;me param&egrave;tre (optionnel) doit &ecirc;tre choisi parmi &#039;t&#039;, &#039;c&#039;, ou &#039;b&#039; (respectivement pour haut(top), centre(center), bas(bottom)). Ces deux derniers param&egrave;tres sp&eacute;cifient la position au sein de l&#039;image originale &agrave; laquelle appliquer le recadrage. e.g. : crop=33,b,r pour effectuer un recadrage de 33% &agrave; partir du bas droit de l&#039;image originale.';
$lang['param_filter_croptofit'] = '(largeur,hauteur) - Effectue un recadrage sur l&#039;image sp&eacute;cifi&eacute;e pour en adapter la taille. Cela tente de redimensionner l&#039;image &agrave; la taille d&eacute;sir&eacute;e en gardant le ratio d&#039;aspect, puis recentre l&#039;image redimensionn&eacute;e. Les param&egrave;tres sp&eacute;cifi&eacute;s correspondent &agrave; la hauteur et la largeur d&eacute;sir&eacute;s pour l&#039;image de destination.';
$lang['param_filter_edgedetect'] = '(toutes valeurs) Accentue les bords de l&#039;image';
$lang['param_filter_emboss'] = '(toutes valeurs) &quot;Repousse&quot; l&#039;image';
$lang['param_filter_flip'] = '(mode) - Applique une rotation &agrave; l&#039;image.  0 = horizontal, 1 = vertical, et 2 = dans les deux directions.';
$lang['param_filter_grayscale'] = '(toutes valeurs) Convertit l&#039;image en niveaux de gris';
$lang['param_filter_meanremoval'] = '(toutes valeurs) Tente d&#039;ajouter un effet &quot;sketchy&quot;';
$lang['param_filter_negate'] = '(toutes valeurs) Passe l&#039;image en n&eacute;gatif';
$lang['param_filter_pixelate'] = '(taille[,avanc&eacute;]) Pix&eacute;lise l&#039;image. Sp&eacute;cifiez un entier pour le poids et une valeur bool&eacute;enne optionnelle (true/false - false par d&eacute;faut) pour permettre une pix&eacute;lisation avanc&eacute;e.';
$lang['param_filter_resize'] = 'type,number[,number] - Perform a resize of the source image.  Possible values are:
<ul>
  <li>p,number - Perform a simple rescale to a certain percentage.  i.e:  resize=p,50 to resize to 50% of the original size.</li>
  <li>w,number - Perform a resize to a specified width (while retaining aspect ratio). i.e: resize=w,80 to create a thumbnail with a maximum width of 80 pixels.</li>
  <li>h,number - Perform a resize to a specified height (while retaining aspect ratio). i.e: resize=h,80 to create a thumbnail with a maximum height of 80 pixels.</li>
  <li>c,x,y - Perform a resize to a custom size (without retaining aspect ratio).  i.e: resize=c,50,75 to create a thumbnail that is 50x75 pixels.</li>
</ul>';
$lang['param_filter_resizetofit'] = '(width,height[,color[,alpha]]) - Perform a resize on the image specified. This attempts to rescale the image to the destination size while reatining aspect ratio, the image is centered in the box specified (either horizontally or vertically depending upon aspect ratio and the destination size, and the image is surrounded by the supplied color.  Colors can be specified by name (see the X11 color names), or by #nnnnnn hexadecimal format, or as rgb values separated by a : i.e:  filter_croptofit=600,400,#ff0000.  The special color value &quot;transparent&quot; can be specified to force the background to be transparent.   An alpha value may be specified between 0 and 127 to specify different degrees of translucency for the background.   At no time will this plugin perform any upscaling.';
$lang['param_filter_rotate'] = '(angle,couleur) Sp&eacute;cifie l&#039;angle (sens des aiguilles d&#039;une montre) de rotation de l&#039;image, et la couleur de remplissage pour les pixels vides. La rotation s&#039;effectue &agrave; partir du centre de l&#039;image.';
$lang['param_filter_roundedcorners'] = '(rayon) Sp&eacute;cifie une valeur de rayon (en pixels) pour arrondir les angles.';
$lang['param_filter_watermark'] = '(toutes valeurs) Sp&eacute;cifie que le filigrane (r&eacute;gl&eacute; dans le module CGExtensions) doit &ecirc;tre appliqu&eacute; &agrave; l&#039;image';
$lang['param_height'] = 'Used when creating an img tag, specify the value for the height attribute.  Note, if this is not specified a value will be automatically calculated for this attribute so that most generated img tags will validate.  You can override this auto calculation with the &amp;quot;noauto&amp;quot; parameter.  This parameter will also be used in conjunction with the &amp;quot;width&amp;quot; parameter to perform a final resizing or croptofit filter (unless the &amp;quot;noautoscale&amp;quot; parameter is also supplied)';
$lang['param_id'] = 'Utilis&eacute; lors de la cr&eacute;ation d&#039;un tag img, permet de sp&eacute;cifier un attribut id &agrave; inclure au tag. Par ex : id=&quot;montag&quot;';
$lang['param_max_height'] = 'Utilis&eacute; uniquement avec la balise {cgsi_convert}, ce param&egrave;tre permet de sp&eacute;cifier la hauteur max. pour les images converties';
$lang['param_max_width'] = 'Utilis&eacute; uniquement avec la balise {cgsi_convert}, ce param&egrave;tre permet de sp&eacute;cifier la largeur max. pour les images converties';
$lang['param_name'] = 'Utilis&eacute; lors de la cr&eacute;ation du tag img, sp&eacute;cifie la valeur pour l&#039;attribut name.';
$lang['param_noauto'] = 'Ne pas calculer automatiquement les attributs (width, height, alt) pour la balise &quot;img&quot;. Ceci peut faire en sorte que votre code ne soit pas valide si vous ne sp&eacute;cifiez pas les attributs &quot;width&quot;, &quot;height&quot; et &quot;alt&quot;.';
$lang['param_noautoscale'] = 'Si les param&egrave;tres hauteur et largeur sont sp&eacute;cifi&eacute;s, ce param&egrave;tres d&eacute;sactive le redimensionnement automatique de l&#039;image.';
$lang['param_nobcache'] = 'Do not allow the resized image to cache in the browser (useful for development purposes) this adds a unique number as a parameter to the image which will force the browser not to cache the image.';
$lang['param_noembed'] = 'Force l&#039;image &agrave; ne pas &ecirc;tre int&eacute;gr&eacute;e encod&eacute;e dans le code source. En d&eacute;pit de sa configuration dans l&#039;admin, le module va g&eacute;n&eacute;rer un fichier image sur le serveur.';
$lang['param_notimecheck'] = 'Disable time checking of cached files.';
$lang['param_norotate'] = 'Ne pas tenter de lire les informations exif depuis le fichier pour corriger la rotation de l&#039;image';
$lang['param_notag'] = 'Ne pas g&eacute;n&eacute;rer de balise &quot;img&quot;, mais uniquement l&#039;URL de l&#039;image en cache. Ceci n&#039;a aucun effet quand CGSmartImage est utilis&eacute; depuis une feuille de styles.';
$lang['param_overwrite'] = 'D&eacute;sactive tout le cache et force &agrave; recalculer les filtres';
$lang['param_quality'] = 'Sp&eacute;cifier la qualit&eacute; de l&#039;image affich&eacute;e.  Mettre une valeur entre 0 et 100.';
$lang['param_rel'] = 'Utilis&eacute; lors de la cr&eacute;ation d&#039;une balise d&#039;image, il permet de sp&eacute;cifier un attribut rel optionnel (utilis&eacute; typiquement dans les albums javascript). e.g: rel=&quot;album&quot;';
$lang['param_silent'] = 'Silently ignore errors';
$lang['param_src'] = 'Specify the source for the image processing (if any) or the generated img tag.  Note, this parameter is flexible, and the module will attempt many methods to find the source image file on the web server as follows:
<ul>
  <li>First look to see if the specified src value exists as a file on the filesystem.</li>
  <li>Next, check if the value specified for the src parameter is a URL and begins with the uploads_url (as specified in the config.php)</li>
  <li>Next, check if the value specified for the src parameter is a URL and begins with the root_url (as specified in the config.php)</li>
  <li>Next, check if the value specified for the src parameter is a URL and begins with the ssl_url (as specified in the config.php)</li>
</ul>
It is also possible to split the src parameter into multiple arguments, which is useful for example when the path to the file, and the filename are stored in separate smarty variables.   You can specify the src as src1=\$the_path src2=\$the_filename.  The system will automatically add a / in between each argument.  There is no limit to the number of src parameters.';
$lang['param_style'] = 'Utilis&eacute; pour la balise &quot;img&quot;, permet de sp&eacute;cifier un style alternatif pour la balise.  Par ex : style=&quot;border: 1px solid black;&quot;';
$lang['param_title'] = 'Renseignez un titre optionnel pour la balise &quot;img&quot;.  Par ex : title=&quot;Ceci est une astuce&quot;';
$lang['param_width'] = 'Utilis&eacute; lors de la cr&eacute;ation d&#039;une balise &quot;img&quot; : sp&eacute;cifie l&#039;attribut &quot;width&quot; (largeur). Note : si cette valeur n&#039;est pas sp&eacute;cifi&eacute;e, une valeur va &ecirc;tre automatiquement calcul&eacute;e. Vous pouvez d&eacute;sactiver ce calcul automatique avec le param&egrave;tre &quot;noauto&quot;.';
$lang['postinstall'] = 'Module install&eacute;';
$lang['postuninstall'] = 'Toutes les donn&eacute;es relatives &agrave; ce module ont &eacute;t&eacute; supprim&eacute;es';
$lang['prompt_croptofit_default_loc'] = 'Position &agrave; partir de laquelle recadrer :';
$lang['prompt_embed_sizelimit'] = 'Int&eacute;gration bas&eacute;e sur le poids de l&#039;image';
$lang['prompt_embed_smartlimited'] = 'Int&eacute;gration en mode Smart mais limite le poids de l&#039;image';
$lang['prompt_embed_type'] = 'Int&eacute;gration bas&eacute;e sur le type de l&#039;image';
$lang['prompt_loc_bottomleft'] = 'En bas &agrave; gauche (bg)';
$lang['prompt_loc_bottomcenter'] = 'En bas au centre (bc)';
$lang['prompt_loc_bottomright'] = 'En bas &agrave; droite (bd)';
$lang['prompt_loc_centerleft'] = 'Au centre &agrave; gauche (cg)';
$lang['prompt_loc_center'] = 'Au centre';
$lang['prompt_loc_centerright'] = 'Au centre &agrave; droite (cd)';
$lang['prompt_loc_topleft'] = 'En haut &agrave; gauche (hg)';
$lang['prompt_loc_topcenter'] = 'En haut au centre (hc)';
$lang['prompt_loc_topright'] = 'En haut &agrave; droite (hd)';
$lang['resizing'] = 'Redimensionnement';
$lang['smart'] = 'Smart ';
$lang['submit'] = 'Envoyer';
$lang['utma'] = '156861353.767691233.1332021834.1332021834.1332021834.1';
$lang['utmz'] = '156861353.1332021834.1.1.utmccn=(referral)|utmcsr=dev.cmsmadesimple.org|utmcct=/|utmcmd=referral';
$lang['qca'] = '4a3d1b41-5eb36-c9570-f49ea';
$lang['utmc'] = '156861353';
$lang['utmb'] = '156861353';
?>
