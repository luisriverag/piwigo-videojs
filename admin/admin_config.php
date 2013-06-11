<?php
/***********************************************
* File      :   admin_config.php
* Project   :   piwigo-videojs
* Descr     :   Generate the admin panel
*
* Created   :   24.06.2012
*
* Copyright 2012-2013 <xbgmsharp@gmail.com>
*
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
************************************************/

// Check whether we are indeed included by Piwigo.
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

// Load parameter
$customcss = $conf['vjs_customcss'] ? $conf['vjs_customcss'] : '';

// Available skins
$available_skins = array(
	'vjs-default-skin' => 'default',
	'vjs-darkfunk-skin' => 'darkfunk',
	'vjs-redsheen-skin' => 'redsheen',
);

// Available preload value
$available_preload = array(
	'auto' => 'Auto',
	'none' => 'None',
);

// Available width value
$available_width = array(
	'480' => 'EDTV: (720x480) ie: 480p',
	'720' => 'HDReady: (1280x720) ie: 720p',
	'1080' => 'FullHD: (1920x1080) ie: 1080p',
);

// Update conf if submitted in admin site
if (isset($_POST['submit']) && !empty($_POST['vjs_skin']))
{
	// keep the value in the admin form
	$conf['vjs_conf'] = array(
		'skin'          => $_POST['vjs_skin'],
		'max_width'     => $_POST['vjs_max_width'],
		'preload'       => $_POST['vjs_preload'],
		'controls'      => get_boolean($_POST['vjs_controls']),
		'autoplay'      => get_boolean($_POST['vjs_autoplay']),
		'loop'          => get_boolean($_POST['vjs_loop']),
	);
	$customcss = $_POST['vjs_customcss'];

	// Update config to DB
	conf_update_param('vjs_conf', serialize($conf['vjs_conf']));
	$query = "UPDATE ". CONFIG_TABLE ." SET value='". $_POST['vjs_customcss'] ."' WHERE param='vjs_customcss'";
	pwg_query($query);

	// the prefilter changes, we must delete compiled templatess
	$template->delete_compiled_templates();

	array_push($page['infos'], l10n('Your configuration settings are saved'));
}

// send value to template
$template->assign($conf['vjs_conf']);
$template->assign(
	array(
		'AVAILABLE_SKINS'	=> $available_skins,
		'AVAILABLE_PRELOAD'	=> $available_preload,
		'AVAILABLE_WIDTH'	=> $available_width,
		'CUSTOM_CSS'		=> htmlspecialchars($customcss),
	)
);

?>