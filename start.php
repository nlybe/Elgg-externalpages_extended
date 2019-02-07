<?php

elgg_register_event_handler('init', 'system', 'expages_extended_init');

function expages_extended_init() {
	
	$external_pages = expages_extended_pages();
	foreach($external_pages as $page) {
		elgg_register_route('my_plugin:section', [
			'path' => "/{$page}",
			'resource' => "expages",
			'defaults' => [
				'expage' =>  $page,
			],
			'walled' => false,
		]);

		if (!is_registered_entity_type('object', $page)) {
			elgg_register_entity_type('object', $page);
		}
	}
	
	elgg_unregister_plugin_hook_handler('register', 'menu:expages', 'expages_menu_register_hook');
	elgg_register_plugin_hook_handler('register', 'menu:expages', 'expages_extended_menu_register_hook');

	expages_extended_setup_footer_menu();	
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
		$return[] = ElggMenuItem::factory([
			'name' => $page,
			'text' => elgg_echo("expages:$page"),
			'href' => "admin/configure_utilities/expages?type=$page",
			'selected' => $page === $type,
		]);
	}
	return $return;
}

/**
 * Setup the links to site pages
 *
 * @return void
 */
function expages_extended_setup_footer_menu() {
	$pages = expages_extended_pages();
	foreach ($pages as $page) {
		elgg_register_menu_item('walled_garden', [
			'name' => $page,
			'text' => elgg_echo("expages:$page"),
			'href' => $page,
		]);

		elgg_register_menu_item('footer', [
			'name' => $page,
			'text' => elgg_echo("expages:$page"),
			'href' => $page,
			'section' => 'meta',
		]);
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