<?php
/**
 * @package Leadhacker
 * @version 1.0.2
 */
/*
Plugin Name: Leadhacker
Plugin URI: http://wordpress.org/extend/plugins/leadhacker/
Description: Simple, fast, and powerful.  <a href="http://www.leadhacker.ru">Leadhacker</a> is a dramatically easier way for you to improve your website through A/B testing. Create an experiment in minutes with our easy-to-use visual interface with absolutely no coding or engineering required. Convert your website visitors into customers and earn more revenue today! To get started: 1) Click the "Activate" link to the left of this description, 2) Sign up for an <a href="http://www.leadhacker.ru">Leadhacker account</a>, and 3) Go to the <a href="admin.php?page=leadhacker-config">settings page</a>, and enter your Leadhacker project code.
Author: Arthur Suermondt
Version: 1.0.1
Author URI: http://www.leadhacker.ru/
License: GPL2
*/

/*  Copyright 2013 Franco Sentry (email: support@leadhacker.ru)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( is_admin() )
  require_once dirname( __FILE__ ) . '/admin.php';

// forcing Leadhacker to load first in the head tag
add_action('wp_head', 'add_leadhacker_script', -1000);

function add_leadhacker_script() {
  if ( empty( $project_code) ) {
	  $project_code = get_option('leadhacker_project_code');
	  if ( !empty($project_code)) {
  	  
  	  $project_code_html = html_entity_decode($project_code);
  	  $patterns = array('/\<script src="/','/"\>\<\/script\>/');
      $projectScript = preg_replace($patterns, '', $project_code_html);

      if (!$_SERVER['HTTPS']) {
        $script = "".$projectScript;
      } else {
        $script = "https:".$projectScript;
      }
      
	  echo $script;

	  }
	}

}

?>