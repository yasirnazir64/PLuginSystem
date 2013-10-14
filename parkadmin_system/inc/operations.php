<?php
 
function FormProcessAdmin()// Run at admin initiation
{
	global $wpdb;
	$prifix=$wpdb->prefix;
	$v=$_POST;
	wp_register_style( 'Parkingstyles', plugins_url( 'parkadmin_system/css/parking.css',__FILE__ ) );
	wp_register_style( 'ParkingstylesUi', plugins_url( 'parkadmin_system/css/jquery-ui.css',__FILE__ ) );
	wp_register_script( 'ParkingScript', plugins_url( 'parkadmin_system/js/validator.js' ,__FILE__) );
	wp_register_script( 'ParkingScript1', plugins_url( 'parkadmin_system/js/jquery-ui.js',__FILE__ ) );
	wp_register_script( 'ParkingScript2', plugins_url( 'parkadmin_system/js/jquery-1.9.1.js',__FILE__ ) );
 	
	if(isset($v['action']))
	{
  	$actiontypes=explode('_',$v['action']);
 	if($actiontypes[0]=='ajaxe')
	{	
		//For Ajax .....
	switch($actiontypes[1])
	{
		
		//Del operations .....
		//Del operations .....
		case 'DelVehicle': 		
 		 $wpdb->delete( $prifix.'vehicle', array( 'VehicleID' => $v['InfoID'] ) );
 	 	echo json_encode(array('message'=>'Deleted Record Successfully'));
		break ;	
		case 'DelVehicleType': 		
 		 $wpdb->delete( $prifix.'vehicletype', array( 'VehicleTypeID' => $v['InfoID'] ) );
 	 	echo json_encode(array('message'=>'Deleted Record Successfully'));
		break ;	
		case 'DelVehiclemaker': 		
 		 $wpdb->delete( $prifix.'vehiclemaker', array( 'VehicleMakerID' => $v['InfoID'] ) );
 	 	echo json_encode(array('message'=>'Deleted Record Successfully'));
		break ;	
		case 'Delpermit': 		
 		 $wpdb->delete( $prifix.'permit', array( 'PermitId' => $v['InfoID'] ) );
 	 	echo json_encode(array('message'=>'Deleted Record Successfully'));
		break ;	
		case 'DelLot': 		
 		 $wpdb->delete( $prifix.'lot', array( 'LotID' => $v['InfoID'] ) );
 	 	echo json_encode(array('message'=>'Deleted Record Successfully'));
		break ;	
		//End Del operations .....	
		// Get Operations	
		case 'getVehicle': 
	 	 $q="SELECT * FROM  `{prefix}vehicle` where VehicleID=".$v['InfoID'];
 	 	echo json_encode(getdata($q));		
		break ;	
		case 'getLot': 
	 	 $q="SELECT * FROM  `{prefix}lot` where LotID=".$v['InfoID'];
 	 	echo json_encode(getdata($q));		
		break ;
		case 'getpermit': 
	 	 $q="SELECT * FROM  `{prefix}permit` where PermitId=".$v['InfoID'];
 	 	echo json_encode(getdata($q));		
		break ;
		case 'getVehicleType': 		
		 $q="SELECT * FROM  `{prefix}vehicletype` where VehicleTypeID=".$v['InfoID'];
 	 	echo json_encode(getdata($q));
		break ;
		case 'getVehiclemaker' :
			$q="SELECT * FROM  `{prefix}vehiclemaker` where VehicleMakerID=".$v['InfoID'];
 	 	echo json_encode(getdata($q));
		break ;
		
		}// End Ajax Switch
 
		exit();
		}
	
	// For Forms
	switch($v['action'])
	{ 
		case 'SaveAdminData':
			
 			$adminOption=AdminSideOptions();
			foreach($adminOption['data'] as $k=>$value)
			{
				if(isset($adminOption['data'][$k]['ext']))
				{	
					$inputs= $k.'_ext' ;
					 if(isset($_FILES[$inputs]['name']) and $_FILES[$inputs]['name']!=''
					 and ( $_FILES[$inputs]['type']=='image/png'
					 or $_FILES[$inputs]['type']=='image/gif'
					 or $_FILES[$inputs]['type']=='image/jpg'
					 or $_FILES[$inputs]['type']=='image/jpeg')  )
					 {
						 $file=$_FILES[$inputs];
						 $Uploded=wp_upload_bits($file["name"], null, file_get_contents($file["tmp_name"]) ) ;
						 $v[$k]=$Uploded['url'];
						 } 
					}
  				$adminOption['data'][$k]['value']=$v[$k];}
 			 	update_option( $adminOption['key'], $adminOption['data'] 
				);	
			setMessage('Admin Options Saved Successfully');		
		break ;
		case 'UpdateLot':
		 
		  $existingL=getdata("SELECT count(LotID) as counter FROM  `{prefix}lot` 
		 						where LotID!=".$v['PID'].". and  LotName='".trim($v['LotName'])."'
									 and Archived is NULL");
									 
 		if($existingL[0]->counter > 0)
		{
 			setMessage('This Lot Name is already exist',1);
			break;
 			}
		 
		 if(isset($v['archived']))
				 $data=array(
							'LotName' => $v['LotName'],
							'LotShortName' => $v['ShortName'],
							'Visible' => isset($v['visible'])?1:0,
							'Location' => $v['location'],
							'Deposit' => $v['depositAmount'],
							'YearlyCost' => $v['yearlyCost'],
							'QuarterlyCost' => $v['quarterlyCost'],
							'MonthlyCost' => $v['monthlyCost'],
							'DailyCost' => $v['dailyCost'],
							'Archived'=> date('Y-m-d H:i:s')					
						);
						
		else
				 $data=array(
							'LotName' => $v['LotName'],
							'LotShortName' => $v['ShortName'],
							'Visible' => isset($v['visible'])?1:0,
							'Location' => $v['location'],
							'Deposit' => $v['depositAmount'],
							'YearlyCost' => $v['yearlyCost'],
							'QuarterlyCost' => $v['quarterlyCost'],
							'MonthlyCost' => $v['monthlyCost'],
							'DailyCost' => $v['dailyCost'],					
						);
		 
		  $arr= $arr=custom_update('lot', $data ,array( 'LotID'=>$v['PID'] )) ;
		 if($arr)
		 	setMessage('Updated Lot successfully.');
		break ;					
						
		case 'UpdatePermit':
		 
		 $existingP=getdata("SELECT count(PermitId) as counter FROM  `{prefix}permit` 
		 						where PermitId!=".$v['PID'].". and  PermitNo='".$v['PermitNO']."'
									 and Archived is NULL");
									 
 		if($existingP[0]->counter > 0)
		{
 			setMessage('This Permit No# is already exist',1);
			break;
 			}
			
		 if(isset($v['archived']))
				 $data=array(
							'PermitNo' => $v['PermitNO'],
							'Lot' => $v['LotNameID'],
							'Visible' => isset($v['visible'])?1:0,
							'Archived'=> date('Y-m-d H:i:s')
						);
		else
				 $data=array(
							'PermitNo' => $v['PermitNO'],
							'Lot' => $v['LotNameID'],
							'Visible' => isset($v['visible'])?1:0
						);
		 
		  $arr=custom_update('permit', $data ,array( 'PermitId'=>$v['PID'] )) ;
		 if($arr)
		 	setMessage('Updated permit successfully.');
		break ;		
		case 'UpdateVehiclMaker':
		 
			$data=  array('MakerName' => $v['makername'] 	) ; 
 			$arr=custom_update('vehiclemaker', $data ,array( 'VehicleMakerID'=>$v['PID'] )) ;		
			if($arr)
		 	setMessage('Updated Vehicle make successfully.');		
				 
		break ;		
		case 'UpdateVehicleType':
		 
			$data=  array('TypeName' => $v['typename'] 	) ; 
 			$arr=custom_update('vehicletype', $data ,array( 'VehicleTypeID'=>$v['PID'] )) ;		
			if($arr)
		 	setMessage('Updated Vehicle Type successfully.');			
						
		break ;		
		case 'AddLots':
 	
		$existingL=getdata("SELECT count(LotID) as counter FROM  `{prefix}lot` 
		 						where LotName='".trim($v['LotName'])."' and Archived is NULL");
									 
 		if($existingL[0]->counter > 0)
		{
 			setMessage('This Lot Name is already exist',1);
			break;
 			}
		$data=		array(
							'LotName' => $v['LotName'],
							'LotShortName' => $v['ShortName'],
							'Visible' => isset($v['visible'])?1:0,
							'Location' => $v['location'],
							'Deposit' => $v['depositAmount'],
							'YearlyCost' => $v['yearlyCost'],
							'QuarterlyCost' => $v['quarterlyCost'],
							'MonthlyCost' => $v['monthlyCost'],
							'DailyCost' => $v['dailyCost'],					
 					);
			$Lot=custom_insert( 'lot' ,$data);
		if($Lot>0 )
 			setMessage('Lot Added Successfully.' );
					 
		break ;
		case 'AddNewPermit':
 		if(trim($v['PermitNO'])=='' or $v['LotNameID']==0 )
		{
			setMessage('Please fill the permit no and also select lotname',1);
			break;
			}
		
 		$existingP=getdata("SELECT count(PermitId) as counter FROM  `{prefix}permit` where PermitNo='".$v['PermitNO']."' and Archived is NULL");
 		if($existingP[0]->counter > 0)
		{
 			setMessage('This Permit No# is already exist',1);
			break;
 			}
	
		$data=array(
					'PermitNo' => $v['PermitNO'],
					'Lot' => $v['LotNameID'],
					'Visible' => isset($v['visible'])?1:0
						);
 		$Permit=custom_insert( 'permit' ,$data);
		if($Permit>0 )
 			setMessage('Permit Added Successfully.' );
  		break ;
		case 'AddNewVehiclMaker':
 	
		$data=array( 'MakerName' => $v['makername'] );
					
			$Permit=custom_insert( 'vehiclemaker' ,$data);
		if($Permit>0 )
 			setMessage('Vehicle make Added Successfully.' );
					 
		break ;
		case 'AddNewVehicleType':
 	
		$data=array( 'TypeName' => $v['typename']  );
		
			$Permit=custom_insert( 'vehicletype' ,$data);
		if($Permit>0 )
 			setMessage('Vehicle Type Added Successfully.' );		 
		break ;
		case 'UpdateVehicle':
		
		$q="SELECT count(VehicleID) as counter FROM {prefix}vehicle where VehicleID!=".$v['PID']." 
				and PlateNumber='".$v['LicencePlate']."' ";
				
			$vehicle = getdata($q);
			if($vehicle[0]->counter > 0 )
				{
					setMessage('Vehicle is already registered with this licence plate number' ,1);
					break ;
					}
 			
		$cond=array('VehicleID'=>$v['PID'] );
		$data=array(
							'Active' => $v['status'] ,
							'PlateNumber'=>$v['LicencePlate'] ,
							'MakeID' =>  $v['Make'] ,
							'TypeID' => $v['Type'] ,
							'Colour' => $v['color'] ,
							'Year' => $v['Years'] ,
							'States' => $v['states'] ,
 						);
 			
		$insertedid=custom_update('vehicle', $data ,$cond) 	;
  		if($insertedid)
			setMessage('Updated Vehicle Successfully.');					 
		break ;
 		
		 
		}
 	} 
	
	}// End FormProcess
add_action('admin_init', 'FormProcessAdmin');

function FormProcessUser()// Run at User initiation
{
	global $wpdb;
	$prifix=$wpdb->prefix;
	$v=$_POST;
	$User_ID = get_current_user_id();
	$Options=AdminSideOptions('data');
 	
	// For Paypal Return Data
	
 	if(isset($v['custom'])  and $v['custom']=='ParkAdminLiteModule' and isset($v['payment_status']))
	{
		$IDs=explode('-', $v['item_number']);
 		$Transaction=array(	
							'PURIDD'=>$IDs[1] ,
							'PermitID' =>$IDs[2] ,
 							'PaymentType' =>1,
 							'Confirmation' =>$v['payment_status'],
							'DepositAmmount' =>$v['mc_gross'] + $v['mc_fee'],
							'PayerID' =>$User_ID,
							'PermitAmmount'=>$v['mc_gross'],
							'TransactionID'=>$v['txn_id'],
  							);	
		
		$TransactionID=custom_insert( 'paymenttransaction' ,$Transaction);		
		 
		if($v['payment_status']=='Completed')
		{
 			$cond=array('PURID' => $IDs[1]);
			$data=array( 'PaymentID'=>$TransactionID , 'Status'=>1);
			custom_update('permit_user_relation', $data ,$cond) ; 
			 
			 	$cond=array('PermitId' => $IDs[2]);
				$data=array( 'Archived'=>date('Y-m-d') , 'Visible'=>0);
 
			 custom_update('permit', $data ,$cond) ;
 			 wp_redirect( get_permalink( $Options['successfull_payment']['value']) );
			 exit();
 			}
		
 		}
  // End For Paypal Return Data
  
 	if(isset($v['action']))
	{
	 
	$actiontypes=explode('_',$v['action']);
	
	if($actiontypes[0]=='ajaxe')
	{	
		//For Ajax .....
	switch($actiontypes[1])
	{
		//Del operations .....
		case 'DelVehicle': 		
 		 $wpdb->delete( $prifix.'vehicle', array( 'VehicleID' => $v['InfoID'] ) );
 	 	echo json_encode(array('message'=>'Deleted Record Successfully'));
		break ;	
		 
		//End Del operations .....	
		// Get Operations		
		case 'getVehicle': 
	 	 $q="SELECT * FROM  `{prefix}vehicle` where VehicleID=".$v['InfoID'];
 	 	echo json_encode(getdata($q));		
		break ;
		case 'getLotInfo':
 	 	 $q="SELECT * FROM  `{prefix}lot` where LotID=".$v['InfoID'];
		 $q1="SELECT * FROM {prefix}permit where Lot=".$v['InfoID']." order by PermitNo ASC";
 		 $data=array('permitlist'=>makelists('','PermitId' ,'PermitNo' , '' , $q1  ),'Lot'=>getdata($q));
		 echo json_encode($data);		
		break ;
		case 'loadpaymentForm':

		$q='SELECT pur.* ,l.LotName , p.* FROM
				  `{prefix}permit_user_relation` `pur`
				  LEFT OUTER JOIN `{prefix}permit` `p` ON (`pur`.`PermitID` = `p`.`PermitId`)
				  LEFT OUTER JOIN `{prefix}lot` `l` ON (`p`.`Lot` = `l`.`LotID`)
				  where PURID='.$v['InfoID'];
		$permit=getdata($q);
	
		$data=array( 	'permitNumber'=>$permit[0]->PURID.'-'.$permit[0]->PermitID,
						'ammount'=>$permit[0]->Ammount,
						'invoice_no'=>'Permit_Invoice-'.date("Ymds")
					);
		
		$html='<table width="50%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td>Ammount:</td>
					<td><strong>'.$permit[0]->Ammount.'$</strong></td>
				  </tr>
				  <tr>
					<td>Permit Number:</td>
					<td><strong>'.$permit[0]->PermitNo.'</strong></td>
				  </tr>
				  <tr>
					<td>Lot Name:</td>
					<td><strong>'.$permit[0]->LotName.'</strong></td>
				  </tr>
				  <tr>
					<td>Payments Type:</td>
					<td><strong>'.$permit[0]->DurationType.'</strong></td>
				  </tr>
				</table><br />';
 

		 echo $html.paypalbyform($data ,'sandbox' );
 
 		break ;
		 
		}// End Ajax Switch
 
		exit();
		}
			
	// For Forms
	switch($v['action'])
	{ 
		case 'RegisterPermit':
		$duration=1;		
		$ammount=0;
 		
		if($v['Permit'] =='' or $v['Permit'] ==0)
		{
			setMessage('Please Select Permit to register',1);
			break;
			}
 
 		$q="SELECT COUNT(PermitID) as counter FROM {prefix}permit_user_relation where (Status=2 or Status=1) and PermitID=".$v['Permit'];		 
		$permits=getdata($q);		
		if($permits[0]->counter > 0)
		{
			setMessage('This Permit already registered please choose another.',1);
			break;
			
			}
		
		switch($v['Durationtype'])
		{
			case 'quarter':
			case 'yearly':
				$ammount=$v[$v['Durationtype']];
			break ;
			case 'monthly':
 				$ammount=$v[$v['Durationtype']] *$v['monthlyDuration'] ;
				$duration=$v['monthlyDuration'];
			break ;
			case 'daily':
 				$ammount=$v[$v['Durationtype']] *$v['daily'] ;
				$duration=$v['daily'];
			break ;
			
 		}
		
  			$data=array(
							'UserID' => $User_ID,
							'PermitID' => $v['Permit'],
							'Ammount' =>  $ammount ,
							'Duration' => $duration,
							'DurationType' => $v['Durationtype'],
							'Status' => 2,
							'PermitStartDate' => $v['StartingDate']	,
							 									
						);
			
		$insertedid=custom_insert('permit_user_relation' , $data)	;
  		if($insertedid)
			setMessage('Added Permit Successfully.');	
 		
		break ;
		case 'AddVehicle':
 			$q="SELECT count(VehicleID) as counter FROM {prefix}vehicle where  PlateNumber='".$v['LicencePlate']."'";
			$vehicle = getdata($q);
			if($vehicle[0]->counter > 0 )
				{
					setMessage('Vehicle is already registered' ,1);
					break ;
					}
			
			
		$data=array(
							'Active' => 1,
							'PlateNumber' => $v['LicencePlate'],
							'MakeID' =>  $v['Make'] ,
							'TypeID' => $v['Type'],
							'Colour' => $v['color'],
							'Year' => $v['Years'],
							'States' => $v['states']	,
							'UserID' => $User_ID ,										
						);
			
 		$insertedid=custom_insert('vehicle' , $data)	;
  		if($insertedid)
			setMessage('Added Vehicle Successfully.');					 
		break ;
		case 'UpdateVehicle':
		
		$q="SELECT count(VehicleID) as counter FROM {prefix}vehicle where VehicleID!=".$v['PID']." 
				and PlateNumber='".$v['LicencePlate']."' ";
				
			$vehicle = getdata($q);
			if($vehicle[0]->counter > 0 )
				{
					setMessage('Vehicle is already registered with this licence plate number' ,1);
					break ;
					}
 			
		$cond=array('VehicleID'=>$v['PID'] );
		$data=array(
							'Active' => 1 ,
							'PlateNumber'=>$v['LicencePlate'] ,
							'MakeID' =>  $v['Make'] ,
							'TypeID' => $v['Type'] ,
							'Colour' => $v['color'] ,
							'Year' => $v['Years'] ,
							'States' => $v['states'] ,
 						);
 			
		$insertedid=custom_update('vehicle', $data ,$cond) 	;
  		if($insertedid)
			setMessage('Updated Vehicle Successfully.');					 
		break ;
 		
		}
	
	} 
	
}// End FormProcessUser
add_action('wp', 'FormProcessUser');

?>