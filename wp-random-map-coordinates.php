<?php
/**
 * Plugin Name:       Random Map Coordinates
 * Plugin URI:        https://github.com/YassenEfremov/wp-random-map-coordinates
 * Description:       A simple Wordpress plugin that provides a map with randomly generated coordinates.
 * Version:           1.0.0
 * Author:            Yassen Efremov
 * Author URI:        https://github.com/YassenEfremov
 */


define("NUMBER_OF_COORDS", 5);

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


if(isset($_POST['gen-new-coords'])) {

	global $wpdb;
	$table_name = $wpdb->prefix.'wpgmza';

	// Delete any markers from before	(kind of buggy)
	$wpdb->delete($table_name, array('map_id' => 1));

	// The beginning of the SQL query, specifies the colums to insert into
	$query = "INSERT INTO $table_name (map_id, address, description, pic, link, icon, lat, lng, anim, title, infoopen, category, approved, retina, type, did, sticky, other_data, latlng) VALUES ";

	
	// Generate NUMBER_OF_COORDS markers, each with random coordinates, and add the values to the SQL query

	for($i = 0; $i < NUMBER_OF_COORDS; $i++) {
		$random_lat = rand(-900000000, 900000000) / 10000000;
		$random_lng = rand(-1800000000, 1800000000) / 10000000;

		$query .= "(1, 'Random', '', '', '', '', '$random_lat', '$random_lng', '0', '', '0', '', 1, 0, 0, '', 0, '', POINT($random_lat, $random_lng))";
		if($i + 1 != NUMBER_OF_COORDS) $query .= ', ';	// Don't add a comma after the last values
	}

	// Prepare and execute the final SQL query
	$prepared_query = $wpdb->prepare($query);
	$wpdb->query($prepared_query);
}
