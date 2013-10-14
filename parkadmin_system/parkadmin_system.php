<?php
/* 
    Plugin Name: Park Admin System
    Plugin URI: http://parkadmin.com 
    Description: Plugin for parking system and vehicle record.
    Author: Tomahawk Technologies Inc.
    Version: 1.0 
    Author URI: http://www.tomahawk.ca
*/  
 
// Theme Installation DB
  
global $TransitionMessage;
require_once('inc/functions.php');
require_once('inc/park_install.php');
require_once('inc/LotsFunctions.php');
require_once('inc/PermitSystem.php');
require_once('inc/VehicleProperties.php');
require_once('inc/operations.php');
require_once('inc/AdminOptions.php');
require_once('inc/FrontPanelView.php');
   
function parking_system_activation() {

   global $wpdb ;
   
   $options=AdminSideOptions();
   update_option( $options['key'], $options['data'] );
 
 	//Db Installation
 	DbInstallParkingSystem();
 	
	//Contents Install
	ContentsInstall();
 }
// Activation Hook
register_activation_hook( __FILE__, 'parking_system_activation' );

function parking_system_deactivation() {

   global $wpdb ;
  	//Db Installation
 	//DbDel();
  }
register_deactivation_hook(__FILE__, 'parking_system_deactivation');
 
function register_parking_system_Menu(){
 
	$page=get('page')  ;
 		
    add_menu_page( 'ParkAdmin Lite', 'ParkAdmin Lite', 'manage_options', 'ParkAdminSystem', 'ParkAdminSystem','', 100 );
 	add_submenu_page( 'ParkAdminSystem', 'Lots','Lots', 'manage_options', 'ParkAdminSystemlots', 'ParkLots' );
	add_submenu_page( 'ParkAdminSystem', 'Manage Permits','Permits', 'manage_options', 'ParkAdminSystemPermit', 'PermitSystem' );
	add_submenu_page( 'ParkAdminSystem', 'Vehicle Search','Vehicles Search', 'manage_options', 'ParkAdminSystemVehicle', 'ParkVehicle' );
 	add_submenu_page( 'ParkAdminSystem', 'Manage Vehicle Makes','Vehicles Makes', 'manage_options', 'ParkAdminSystemMaker', 'VehicleMaker' );
	add_submenu_page( 'ParkAdminSystem', 'Manage Vehicle Types','Vehicles Types', 'manage_options', 'ParkAdminSystemType', 'VehicleType' );
	add_submenu_page( 'ParkAdminSystem', 'Manage Permit Payments','Permit Payments', 'manage_options', 'ParkAdminSystemTransaction', 'PaymentTransactions' );
	  	
	switch($page)
	{
		case 'ParkAdminSystem':
		case 'ParkAdminSystemlots':
		case 'ParkAdminSystemVehicle':
		case 'ParkAdminSystemPermit':
		case 'ParkAdminSystemMaker':
		case 'ParkAdminSystemType':
		case 'ParkAdminSystemTransaction':
		case 'ParkAdminSystemPermitUser':
		
 						IncludeCssJs();
						
		break;
	}
 
	
}//register_parking_system_Menu

// Action For The Admin Menu 
add_action( 'admin_menu', 'register_parking_system_Menu' );

 function IncludeCssJs()
{	
	wp_enqueue_style( 'Parkingstyles',plugins_url().'/parkadmin_system/css/parking.css' );
	wp_enqueue_style( 'ParkingstylesUi', plugins_url().'/parkadmin_system/css/jquery-ui.css' );	
	wp_enqueue_script('ParkingScript2', plugins_url().'/parkadmin_system/js/jquery-1.9.1.js' );
	wp_enqueue_script('ParkingScript1', plugins_url().'/parkadmin_system/js/jquery-ui.js' );
	wp_enqueue_script('ParkingScript', plugins_url().'/parkadmin_system/js/validator.js'); 	
}
// *********************************

?>