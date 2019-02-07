<?php 

if (!isset($vars['entity']->pages)) {
	$vars['entity']->pages = 'about,terms,privacy';
}

echo elgg_view_field([
    '#type' => 'text',
	'name' => 'params[pages]',
	'value' => $vars['entity']->pages,
	'#label' => elgg_echo('expages:extendedpages'),
]);

$text = elgg_echo('expages:extendedpages:languagenotification');
echo "<div class='mts'>
		<blockquote>
			<p class='pam'>
				$text
			</p>
		</blockquote>
	  </div>";