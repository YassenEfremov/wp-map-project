<?php
/**
 * Plugin Name:       Random Map Coordinates
 * Plugin URI:        https://github.com/YassenEfremov/wp-random-map-coordinates
 * Description:       A simple Wordpress plugin that provides a map with randomly generated coordinates.
 * Version:           1.0.0
 * Author:            Yassen Efremov
 * Author URI:        https://github.com/YassenEfremov
 */


// v v v This code could be useful v v v

//	$wpdb->insert($table_name, array(
//		'id' => NULL,
//		'map_id' => 1,
//		'address' => 'Random',
//		'description' => NULL,
//		'pic' => NULL, 
//		'link' => NULL,
//		'icon' => NULL,
//		'lat' => '?',
//		'lng' => '?',
//		'anim' => '0',
//		'title' => NULL,
//		'infoopen' => '0',
//		'category' => NULL,
//		'approved' => 1,
//		'retina' => 0,
//		'type' => 0,
//		'did' => NULL,
//		'sticky' => 0,
//		'other_data' => NULL,
//		'latlng' => 0x000000000101000000A60980965359454015F6FE507A523740
//	));


function generate_new_coordinates() {

	global $wpdb;
	$table_name = $wpdb->prefix.'wpgmza';
	
	// Delete any markers from before	(kind of buggy)
	$wpdb->delete($table_name, array('map_id' => 1));
	
	// The beginning of the SQL query, specifies the colums to insert into
	$query = "INSERT INTO $table_name (map_id, address, description, pic, link, icon, lat, lng, anim, title, infoopen, category, approved, retina, type, did, sticky, other_data, latlng) VALUES ";
	
	$number_of_coords = $_POST['number_of_coords'];


	/* ----- METHOD 1: Use an external API to get the random coordinates ----- */
	
	$curl = curl_init();

	// Set the URL and return type
	curl_setopt($curl, CURLOPT_URL, 'https://www.random.org/decimal-fractions/?num=' . 2 * $number_of_coords . '&dec=10&col=2&format=plain&rnd=new');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$result_string = curl_exec($curl);
	
	curl_close($curl);
	
	// Split the resulting string by the delimiting character, in this case the PHP_EOL character
	$result = explode(PHP_EOL, $result_string);
	
	// Add the values to the SQL query
	for($i = 0; $i < $number_of_coords; $i++) {
		$random_lat = 180 * explode("\t", $result[$i])[0] - 90;
		$random_lng = 360 * explode("\t", $result[$i])[1] - 180;

		$query .= "(1, 'Random', '', '', '', '', '$random_lat', '$random_lng', '0', '', '0', '', 1, 0, 0, '', 0, '', POINT($random_lat, $random_lng))";
		if($i + 1 != $number_of_coords) $query .= ', ';	// Don't add a comma after the last values
	}


	/* ----- METHOD 2: Use the built-in PHP rand methods to generate the random coordinates ----- */
	
	// Generate $number_of_coords markers, each with random coordinates, and add the values to the SQL query

	/*
	for($i = 0; $i < $number_of_coords; $i++) {
		$random_lat = rand(-900000000, 900000000) / 10000000;
		$random_lng = rand(-1800000000, 1800000000) / 10000000;

		$query .= "(1, 'Random', '', '', '', '', '$random_lat', '$random_lng', '0', '', '0', '', 1, 0, 0, '', 0, '', POINT($random_lat, $random_lng))";
		if($i + 1 != $number_of_coords) $query .= ', ';	// Don't add a comma after the last values
	}
	*/

	// Prepare and execute the final SQL query
	$prepared_query = $wpdb->prepare($query);
	$wpdb->query($prepared_query);
}

function wp_enqueue_gen_new_coords() {
	wp_enqueue_script('generate_new_coordinates', plugin_dir_url(__FILE__) . '/js/generate-new-coordinates.js', array('jquery'));
	wp_localize_script('generate_new_coordinates', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'wp_enqueue_gen_new_coords');

add_action('wp_ajax_nopriv_generate_new_coordinates', 'generate_new_coordinates');
add_action('wp_ajax_generate_new_coordinates', 'generate_new_coordinates');
