<?php 

if (!isset($vars['entity']->pages)) {
	$vars['entity']->pages = 'about,terms,privacy';
}

echo '<div>';
echo elgg_echo('expages:extendedpages');
echo ' ';
echo elgg_view('input/text', array(
	'name' => 'params[pages]',
	'value' => $vars['entity']->pages,
));
echo '</div>';

$text = elgg_echo('expages:extendedpages:languagenotification');
echo "<div class='mts'>
		<blockquote>
			<p class='pam'>
				$text
			</p>
		</blockquote>
	  </div>";