<?php


function get($key)
{  return isset($_GET[$key])?trim($_GET[$key]):'' ;  }
	
function DateFormating($date , $format='' ,$modify='')	
{
 	if(is_object($date))
	{
		$newFormt=$date->format($format);
		if($modify!='')
			$newFormt=$newFormt->modify($modify);
		return  $newFormt;
		
		}
 	
	if( $date =='' or  $format=='')
		return false ;
		
	$newdat_obj=new DateTime($date);
	
	if($modify!='')
		$newdat_obj->modify($modify);
		
	$newFormt=$newdat_obj->format($format);
	return  $newFormt;
	}
	
function emptyMessage()
{
	global $TransitionMessage;
	$TransitionMessage='';
	}
function getMessage()
{
	global $TransitionMessage;
	$data=$TransitionMessage; 
	return 	$data;

	}
function setMessage($message,$type=0)
{
 	global $TransitionMessage;
	if($type)
		$TransitionMessage='<div  class="error" >'.$message.'</div>' ; 
	else
		$TransitionMessage='<div class="success" >'.$message.'</div>' ; 
		
  	}
		
 
function makequery($q,$current ,$offset)
 {	 
	 $page=($current > 1 )?($current*$offset ):0;
	 
	 $limit= $page." , ".$offset ;
	 
	 return str_replace('{limit}',$limit ,$q);
	 }

function TotalRecord($table , $q='')
{
	
	global $wpdb ;
	$prifix=$wpdb->prefix;
	if($q=='')
		$q='select count(*) as total from '.$prifix.$table;
		else
		$q=str_replace('{prefix}',$prifix ,$q);
 
 	$result=$wpdb->get_results($q );
  	$total=$result[0]->total;
 	return $total;
	}

function Wp_pagination($current , $total)
{
	$pageCookie=(isset($_COOKIE['CurrentTabName']))?$_COOKIE['CurrentTabName']:'';
 	$total=round(($total + 15)/15) ;
  	$pagination=paginate_links( array(
									'base' => str_replace( 12250, '%#%', esc_url( get_pagenum_link( 12250 ) ) ),
									'format' => '?paged=%#%',
									'current' => max( 1,$current ),
									'total' => $total ,
									'add_fragment'=>$pageCookie
										) );
	
	$pagination='<div class="pagination" >'.$pagination.'</div>';
		
	return $pagination;
	}	

 function getparkinglots()
{
	
	global $wpdb ;
	$prifix=$wpdb->prefix;
 
	$paged = $_GET['paged']? $_GET['paged'] :1; 
	$q="SELECT * FROM  `".$prifix."lot` ORDER BY  `LotID` ASC LIMIT {limit}";
	$data['data']=$wpdb->get_results(makequery($q,$paged ,15)  );
	 
	$data['pagination']=Wp_pagination($paged ,TotalRecord( 'lot'));
 
	return $data;
	
	
	}

function getdata($q)
{
	global $wpdb ;
	$prifix=$wpdb->prefix;
	$q = str_replace('{prefix}',$prifix,$q);
	$data=$wpdb->get_results($q);
	return $data;

	}
function custom_insert($table , $data)
{
	global $wpdb ;
	$prifix=$wpdb->prefix;
	
	$oper=$wpdb->insert(
						$prifix.$table,
						$data
					);
					
	if($oper)
		return $wpdb->insert_id;
		else
		return false ;
	
	
	}
function num_format($number , $place=2 )	
	{
		$number=number_format($number ,$place );
		return $number;
		}
function custom_update($table , $data ,$cond)
{
	global $wpdb ;
   	$arr=$wpdb->update( 
						$wpdb->prefix.$table, 
						$data, 
						$cond						
						);
  	if($arr)
		return true;
		else
		return false ;	
	}
function getPermits()
{
	global $wpdb ;
	$prifix=$wpdb->prefix; 
	$paged = $_GET['paged']? $_GET['paged'] :1; 
 	
	$q="SELECT p.* , l.LotName, l.Location FROM
		  `".$prifix."permit` as p
		  LEFT OUTER JOIN `".$prifix."lot` as l ON (p.`Lot` =l.`LotID`)  
		  ORDER BY  `PermitId` ASC LIMIT {limit}" ;
		  
	$data['data']=$wpdb->get_results(makequery($q,$paged ,15)  );	 
	$data['pagination']=Wp_pagination($paged ,TotalRecord( 'permit'));
 
	return $data;
 	
	}

function PaymentsHistory($data='')
{
	global $wpdb ;
	$where='';
	
	if(is_admin())
			$paged = $_GET['paged']? $_GET['paged'] :1; 
		else
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	
	if(isset($data['UserID']))
		 $where = " and (PayerID='".$data['UserID']."' or u.user_login LIKE '".$data['UserID']."%' ) ";
		 
	if(isset($data['PermitNo']) and is_numeric($data['PermitNo']) )
			 $where = ' and p.PermitNo='.$data['PermitNo'];	 
	
	if(isset($data['TransactionID']) and is_numeric($data['TransactionID']) )
			 $where = ' and pay.PaymentID='.$data['TransactionID'];	
			  
	if(isset($data['PaypalTnxID']) and $data['PaypalTnxID']!='' )
			 $where = " and pay.TransactionID='".$data['PaypalTnxID']."'";
			 
	if(isset($data['User']) and $data['User']!='' )
			 $where = " and u.user_login like ='".$data['PaypalTnxID']."%'";	
	
	if(isset($data['FromDate']) and $data['FromDate']!='' )
			 $where = " and DATE_FORMAT(pay.ProcessingDate, '%Y-%m-%d') >= '".$data['FromDate']."'";			 
	
	if(isset($data['ToDate']) and $data['ToDate']!='' )
			 $where = " and DATE_FORMAT(pay.ProcessingDate, '%Y-%m-%d') <= '".$data['ToDate']."'";		  		 	 
		
  $q="SELECT l.*,p.*,pur.*,pay.*,u.* FROM
  `{prefix}paymenttransaction` `pay`
  LEFT OUTER JOIN `{prefix}permit_user_relation` `pur` ON (`pay`.`PURIDD` = `pur`.`PURID`)
  LEFT OUTER JOIN `{prefix}users` `u` ON (`pur`.`UserID` = `u`.`ID`)
  LEFT OUTER JOIN `{prefix}permit` `p` ON (`pur`.`PermitID` = `p`.`PermitId`)
  LEFT OUTER JOIN `{prefix}lot` `l` ON (`p`.`Lot` = `l`.`LotID`)
  where 1=1
  $where 
  order by pay.ProcessingDate DESC LIMIT {limit}" ;
  
  
  $T="SELECT count(PayerID) as total FROM
  `{prefix}paymenttransaction` `pay`
  LEFT OUTER JOIN `{prefix}permit_user_relation` `pur` ON (`pay`.`PURIDD` = `pur`.`PURID`)
  LEFT OUTER JOIN `{prefix}users` `u` ON (`pur`.`UserID` = `u`.`ID`)
  LEFT OUTER JOIN `{prefix}permit` `p` ON (`pur`.`PermitID` = `p`.`PermitId`)
  LEFT OUTER JOIN `{prefix}lot` `l` ON (`p`.`Lot` = `l`.`LotID`)
  where 1=1
  $where 
  order by pay.ProcessingDate" ;
  
  
   $Am="SELECT SUM(pay.PermitAmmount) as total FROM
  `{prefix}paymenttransaction` `pay`
  LEFT OUTER JOIN `{prefix}permit_user_relation` `pur` ON (`pay`.`PURIDD` = `pur`.`PURID`)
  LEFT OUTER JOIN `{prefix}users` `u` ON (`pur`.`UserID` = `u`.`ID`)
  LEFT OUTER JOIN `{prefix}permit` `p` ON (`pur`.`PermitID` = `p`.`PermitId`)
  LEFT OUTER JOIN `{prefix}lot` `l` ON (`p`.`Lot` = `l`.`LotID`)
  where 1=1
  $where 
  order by pay.ProcessingDate" ;
   	
  	$data['data']=getdata(makequery($q,$paged ,15));
 	$data['pagination']=Wp_pagination($paged ,TotalRecord( '' ,$T));
	$data['total_ammount']=getdata($Am);
 
	return $data;
	
 	}
	
	 		
function getVehiclesAdmin($vehicleID='')
{
	global $wpdb ;
	$prifix=$wpdb->prefix; 
	$paged = $_GET['paged']? $_GET['paged'] :1; 
	$where =' where 1=1 ';
	
	if($vehicleID > 0)
 			$where .=" and v.VehicleID=$vehicleID"; 
 	
	if(get('state'))
			$where .=" and v.States like '".get('state')."%'";
			
	if(get('PlateNumber'))
			$where .=" and v.PlateNumber like '". get('PlateNumber')."%'";
	if(get('Color'))
			$where .=" and v.Colour like '%". get('Color')."%'";
	if(get('VehicleMaker'))
			$where .=" and v.MakeID=". get('VehicleMaker');
		
	if(get('Year'))
			$where .=" and v.Year=". get('Year');
			
	if(get('VehicleType'))
			$where .=" and v.TypeID=". get('VehicleType');
	if(get('User'))
			$where .=" and u.user_login LIKE '". get('User')."%'";
  	
	
	$q="SELECT v.*,m.MakerName,t.TypeName ,u.* FROM
	 	  `{prefix}vehicle` `v`
		  LEFT OUTER JOIN `{prefix}vehiclemaker` `m` ON (`v`.`MakeID` = `m`.`VehicleMakerID`)
		  LEFT OUTER JOIN `{prefix}vehicletype` `t` ON (`v`.`TypeID` = `t`.`VehicleTypeID`)
		  LEFT OUTER JOIN `{prefix}users` `u` ON (`v`.`UserID` = `u`.`ID`)
		  
		  $where
		  order by v.created DESC LIMIT {limit}" ;
		  
	 $T="SELECT count(v.VehicleID) as total FROM
	 	  `{prefix}vehicle` `v`
		  LEFT OUTER JOIN `{prefix}vehiclemaker` `m` ON (`v`.`MakeID` = `m`.`VehicleMakerID`)
		  LEFT OUTER JOIN `{prefix}vehicletype` `t` ON (`v`.`TypeID` = `t`.`VehicleTypeID`) 
		  LEFT OUTER JOIN `{prefix}users` `u` ON (`v`.`UserID` = `u`.`ID`)
		  $where
		  order by v.created DESC" ;
		  
  	$data['data']=getdata(makequery($q,$paged ,15));
		 
	$data['pagination']=Wp_pagination($paged ,TotalRecord( '' ,$T));
 
	return $data;
	
	
	}
	
function makelists($data,$bound ,$text ,$selected='' , $q='' )
	{
	if($data=='')
	{ $data=getdata($q); }
 	$final='';
	
	
  	if(is_object($data[0]) or is_object($data))	
		foreach($data as $v)
		{
			$text_array='';
			if($v->$bound==$selected)
			$sel='selected="selected"';
			else
			$sel='';
			
			if(is_array($text))
			{
				foreach($text as $val)
				{
					$text_array.= $v->$val.' - ';
				}
				$final.='<option '.$sel.' value="'.$v->$bound.'" > '.trim($text_array,' - ').' </option>';	
			}	
			else
				$final.='<option '.$sel.' value="'.$v->$bound.'" > '.$v->$text.' </option>';
					
			}
			
	else
		foreach($data as $v)
		{
			$text_array='';
			if($v[$bound]==$selected)
			$sel='selected="selected"';
			else
			$sel='';
			
			if(is_array($text))
			{
				foreach($text as $val)
				{
					$text_array.= $v[$val].' - ';
				}
				$final.='<option '.$sel.' value="'.$v[$bound].'" > '.trim($text_array,' - ').' </option>';	
			}	
			else
				$final.='<option '.$sel.' value="'.$v[$bound].'" > '.$v[$text].' </option>';
					
			} 
		return $final;
	}
	
// ************** Payment Function For Paypal

function paypalbyform($data ,$environment )
{
	$options=AdminSideOptions();
	$ApiUSerName=$options['data']['paypal_account']['value'];
	$StoreLogo=$options['data']['paypal_custom_logo']['value'];
	
 	$paypal_url  = ($environment=='sandbox') ? 'https://www.sandbox.paypal.com/webscr' : 'https://www.paypal.com/webscr';
	$form='
	<form method="post" name="paypal_form" action="'.$paypal_url.'">	
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="business" value="'.$ApiUSerName.'">
	<input type="hidden" name="return" value="'.home_url().'/">
	<input type="hidden" name="cancel_return" value="'.home_url().'/">
	<input type="hidden" name="notify_url" value="'.home_url().'/">
	<input type="hidden" name="item_name" value="Permit Registeration">
	<input type="hidden" name="item_number" value="Permit-'.$data['permitNumber'].'">
	<input type="hidden" name="cbt" value="Click Here to return For Further Process.">
	<input type="hidden" name="image_url" value="'.$StoreLogo.'">
	<input type="hidden" name="amount" value="'.$data['ammount'].'" />
	<input type="hidden" name="invoice" value="'.$data['invoice_no'].'">
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="custom" value="ParkAdminLiteModule">
</form>';

$form.='<div id="processingid" class=" center bold"></div>';
	
	return $form;
	}

?>