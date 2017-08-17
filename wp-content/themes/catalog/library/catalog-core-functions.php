<?php

function create_catalog_db_table() {
  global $wpdb;

  $table_name = $wpdb->prefix . "catalog_items";

  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

	  $charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  userid mediumint(9) NOT NULL,
		  name text NOT NULL,
		  details longtext DEFAULT '' NOT NULL,
		  category text NOT NULL,
		  purchase_price decimal(6,2) DEFAULT 0.00 NOT NULL,
		  purchase_date date DEFAULT '0000-00-00' NOT NULL,
		  photo varchar(200) DEFAULT '' NOT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_action( 'admin_notices', 'admin_notice__catalog_db_table_created' );

	} else {
		add_action( 'admin_notices', 'admin_notice__catalog_db_table_already_exists' );
	}
}

add_action('after_switch_theme', 'create_catalog_db_table');

// additionally create DB table through WP Dashboard with URL parameter
if (is_admin() && isset($_GET["create_catalog_db_table"])) {
	create_catalog_db_table();
}

function admin_notice__catalog_db_table_created() {
  ?>
  <div class="notice notice-success is-dismissible">
      <p><?php _e( 'The database table for catalog items was created successfully.', 'catalog' ); ?></p>
  </div>
  <?php
}

function admin_notice__catalog_db_table_already_exists() {
  ?>
  <div class="notice notice-info is-dismissible">
      <p><?php _e( 'The database table for catalog items already exists. You\'re good to go.', 'catalog' ); ?></p>
  </div>
  <?php
}


//======================================================================
// SET UP CatalogItem CLASS
//======================================================================
class CatalogItem {
	var $id;
	var $userID;
  var $name;
  var $details;
  var $purchase_price;
  var $category;
  var $purchase_date;
  var $photo;

  //-----------------------------------------------------
	// Constructors
	//-----------------------------------------------------

  /*
	* Constructor Method
	*/
  function __construct( ) {
  	$a = func_get_args();
    $i = func_num_args();

    // call specific constructor based on the number of parameters passed
    if (method_exists($this,$f='__construct'.$i)) {
      call_user_func_array(array($this,$f),$a);
    } else {
    	print "Incorrect number of parameters passed\n";
    }
	}

	/*
	* Constructor, provided ID only
	*/
  function __construct1( $id ) {
  	$this->id = $id;
  	// query database table for the row with the provided id
  	// set remaining member variables based on result
  	global $wpdb;
  	$table_name = $wpdb->prefix . "catalog_items";

  	$item = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $id" );
  	$this->name = $item->name;
  	$this->details = $item->details;
  	$this->category = $item->category;
  	$this->purchase_price = $item->purchase_price;
  	$this->purchase_date = $item->purchase_date;
   	$this->photo = $item->photo;
	}

	/*
	* Constructor, provided all member variables
	*/
  function __construct7( $userID, $name, $details, $purchase_price, $purchase_date, $category, $photo ) {
  	$this->userID = $userID;
  	$this->name = $name;
   	$this->details = $details;
   	$this->purchase_price = $purchase_price;
   	$this->category = $category;
   	$this->purchase_date = $purchase_date;
   	$this->photo = $photo;
	}

	//-----------------------------------------------------
	// Accessor Methods
	//-----------------------------------------------------

	// Accessor for name, provided an ID
  function getName($id) {
  	return $this->name;
  }

  //-----------------------------------------------------
	// Mutator Methods
	//-----------------------------------------------------


	//Mutator for name, provided an ID and a value
  function setName($id, $value) {
  	$this->name = $value;
  }

  //-----------------------------------------------------
	// Other Member Methods
	//-----------------------------------------------------

	// Add the CategoryItem object to the database
	function addToDatabase() {
		global $wpdb;
  	$table_name = $wpdb->prefix . "catalog_items";

		// grab the objects member variables and create a row in the DB table with those values
		return $wpdb->insert(
			$table_name,
			array(
				'userID' 					=> $this->userID,
				'name' 						=> $this->name,
				'details' 				=> $this->details,
				'purchase_price' 	=> $this->purchase_price,
				'purchase_date' 	=> $this->purchase_date,
				'category' 				=> $this->category,
				'photo' 					=> $this->photo
			),
			array(
				'%d',
				'%s',
				'%s',
				'%f',
				'%s',
				'%s',
				'%s',
			)
		);
	}


	// Update the corresponding database row with the CategoryItem's values
	function updateDatabaseEntry() {
		// grab the objects member variables and update the corresponding row in the DB table with those values
		return true;
	}
}



function doesCurrentUserOwnItem($itemID) {
	global $wpdb;
	$table_name = $wpdb->prefix . "catalog_items";
	$itemUserID = (int)$wpdb->get_var(
		"
		SELECT userid
		FROM $table_name
		WHERE id = $itemID
		"
	);

	if (get_current_user_id() == $itemUserID) {
		return true;
	} else {
		return false;
	}
}

function getItems() {
	global $wpdb;
	$table_name = $wpdb->prefix . "catalog_items";
	$currentUserID = get_current_user_id();
	$items = $wpdb->get_results(
		"
		SELECT *
		FROM $table_name
		WHERE userid = $currentUserID
		"
	);

	return $items;
}

function getCategories() {
	global $wpdb;
	$table_name = $wpdb->prefix . "catalog_items";
	$currentUserID = get_current_user_id();
	$categoriesArray = [];
	$categories = $wpdb->get_results(
		"
		SELECT DISTINCT category
		FROM $table_name
		WHERE userid = $currentUserID
		"
	);

	foreach ($categories as $category) {
		array_push($categoriesArray, $category->category);
	}

	return $categoriesArray;
}

add_action( 'wp_ajax_nopriv_add_item', 'add_item_processor' );
add_action( 'wp_ajax_add_item', 'add_item_processor' );

function add_item_processor() {
	$formError = false;
	$success = false;

	$userID = get_current_user_id();
	$name = $_POST['item_name'];
	if ($name == "") {
		$formError = "Item name is required";
	}
	$details = $_POST['details'];
	$purchase_price = $_POST['purchase_price'];
	$purchase_date = $_POST['purchase_date'];
	$category = $_POST['category'];

  // $upload = wp_upload_bits( $_FILES['photo']['name'], null, file_get_contents( $_FILES['photo']['tmp_name'] ) );
  $attachment_id = media_handle_upload( 'photo', 0 );
	if ( is_wp_error( $attachment_id ) ) {
	  // There was an error uploading the image.
		echo 'error';
	} else {
	  // The image was uploaded successfully!
		// $photo = $upload['url'];
		$photo = $attachment_id;
	}


  if ($formError) {
  	$message = $formError;
		wp_redirect( site_url() . '/add-item/?message=' . urlencode($message) . '&type=alert' );
  } else {
  	$catalogItem = new CatalogItem($userID, $name, $details, $purchase_price, $purchase_date, $category, $photo);
		$success = $catalogItem->addToDatabase();

		if ($success) {
			$message = "Item added successfully.";
			wp_redirect( site_url() . '/dashboard/?message=' . urlencode($message) . '&type=success' );
		} else {
			$message = "There was an issue with your request.";
			wp_redirect( site_url() . '/add-item/?message=' . urlencode($message) . '&type=alert' );
		}
	}

	die();
}

add_action( 'wp_ajax_nopriv_edit_item', 'edit_item_processor' );
add_action( 'wp_ajax_edit_item', 'edit_item_processor' );

function edit_item_processor() {
	$formError = false;
	$success = false;

	$userID = get_current_user_id();
	$itemID = $_POST['item_id'];
	$name = $_POST['item_name'];
	if ($name == "") {
		$formError = "Item name is required";
	}
	$details = $_POST['details'];
	$purchase_price = $_POST['purchase_price'];
	$purchase_date = $_POST['purchase_date'];
	$category = $_POST['category'];

  // $upload = wp_upload_bits( $_FILES['photo']['name'], null, file_get_contents( $_FILES['photo']['tmp_name'] ) );
  $attachment_id = media_handle_upload( 'photo', 0 );
	if ( is_wp_error( $attachment_id ) ) {
	  // There was an error uploading the image.
		echo 'error';
	} else {
	  // The image was uploaded successfully!
		// $photo = $upload['url'];
		$photo = $attachment_id;
	}


  if ($formError) {
  	$message = $formError;
		wp_redirect( site_url() . '/edit-item/?id=' . $itemID . '&message=' . urlencode($message) . '&type=alert' );
  } else {
  	$catalogItem = new CatalogItem($userID, $name, $details, $purchase_price, $purchase_date, $category, $photo);
		// $success = $catalogItem->addToDatabase();

		if ($success) {
			$message = "Changes were saved successfully.";
			wp_redirect( site_url() . '/dashboard/?message=' . urlencode($message) . '&type=success' );
		} else {
			$message = "There was an issue with your request.";
			wp_redirect( site_url() . '/add-item/?message=' . urlencode($message) . '&type=alert' );
		}
	}

	die();
}


function get_item_details($itemID) {
	$catalogItem = new CatalogItem($itemID);
	return $catalogItem;
}


