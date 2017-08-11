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
		  time_of_purchase datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
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
  var $time_of_purchase;
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
	}

	/*
	* Constructor, provided all member variables
	*/
  function __construct7( $userID, $name, $details, $purchase_price, $category, $time_of_purchase, $photo ) {
  	$this->userID = $userID;
  	$this->name = $name;
   	$this->details = $details;
   	$this->purchase_price = $purchase_price;
   	$this->category = $category;
   	$this->time_of_purchase = $time_of_purchase;
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
	function addCategoryItem() {
		// grab the objects member variables and create a row in the DB table with those values
		return true;
	}


	// Update the corresponding database row with the CategoryItem's values
	function updateCategoryItem() {
		// grab the objects member variables and update the corresponding row in the DB table with those values
		return true;
	}
}
