<?php

elgg_register_event_handler('init', 'system', 'expages_extended_init');

function expages_extended_init() {
	
	$external_pages = expages_extended_pages();
	foreach($external_pages as $page){
		elgg_register_page_handler($page, 'expages_page_handler');
	}
	
	elgg_unregister_plugin_hook_handler('public_pages', 'walled_garden', 'expages_public');
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'expages_extended_public');

	elgg_unregister_plugin_hook_handler('register', 'menu:expages', 'expages_menu_register_hook');
	elgg_register_plugin_hook_handler('register', 'menu:expages', 'expages_extended_menu_register_hook');

	expages_extended_setup_footer_menu();

	elgg_unregister_action("expages/edit");
	elgg_register_action("expages/edit", __DIR__ . '/actions/edit.php', 'admin');		
}

/**
 * Extend the public pages range
 *
 */
function expages_extended_public($hook, $handler, $return, $params){
	$pages = expages_extended_pages();
	return array_merge($pages, $return);
}

/**
 * Adds menu items to the expages edit form
 *
 * @param string $hook   'register'
 * @param string $type   'menu:expages'
 * @param array  $return current menu items
 * @param array  $params parameters
 * 
 * @return array
 */
function expages_extended_menu_register_hook($hook, $type, $return, $params) {
	$type = elgg_extract('type', $params);
		
	$pages = expages_extended_pages();
	foreach ($pages as $page) {
		$return[] = ElggMenuItem::factory(array(
			'name' => $page,
			'text' => elgg_echo("expages:$page"),
			'href' => "admin/appearance/expages?type=$page",
			'selected' => $page === $type,
		));
	}
	return $return;
}

/**
 * Setup the links to site pages
 */
function expages_extended_setup_footer_menu() {
	$pages = expages_extended_pages();
	foreach ($pages as $page) {
		$url = "$page";
		$wg_item = new ElggMenuItem($page, elgg_echo("expages:$page"), $url);
		elgg_register_menu_item('walled_garden', $wg_item);

		$footer_item = clone $wg_item;
		elgg_register_menu_item('footer', $footer_item);
	}
}

/**
 * Get the list of external pages 
 *
 * @return array
 */
function expages_extended_pages(){
	$pagelist = elgg_get_plugin_setting('pages', 'externalpages_extended');
	if($pagelist){
		$pages = explode(",", $pagelist);
		foreach($pages as $page){
			$title = elgg_get_friendly_title($page);
			$external_pages[] = $title; 
		}
	} else {
		$external_pages = array('about', 'terms', 'privacy');
	}
	return $external_pages;
}