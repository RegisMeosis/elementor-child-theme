<?php
require( '../../../wp-load.php');
if ($_POST['submit'] && $_POST['submit'] == "Configuration") {
	if( $_POST['societe'] && ( $_POST['societe'] <> '' ) && 
		$_POST['url'] && ( $_POST['url'] <> '' ) && 
		$_POST['director'] && ( $_POST['director'] <> '' ) && 
		$_POST['mail'] && ( $_POST['mail'] <> '' ) && 
		$_POST['rue'] && ( $_POST['rue'] <> '' ) && 
		$_POST['zip'] && ( $_POST['zip'] <> '' ) && 
		$_POST['ville'] && ( $_POST['ville'] <> '' ) && 
		$_POST['phone'] && ( $_POST['phone'] <> '' ) && 
		$_POST['siret'] && ( $_POST['siret'] <> '' ) ) {
		$societe = $_POST['societe'];
		$url = $_POST['url'];
		$director = $_POST['director'];
		$mail = $_POST['mail'];
		$rue = $_POST['rue'];
		$zip = $_POST['zip'];
		$ville = $_POST['ville'];
		$phone = $_POST['phone'];
		$siret = $_POST['siret'];
		if ( get_option('meo_installation') != 1 ) {
		/* Meo Configuration */
			update_option('wsp_posts_by_category', '<a href="{permalink}" title="{title}">{title}</a>');
			update_option('permalink_structure', '/%postname%-%post_id%.html');
		// /* LOCAL SEO */
		// 	$options = get_option( 'wpseo_local' );
		// 	$options['license'] = '8d62741daab34920ba21b0467873fb3d';
		// 	$options['license-status'] = 'valid';
		// 	$options['opening_hours_24h'] = '1';
		// 	$options['address_format'] = 'postal-address';
		// 	$options['location_name'] = $societe;
		// 	$options['location_address'] = $rue;
		// 	$options['location_city'] = $ville;
		// 	$options['location_zipcode'] = $zip;
		// 	$options['location_phone'] = $phone;
		// 	$options['location_email'] = $mail;
		// 	update_option( 'wpseo_local', $options );
		    
	    /* Activation de plugins */
		    $pluginActivation = get_option( 'active_plugins' );
		    if (!in_array('wp-sitemap-page/wp-sitemap-page.php', $pluginActivation)){
		        $pluginActivation[] = 'wp-sitemap-page/wp-sitemap-page.php';
		    }
		    if (!in_array('elementor/elementor.php', $pluginActivation)){
		        $pluginActivation[] = 'elementor/elementor.php';
		    }
		    if (!in_array('elementor-pro/elementor-pro.php', $pluginActivation)){
		        $pluginActivation[] = 'elementor-pro/elementor-pro.php';
		    }
		    if (!in_array('cookie-notice/cookie-notice.php', $pluginActivation)){
		        $pluginActivation[] = 'cookie-notice/cookie-notice.php';
		    }
		    update_option( 'active_plugins', $pluginActivation );
		   
	   	/* Paramétrage des permaliens Seo */
		    $wpseo = get_option( 'wpseo_permalinks' );
		    $wpseo['stripcategorybase'] = '1';
		    update_option( 'wpseo_permalinks', $wpseo );
	    /* Activation et paramétrage du fil d'ariane (old)*/
		    $wpseoBread = get_option( 'wpseo_internallinks' );
		    $wpseoBread['breadcrumbs-enable'] = '1';
		    $wpseoBread['breadcrumbs-sep'] = '›';
		    $wpseoBread['breadcrumbs-prefix'] = 'Vous êtes ici ›';
		    $wpseoBread['post_types-post-maintax'] = 'category';
		    update_option( 'wpseo_internallinks', $wpseoBread );
		/* Activation et paramétrage du fil d'ariane (new)*/
		    $wpseoBread = get_option( 'wpseo_titles' );
		    $wpseoBread['breadcrumbs-enable'] = '1';
		    $wpseoBread['breadcrumbs-sep'] = '›';
		    $wpseoBread['breadcrumbs-prefix'] = 'Vous êtes ici ›';
		    $wpseoBread['post_types-post-maintax'] = 'category';
		    update_option( 'wpseo_titles', $wpseoBread );
	    /* Désactivation du Seo Sitemap (old) */
		    $wpseoxml = get_option( 'wpseo_xml' );
		    $wpseoxml['enablexmlsitemap'] = '0';
		    update_option( 'wpseo_xml', $wpseoxml );
		/* Désactivation du Seo Sitemap (new) */
		    $wpseoxml = get_option( 'wpseo' );
		    $wpseoxml['enable_xml_sitemap'] = '0';
		    update_option( 'wpseo', $wpseoxml );
		/* Configuration cookie notice */
			$cookie = get_option( 'cookie_notice_options' );
	        $cookie['message_text'] = 'Ce site utilise des cookies pour vous offrir de meilleures conditions de navigation, et pour des raisons de mesure d’audience. En cliquant sur « OK », vous acceptez l’utilisation de cookies sur ce site. Pour en savoir plus et paramétrer les cookies <a href="/mentions-legales-politique-confidentialite.html">cliquez-ici</a>.';
	        update_option( 'cookie_notice_options', $cookie );
	    /* Ajout du formulaire de contact et des pages par défaut */
			add_items($url, $director, $mail, $rue, $zip, $ville, $phone, $societe, $siret);
		/* Ajout d'une option pour empêcher le duplicatat de l'installation */
			update_option( 'meo_installation', 1 );
			$notice = '<div class="notice notice-success"><p>Installation réussie.</p></div>';
		}	
	}
	else {
		$notice = '<div class="notice notice-error"><p>Erreur</p></div>';
	}
}
function add_mentions_legales($url, $director, $mail, $rue, $zip, $ville, $phone, $societe, $siret) {
	return "Ce site web est édité par :<br/>
	Raison sociale : $societe<br/>
	N° SIRET : $siret<br/>
	Représentant légal : $director<br/>
	Directeur de la publication : $director<br/>
	<br/>
	Adresse : $rue $zip $ville<br/>
	Email : $mail<br/>
	Tél. : $phone<br/>
	[mention_legales_shortcode]";
}
function add_items($url, $director, $mail, $rue, $zip, $ville, $phone, $societe, $siret) {
	global $wpdb;
	$ml = add_mentions_legales($url, $director, $mail, $rue, $zip, $ville, $phone, $societe, $siret);
	$defaultContent = 'Page en construction';
	$loremContent ='Lorem ipsum <em>emphasised text</em> dolor sit amet, <strong>strong text</strong> consectetur adipisicing elit, <abbr title="">abbreviated text</abbr> sed do eiusmod tempor <acronym title="">acronym text</acronym> incididunt ut labore et dolore magna aliqua. Ut <q>quoted text</q> enim ad minim veniam, quis nostrud exercitation <a href="/">link text</a> ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute <del>deleted text</del> <ins>inserted text</ins> irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat <code>code text</code> cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		<blockquote>Blockquote. Velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia</blockquote>
		<cite><a href="/">Cite author with link text</a></cite>, 2008
		<h2>Header 2</h2>
		Extended paragraph. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		<ol><li>Ordered list</li><li>Item 2 Consectetur adipisicing elit</li><li>Item 3 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</li><li>Item 4</li><li>Item 5</li></ol>
		Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		<h3>Header 3</h3>
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
		<ul><li>Unordered list</li><li>Consectetur adipisicing elit</li><li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</li><li>Item</li><li>Item</li><li>Item</li></ul>
		Lorem ipsum dolor sit amet,consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
		<pre><code>pre and code pair{
			display:block;
			line-height:1.833em;
			border-top:0.083em solid #200;
		}</code></pre>
		Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		<h4>Header 4</h4>
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
		<dl><dt>Definition list</dt><dd>Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</dd><dt>Lorem ipsum dolor sit amet</dt><dd>Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</dd><dt>Lorem ipsum dolor sit amet</dt><dd>Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</dd><dt>Lorem ipsum dolor sit amet</dt><dd>Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</dd></dl>
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
		<table summary="Table summary"><caption>Table Caption</caption><thead><tr><th>Header</th><th>Header</th><th>Header</th></tr></thead><tbody><tr><td>Content</td><td>1</td><td>a</td></tr><tr><td>Content</td><td>2</td><td>b</td></tr><tr><td>Content</td><td>3</td><td>c</td></tr><tr><td>Content</td><td>4</td><td>d</td></tr><tr><td>Content</td><td>5</td><td>e</td></tr><tr><td>Content</td><td>6</td><td>f</td></tr></tbody></table><address>Author text</address>';
	$arr = array(
		array(
			'post_title' => 'Accueil',
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_content' => $loremContent
		),
		array(
			'post_title' => '',
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_content' => $defaultContent
		),
		array(
			'post_title' => '',
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_content' => $defaultContent
		),
		array(
			'post_title' => '',
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_content' => $defaultContent
		),
		array(
			'post_title' => '',
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_content' => $defaultContent
		),	
		array(
			'post_title' => 'Avis',
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_name' => 'avis',
			'post_content' => '',
		),
		// Ajout par défaut
		array(
			'post_title' => 'Plan du site',
			'post_status' => 'publish',
			'post_name' => 'sitemap',
			'post_type' => 'page',
			'post_content' => '[wp_sitemap_page]',
		),
		array(
			'post_title' => 'Contact',
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_content' => '[gravityform id="1" title="false" description="false" ajax="true"]'
		),
		array(
			'post_title' => 'Mentions légales & Politique de Confidentialité',
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_name' => 'mentions-legales-politique-confidentialite',
			'post_content' => $ml
		)
	);
	foreach ($arr as $key => $value) {
		wp_insert_post($value);
	}
	$oldForm = $wpdb->insert(
		$wpdb->prefix.'rg_form',
		array(
			'id' => 1,
			'title' => 'Formulaire de contact',
			'date_created' => current_time('mysql', 1),
			'is_active' => 1
		)
	);
	$newForm = $wpdb->insert(
		$wpdb->prefix.'gf_form',
		array(
			'id' => 1,
			'title' => 'Formulaire de contact',
			'date_created' => current_time('mysql', 1),
			'is_active' => 1
		)
	);
	$arrDM = array(
		"title"					=> "Formulaire de contact",
		"description"			=> "",
		"labelPlacement"		=> "top_label",
		"descriptionPlacement"	=> "below",
		"button" 				=> array(
			"type"		=> "text",
			"text"		=> "Envoyer",
			"imageUrl"	=> ""
		),
		"fields" => array(
			array(
				"type" => "text",
				"id" => 1,
				"label" => "Nom",
				"adminLabel" => "",
				"isRequired" => true,
				"size" => "large",
				"errorMessage" => "",
				"inputs" => null,
				"formId" => 1,
				"description" => "",
				"allowsPrepopulate" => false,
				"inputMask" => false,
				"inputMaskValue" => "",
				"inputType" => "",
				"labelPlacement" => "",
				"descriptionPlacement" => "",
				"subLabelPlacement" => "",
				"placeholder" => "",
				"cssClass" => "gf_left_half name",
				"inputName" => "",
				"visibility" => "visible",
				"noDuplicates" => false,
				"defaultValue" => "",
				"choices" => "",
				"conditionalLogic" => "",
				"productField" => "",
				"form_id" => "",
				"useRichTextEditor" => false,
				"multipleFiles" => false,
				"maxFiles" => "",
				"calculationFormula" => "",
				"calculationRounding" => "",
				"enableCalculation" => "",
				"disableQuantity" => false,
				"displayAllCategories" => false,
				"pageNumber" => 1
			),
			array(
				"type" => "email",
				"id" => 2,
				"label" => "E-mail",
				"adminLabel" => "",
				"isRequired" => true,
				"size" => "large",
				"errorMessage" => "",
				"inputs" => null,
				"formId" => 1,
				"description" => "",
				"allowsPrepopulate" => false,
				"inputMask" => false,
				"inputMaskValue" => "",
				"inputType" => "",
				"labelPlacement" => "",
				"descriptionPlacement" => "",
				"subLabelPlacement" => "",
				"placeholder" => "",
				"cssClass" => "gf_right_half email",
				"inputName" => "",
				"visibility" => "visible",
				"noDuplicates" => false,
				"defaultValue" => "",
				"choices" => "",
				"conditionalLogic" => "",
				"productField" => "",
				"emailConfirmEnabled" => "",
				"multipleFiles" => false,
				"maxFiles" => "",
				"calculationFormula" => "",
				"calculationRounding" => "",
				"enableCalculation" => "",
				"disableQuantity" => false,
				"displayAllCategories" => false,
				"useRichTextEditor" => false,
				"pageNumber" => 1
			),
			array(
				"type" => "phone",
				"id" => 3,
				"label" => "Téléphone",
				"adminLabel" => "",
				"isRequired" => true,
				"size" => "large",
				"errorMessage" => "",
				"inputs" => null,
				"phoneFormat" => "international",
				"formId" => 1,
				"description" => "",
				"allowsPrepopulate" => false,
				"inputMask" => false,
				"inputMaskValue" => "",
				"inputType" => "",
				"labelPlacement" => "",
				"descriptionPlacement" => "",
				"subLabelPlacement" => "",
				"placeholder" => "",
				"cssClass" => "gf_left_half phone",
				"inputName" => "",
				"visibility" => "visible",
				"noDuplicates" => false,
				"defaultValue" => "",
				"choices" => "",
				"conditionalLogic" => "",
				"form_id" => "",
				"productField" => "",
				"multipleFiles" => false,
				"maxFiles" => "",
				"calculationFormula" => "",
				"calculationRounding" => "",
				"enableCalculation" => "",
				"disableQuantity" => false,
				"displayAllCategories" => false,
				"useRichTextEditor" => false,
				"pageNumber" => 1
			),
			array(
				"type" => "text",
				"id" => 4,
				"label" => "Sujet",
				"adminLabel" => "",
				"isRequired" => false,
				"size" => "large",
				"errorMessage" => "",
				"inputs" => null,
				"formId" => 1,
				"description" => "",
				"allowsPrepopulate" => false,
				"inputMask" => false,
				"inputMaskValue" => "",
				"inputType" => "",
				"labelPlacement" => "",
				"descriptionPlacement" => "",
				"subLabelPlacement" => "",
				"placeholder" => "",
				"cssClass" => "gf_right_half subject",
				"inputName" => "",
				"visibility" => "visible",
				"noDuplicates" => false,
				"defaultValue" => "",
				"choices" => "",
				"conditionalLogic" => "",
				"productField" => "",
				"form_id" => "",
				"useRichTextEditor" => false,
				"multipleFiles" => false,
				"maxFiles" => "",
				"calculationFormula" => "",
				"calculationRounding" => "",
				"enableCalculation" => "",
				"disableQuantity" => false,
				"displayAllCategories" => false,
				"pageNumber" => 1
			),
			array(
				"type" => "textarea",
				"id" => 5,
				"label" => "Message",
				"adminLabel" => "",
				"isRequired" => true,
				"size" => "large",
				"errorMessage" => "",
				"inputs" => null,
				"formId" => 1,
				"description" => "",
				"allowsPrepopulate" => false,
				"inputMask" => false,
				"inputMaskValue" => "",
				"inputType" => "",
				"labelPlacement" => "",
				"descriptionPlacement" => "",
				"subLabelPlacement" => "",
				"placeholder" => "",
				"cssClass" => "message",
				"inputName" => "",
				"visibility" => "visible",
				"noDuplicates" => false,
				"defaultValue" => "",
				"choices" => "",
				"conditionalLogic" => "",
				"productField" => "",
				"form_id" => "",
				"useRichTextEditor" => false,
				"multipleFiles" => false,
				"maxFiles" => "",
				"calculationFormula" => "",
				"calculationRounding" => "",
				"enableCalculation" => "",
				"disableQuantity" => false,
				"displayAllCategories" => false,
				"pageNumber" => 1
			),
			array(
				"type" => "consent",
				"checked_indicator_url" => "/wp-content/plugins/gravityforms/images/tick.png",
				"checked_indicator_markup" => "<img src=\"$url/wp-content/plugins/gravityforms/images/tick.png\" />",
				"id" => 6,
				"label" => "RGPD",
				"adminLabel" => "",
				"isRequired" => true,
				"size" => "medium",
				"errorMessage" => "",
				"visibility" => "visible",
				"inputs" => array(
					array(
						"id" => "6.1",
						"label" => "RGPD",
						"name" => ""
					),
					array(
						"id" => "6.2",
						"label" => "Texte",
						"name" => "",
						"isHidden" => true
					),
					array(
						"id" => "6.3",
						"label" => "Description",
						"name" => "",
						"isHidden" => true
					)
				),
				"checkboxLabel" => "En cochant cette case, vous acceptez d'être recontacté par email, conformément à notre <a href=\"/mentions-legales-politique-confidentialite.html\">Politique de Confidentialité</a>.",
				"descriptionPlaceholder" => "Saisissez le texte de consentement ici. Le champ de consentement stockera le texte d'accord avec l'entrée du formulaire pour suivre ce que l'utilisateur a accepté.",
				"choices" => array(
					array(
						"text" => "Coché",
						"value" => "1",
						"isSelected" => false,
						"price" => ""
					)
				),
				"formId" => 1,
				"description" => "",
				"allowsPrepopulate" => false,
				"inputMask" => false,
				"inputMaskValue" => "",
				"inputMaskIsCustom" => false,
				"maxLength" => "",
				"labelPlacement" => "",
				"descriptionPlacement" => "",
				"subLabelPlacement" => "",
				"placeholder" => "",
				"cssClass" => "rgpd",
				"inputName" => "",
				"noDuplicates" => false,
				"defaultValue" => "",
				"conditionalLogic" => "",
				"productField" => "",
				"multipleFiles" => false,
				"maxFiles" => "",
				"calculationFormula" => "",
				"calculationRounding" => "",
				"enableCalculation" => "",
				"disableQuantity" => false,
				"displayAllCategories" => false,
				"useRichTextEditor" => false,
				"fields" => "",
				"displayOnly" => ""
			)
		),
		"version" => "2.6.8",
		"id" => 1,
		"useCurrentUserAsAuthor" => true,
		"postContentTemplateEnabled" => false,
		"postTitleTemplateEnabled" => false,
		"postTitleTemplate" => "",
		"postContentTemplate" => "",
		"lastPageButton" => null,
		"pagination" => null,
		"firstPageCssClass" => null
	);
	$arrConf = array(
		"5be571c64ec09"=>array(
			"id" => "5be571c64ec09",
			"name" => "Confirmation par défaut",
			"isDefault" => true,
			"type" => "message",
			"message" => "Merci de nous avoir contacté ! Nous vous répondrons dans les plus brefs délais.",
			"url" => "",
			"pageId" => "",
			"queryString" => ""
		)
	);
	$arrNotif = array(
		"5be571c64cc89" => array (
			"isActive" => true,
			"id" => "5be571c64cc89",
			"name" => "Notification administrateur",
			"service" => "wordpress",
			"event" => "form_submission",
			"to" => $mail,
			"toType" => "email",
			"bcc" => "",
			"subject" => "MEOSIS — Nouveau Contact — {form_title}",
			"message" => "{all_fields}",
			"from" => $mail,
			"fromName" => "{Nom:1}",
			"replyTo" => "{E-mail:2}",
			"routing" => null,
			"conditionalLogic" => null,
			"disableAutoformat" => false
		)
	);
	$oldFormMeta = $wpdb->insert(
		$wpdb->prefix.'rg_form_meta',
		array(
			"form_id" => 1,
			"display_meta" => json_encode( $arrDM ),
			"confirmations" => json_encode( $arrConf ),
			"notifications" => json_encode( $arrNotif ),
		)
	);
	$newFormMeta = $wpdb->insert(
		$wpdb->prefix.'gf_form_meta',
		array(
			"form_id" => 1,
			"display_meta" => json_encode( $arrDM ),
			"confirmations" => json_encode( $arrConf ),
			"notifications" => json_encode( $arrNotif ),
		)
	);
	/*var_dump($newForm);
	var_dump($newFormMeta);*/
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Meo Quick Install</title>
	<style type="text/css">
		body {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 14px;
			background: #23282d url(https://trello-attachments.s3.amazonaws.com/566ab402ff0ea9fe54bf4269/1712x1703/fd832a1910af3de17c3663951c5ed67a/toilev2.png) no-repeat;
		}
		#install {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		}
		h1 {
			text-align: center;
			color: #fff;
		}
		form {
			background: rgba(26, 30, 33, 0.49);
			padding: 26px 24px 46px;
		}
		label span {
			color: #777;
    		font-size: 14px;
    		font-style: italic;
		}
		label input {
			background: #fbfbfb;
			font-size: 24px;
		    width: 100%;
		    padding: 3px;
		    margin: 5px 0 0 0;
		    border: 1px solid #ddd;
		    -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
		    box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
		    background-color: #fff;
		    color: #32373c;
		    outline: 0;
		    -webkit-transition: 50ms border-color ease-in-out;
		    transition: 50ms border-color ease-in-out;
		}
		input[type="submit"] {
			background: #97bf0d;
		    border-color: #6A860A;
		    -webkit-box-shadow: inset 0 1px 0 rgba(120,200,230,.5),0 1px 0 rgba(0,0,0,.15);
		    box-shadow: inset 0 1px 0 rgba(120,200,230,.5),0 1px 0 rgba(0,0,0,.15);
		    color: #fff;
		    text-decoration: none;
		    float: right;
			font-size: 13px;
			margin: 0;
			border-width: 1px;
			border-style: solid;
			border-radius: 3px;
			height: 30px;
		    line-height: 28px;
		    padding: 0 12px 2px;
		    cursor: pointer;
		}
		.notice {
		    background: rgba(51, 51, 51, 0.5);
		    margin: 0;
		    padding: 0;
		    position: absolute;
		    top: 50%;
		    left: 50%;
		    transform: translate(-50%, -50%);
		    z-index: 999;
		    width: 100vw;
		    height: 100vh;
		}
		.notice p {
			background: #fff;
			margin: 30px;
			border-left: 4px solid transparent;
			box-shadow: 0 2px 5px rgba(0,0,0,0.25);
			padding: 10px 30px;
			position: absolute;
		    top: 50%;
		    left: 50%;
		    transform: translate(-50%, -50%);
		}
		.notice-success p {
			border-left-color: green;
		}
		.notice-error p {
			border-left-color: red;
		}
	</style>
</head>
<body>
	<?php echo $notice; ?>
	<div id="install">
		<h1>Meo Quick Install</h1>
		<form method="post" action="">
			<p><label><span>Société *</span> <input name="societe" value="<?php echo $_POST['societe']; ?>" required></label></p>
			<p><label><span>Nom de domaine *</span> <input name="url" value="<?php echo $_POST['url']; ?>" required></label></p>
			<p><label><span>Directeur de publication *</span> <input name="director" value="<?php echo $_POST['director']; ?>" required></label></p>
			<p><label><span>Mail *</span> <input name="mail" value="<?php echo $_POST['mail']; ?>" required></label></p>
			<p><label><span>Rue *</span> <input name="rue" value="<?php echo $_POST['rue']; ?>" required></label></p>
			<p><label><span>Code postal *</span> <input name="zip" value="<?php echo $_POST['zip']; ?>" required></label></p>
			<p><label><span>Ville *</span> <input name="ville" value="<?php echo $_POST['ville']; ?>" required></label></p>
			<p><label><span>Téléphone *</span> <input name="phone" value="<?php echo $_POST['phone']; ?>" required></label></p>
			<p><label><span>SIRET *</span> <input name="siret" value="<?php echo $_POST['siret']; ?>" required></label></p>
			<input type="submit" name="submit" value="Configuration">
		</form>
	</div>
</body>
</html>