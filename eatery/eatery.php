<?php
/*
Plugin Name: EATERY
Author: Karen Daniel
Description: Plugin to handle CRUD on food places
Version: 1.0
*/

use EATERY\EAT_Setup as setup;

/*autoload*/
spl_autoload_register('autoloadFiles');

//constants
define('SYSTEM_KEYWORD', 'EAT');
define('KEY', 'eat');
define('FILE_PATH', ABSPATH.'wp-content/plugins/eatery');
define('SUB_MODEL', ABSPATH.'wp-content/plugins/eatery/models');
define('SUB_CONTROLLER', ABSPATH.'wp-content/plugins/eatery/controllers');

function autoloadFiles($className)
{
	if (false === strpos($className, SYSTEM_KEYWORD)) {
		return;
	}

	$file_parts = explode('\\', $className);
	$namespace = '';

	for ($i = count($file_parts) - 1; $i > 0; $i--) {

		$current = strtolower($file_parts[$i]);
		$current = str_ireplace('_', '-', $current);
		$current = str_ireplace(SYSTEM_KEYWORD.'-', '', $current);

		if (count($file_parts) - 1 === $i) {

			if (strpos(strtolower($file_parts[count($file_parts) - 1]), 'interface')) {
				echo "interface";
			}else if (strpos(strtolower($file_parts[count($file_parts) - 1]), 'model')) {
			
				$occurence = substr_count($current, "-");

				//if - occured more than once, make all after 'model' as folder and last one as file name
				if ($occurence > 1) {
					$model = explode('-', $current);
					
					$folders = null;

					for ($j = 0; $j < $occurence; $j++) {
						
						if ($model[$j] != $model[0]){
							$folders .= '/'.$model[$j];
						}
						
						$files .= '-'.$model[$j];
					}

					$file_name = "models$folders/$model[$occurence]/class$files-$model[$occurence].php";
				
				} else {
					$model = explode('-', $current);
					$file_name = "models/".$model[1]."/class-$current.php";
				}


			} else {

				$file_name = "controllers/$current/class-$current.php";
			}

		} else {
			$namespace = '/'.$current.$namespace;
		}
	}


	$file_path = trailingslashit(dirname(__FILE__).$namespace);
	$file_path .= $file_name;

	require_once($file_path);
}

#kick start the plugin here
register_activation_hook(__FILE__, 'uponActivation');
add_action('plugins_loaded', 'loadDOROPU');

function uponActivation()
{
	$setup = new setup;
	$setup->createDefaultTables();
	$page_definitions = array(

			'listing' => array(
			    'title' => __('Listing'),
			    'content' => '[listing]'
		    ),
		    'add-listing' => array(
			    'title' => __('Add Listing'),
			    'content' => '[add_listing]'
		    )
		);

		foreach ($page_definitions as $slug => $page) {

		    // Check that the page doesn't exist already
		    $query = new \WP_Query('pagename=' . $slug);
		    if (!$query->have_posts()) {
		        // Add the page using the data from the array above
		        wp_insert_post(
		            array(
		                'post_content'   => $page['content'],
		                'post_name'      => $slug,
		                'post_title'     => $page['title'],
		                'post_status'    => 'publish',
		                'post_type'      => 'page',
		                'ping_status'    => 'closed',
		                'comment_status' => 'closed',
		            )
		        );
		    }
		} 
}

//initialize doropu
function loadDOROPU()
{
	$setup = new setup;
}