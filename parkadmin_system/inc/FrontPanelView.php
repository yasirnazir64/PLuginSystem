<?php

//Front Panel

function FrontUserPanel()
{	
 global $post;
 $base_plugin_url = plugin_dir_url( 'parkadmin_system' ).'parkadmin_system/';
 $current_usrID=get_current_user_id();
 $userInfo = get_user_by( 'id',$current_usrID );
 $AdminOpt=AdminSideOptions();
 
 if($current_usrID==0)
 	{
		if($AdminOpt['data']['LoginPage']['value']==0)
				$loginPage=wp_login_url( get_permalink($post->ID) );
			else
				$loginPage=get_permalink($AdminOpt['data']['LoginPage']['value']) ;
 
 		$message='User Is Not Logged in.Please Register or login first.<br />
		<a href="'.$loginPage.'"> Click Here To Login </a>'; 
		setMessage($message,1);
	}
  
 $payments=PaymentsHistory(array('UserID'=>$current_usrID));
 
 $q="SELECT * FROM
  `{prefix}permit_user_relation` as `pur`
  LEFT OUTER JOIN `{prefix}permit` as `p` ON (`pur`.`PermitID` = `p`.`PermitId`)
  LEFT OUTER JOIN `{prefix}lot` as `l` ON (`p`.`Lot` = `l`.`LotID`)
  where  UserID=$current_usrID";
   $registeredPermits=getdata($q);
 
?><div id="processindicator"></div>


<script src="<?php echo home_url() ?>/wp-includes/js/jquery/jquery.js" ></script>
<script src="<?php echo $base_plugin_url; ?>js/validator.js" ></script>				
<script src="<?php echo $base_plugin_url; ?>js/jquery-ui.js" ></script>					
<script type="text/javascript" >

 var base_plugin_url="<?php echo $base_plugin_url; ?>";
	 // Css Embeding Code
 var linkstyle='<link rel="stylesheet"   href="'+base_plugin_url+'css/jquery-ui.css" type="text/css" media="all">'
					+ '<link rel="stylesheet"   href="'+base_plugin_url+'css/parking.css" type="text/css" media="all">';
  jQuery("head").append(linkstyle);
 
  jQuery(document).ready(function($){
	
	 var base_plugin_url="<?php echo $base_plugin_url; ?>";
	 	  
 	 // Payment Form 
	
	jQuery( "#paymentForm" ).dialog({
      autoOpen: false,
	  title:"Payment Processing",
      width: 450,
      modal: true,
      buttons: {
        "Process": function() {
			
           				
			var busnise=jQuery( "#paymentForm [name=business]" ).val();
			
			//paypal_form
				if(busnise!='')
					{
						jQuery( "#paymentForm #processingid" ).html('Processing...');
						jQuery( "#paymentForm #processingid" ).addClass('processing');		
						setTimeout("document.paypal_form.submit()",300);
					}
					else
						alert('The Payment System is disable.'); 		   
         },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      
    });
 	
		jQuery( "#frontPanelContainer .registeredunpaid" ).click(function() {
		 ID=jQuery(this).attr('id');
		 id_array=ID.split('_');
  		 jQuery.post("",{InfoID:id_array[1],action:'ajaxe_loadpaymentForm' },function(result)
				{		  
					jQuery( "#paymentForm" ).html(result);
					jQuery( "#paymentForm" ).dialog('open');
					});
			});
	
	// End Payment Form
	// Permit Registeration
	
	jQuery( "#SelectLot" ).button();
	jQuery( "#RegisterPermit [name='StartingDate']" ).datepicker({ dateFormat: "yy-mm-dd" });
 
	jQuery( "#RegisterPermit" ).dialog({
      autoOpen: false,
	  title:"Register Permit",
      width: 450,
      modal: true,
      buttons: {
        "Submit": function() {
           
		    if (  permit.exec() ) 
 			   $( "[name='RegisterPermitForm']" ).submit() ;
 
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      
    });
	
	
	jQuery( "#RegisterPermit [type=radio]" ).click(function(){
		Naming='#RegisterPermit [name=' + jQuery(this).val() + ']' ;
		infoid=jQuery(Naming).val();
		if(parseInt(infoid)==0)
				{
					alert('Option Is Not Available');
					return false;
				}
 		
		});
	
	jQuery( "#SelectLot" ).click(function(){
		infoid=jQuery("[name='LotsSelect']").val();
		if(infoid > 0 )
		 jQuery.post("",{InfoID:infoid,action:'ajaxe_getLotInfo' },function(result)
				{	
					
  					var Data = eval ("(" + result + ")");
					LotInfo=Data.Lot[0];
					 
 						$( "#RegisterPermitForm [name='Permit']" ).html('<option value="0"> Select Permit </option>' + Data.permitlist);
						$( "#RegisterPermitForm #dalyprice" ).html(LotInfo.DailyCost + '$ Per Daily');
						$( "#RegisterPermitForm [name='daily']" ).val(LotInfo.DailyCost );
						$( "#RegisterPermitForm #monthlyprice" ).html(LotInfo.MonthlyCost + '$ Per Month');
						$( "#RegisterPermitForm [name='monthly']" ).val(LotInfo.MonthlyCost );
						$( "#RegisterPermitForm #quarterprice" ).html(LotInfo.QuarterlyCost + '$ Per Quarter');
						$( "#RegisterPermitForm [name='quarter']" ).val(LotInfo.QuarterlyCost );
						$( "#RegisterPermitForm #yearlyprice" ).html(LotInfo.YearlyCost + '$ Per Year');
						$( "#RegisterPermitForm [name='yearly']" ).val(LotInfo.YearlyCost );
						$( "#RegisterPermitForm [name='LOTID']" ).val(LotInfo.LotID);
						$( "#RegisterPermitForm #LotName" ).html(LotInfo.LotName );
						
						
						jQuery( "#RegisterPermit" ).dialog('open');
								
						 
  
  					});
					else
					alert('Please Select The Lot To register');
		 
		
		});
	
	//  End Permit 
 	//  Vehicle Script

     jQuery( "#VehicleForm" ).dialog({
      autoOpen: false,
	  title:"Add New Vehicle",
      width: 450,
      modal: true,
      buttons: {
        "Submit": function() {
           Valid=vehicle.exec() ;
           if (  Valid ) 
 			   $( "[name='vehicleform']" ).submit() ;
			 },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      
    });
 
    jQuery( "#Vehiclebtn" )
      .button()
      .click(function() {
		jQuery( "#vehicleform [name='action']" ).val('AddVehicle');	
		jQuery( "#VehicleForm" ).dialog({ title: "Add New Vehicle" });
        jQuery( "#VehicleForm" ).dialog("open");
      });
	  
 	jQuery( "#frontPanelContainer .del" ).click(function() {
		 ID=jQuery(this).attr('id');
		 id_array=ID.split('_');
		
		if(confirm('Are you sure you want to delete this record ?'))
		 jQuery.post("",{InfoID:id_array[1],action:'ajaxe_'+ id_array[0]},function(result)
				{		  
					var Data = eval ("(" + result + ")");
					alert(Data.message);
					window.location=window.location;
					});
	
 	});
	
	
	jQuery( "#frontPanelContainer .edit" ).click(function() {
		 ID=jQuery(this).attr('id');
		 id_array=ID.split('_');
		
 		 jQuery.post("",{InfoID:id_array[1],action:'ajaxe_'+ id_array[0]},function(result)
				{		
 					var Data = eval ("(" + result + ")");
					
 						$( "#vehicleform [name='action']" ).val('UpdateVehicle');	
						$( "#vehicleform [name='PID']" ).val(id_array[1]);		
						$( "#vehicleform [name='LicencePlate']" ).val(Data[0].PlateNumber);
						$( "#vehicleform [name='states']" ).val(Data[0].States);
						$( "#vehicleform [name='color']" ).val(Data[0].Colour);
						$( "#vehicleform [name='Make']" ).val(Data[0].MakeID);
						$( "#vehicleform [name='Type']" ).val(Data[0].TypeID);
						$( "#vehicleform [name='Years']" ).val(Data[0].Year);
						$( "#VehicleForm" ).dialog({ title: "Update Vehicle" });
						$( "#VehicleForm" ).dialog('open');
					
 					});
	
 	});
	
	// End Vehicle Script
 
	
     jQuery( "#frontPanelContainer" ).tabs({
   
    activate : function( event, ui ) {
		
		Href=ui.newTab.context.href ;
		Href_s=Href.split('#');
         var newIndex = ui.newTab.context.href   ;
		 document.cookie = "CurrentTabName=#" + Href_s[1];
 		 panel_offset=document.getElementById('frontPanelContainer').offsetHeight-150 ;
 		 window.location=newIndex;
		 window.scrollTo(0,panel_offset);

           // my setup requires the custom path, yours may not
     }
} );	 
 
 
});
 
 </script>	
    <div id="frontPanelContainer">
    <h1 class="panelheading borderb"><?php echo $AdminOpt['data']['parkadmin_heading']['value']; ?></h1>
     <?php 
 if($current_usrID!=0){ ?>
    
     <div id="lefttabspanel">
          <ul>
            <li><a href="#vehicles">Vehicles:</a></li>
            <li><a href="#PermitPanel">Register Permit:</a></li>
            <li><a href="#PaymentHistory">Payment History:</a></li>
	</ul>
  </div>
   <?php  } ?>
   <!--  End lefttabspanel-->
  <div id="rightpanel">
    <?php echo getMessage();
 if($current_usrID!=0){ ?>
   
                          <div id="vehicles">
                              <?php
                             
                             $q="SELECT v.*,m.MakerName,t.TypeName FROM
                                  `{prefix}vehicle` `v`
                                  LEFT OUTER JOIN `{prefix}vehiclemaker` `m` ON (`v`.`MakeID` = `m`.`VehicleMakerID`)
                                  LEFT OUTER JOIN `{prefix}vehicletype` `t` ON (`v`.`TypeID` = `t`.`VehicleTypeID`)
								  where v.UserID=$current_usrID
                                  order by v.created DESC " ;
                             $vehicles=getdata($q);
                             
                             ?>
                            <h2 class="center borderb" >Vehicles</h2>
                           
                            <table class="grids" width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <th>Plate</th>
                            <th>Color</th>
                            <th>Make</th>
                            <th>Type</th>
                            <th>State</th>
                            <th>Year</th>
                            <th>Options</th>
                          </tr>
                           <?php
						   if(sizeof($vehicles) > 0)
                             foreach($vehicles as $v){
                             ?>
                          <tr>
                            <td><?php echo $v->PlateNumber; ?></td>
                            <td><?php echo $v->Colour; ?></td>
                            <td><?php echo $v->MakerName; ?></td>
                            <td><?php echo $v->TypeName; ?></td>
                            <td><?php echo $v->States; ?></td>
                            <td><?php echo $v->Year; ?></td>
                            <td>
                            <a href="javascript:void(0);" id="getVehicle_<?php echo $v->VehicleID ?>" class="edit"> </a> 
                             <a href="javascript:void(0);" id="DelVehicle_<?php echo $v->VehicleID ?>" class="del"> </a>
                             </td>
                          </tr>
                          <?php }
						  else{ ?>
                          <tr>
                            <td colspan="7" class="center" >Record Not Found.</td>
                             
                          </tr>
                          <?php } ?>
                          <tr>
                            <td colspan="7" ><input type="button" id="Vehiclebtn" class="addnewbtn" value="Add New Vehicle" /></td>
                             
                          </tr>
                        </table>
                            
                            <div id="VehicleForm" class="form">
                         <form action="" method="post" name="vehicleform" id="vehicleform" >
                         <input type="hidden" value="AddVehicle" name="action" />
                         <input type="hidden" value="" name="PID" />
                         
                         
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%" >License Plate:  </td>
                            <td width="50%" ><input type="text" value="" name="LicencePlate" id="LicencePlate" /></td>
                          </tr>
                          <tr>
                            <td>State:  </td>
                            <td><input type="text" value="" name="states" id="states" maxlength="2"  /></td>
                          </tr>
                          <tr>
                            <td>Color:  </td>
                            <td><input type="text" value="" name="color" id="color"   /></td>
                          </tr>
                          <tr>
                            <td>Make:  </td>
                            <td><select name="Make"  id="Make"   >
                            <option value=""> Select Vehicle Maker </option>
                              <?php 
                            $q='SELECT * FROM  `{prefix}vehiclemaker` ORDER BY `MakerName` ASC  ';
                            echo makelists('','VehicleMakerID' ,'MakerName' , '' , $q  );
                            ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td>Type:  </td>
                            <td><select name="Type"  id="Type"    >
                            <option value=""> Select Vehicle Types </option>
                            <?php 
                            $q='SELECT * FROM  `{prefix}vehicletype` ORDER BY `TypeName` ASC ';
                            echo makelists('','VehicleTypeID' ,'TypeName' , '' , $q  );
                            ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td>Year:  </td>
                            <td><input type="text" value="" name="Years"   id="Years"   /></td>
                          </tr>
                          <tr>
                            <td valign="top" >Terms And Services:   </td>
                            <td> <input type="checkbox" name="Terms" id="Terms" /> Accept <br />
                            <?php echo $AdminOpt['data']['terms_ofservices']['value']; ?></td>
                          </tr>
                         </table>
                       </form> 
                         </div>   
                           
                          </div>
                          
                          <div id="PermitPanel">
                            <h2 class="center borderb" > Register Permit </h2>
                             
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>
                            <select name="LotsSelect">
                            <option>Select Permit</option>
                            <?php 
                            $q="SELECT l.* , CONCAT_WS(' ',LotName ,' [ ',(SELECT count(PermitId) FROM  `{prefix}permit` as p where p.Lot=l.LotID and
							 p.Archived is NULL and p.Visible=1 ) ,' Available Permits ]') as permits 
							   FROM  `{prefix}lot` as l
							   where l.Visible=1 and 
							   (SELECT count(PermitId) FROM  `{prefix}permit` as p where p.Lot=l.LotID and p.Archived is NULL and p.Visible=1 ) > 0
							   ORDER BY LotName ASC ";
                         
                            echo makelists('','LotID' ,'permits' , '' , $q  );
                            ?>
                            </select>
                            </td>
                            <td>
                            <input type="button" id="SelectLot" value="Register Permit in this Lot" /></td>
                          </tr>
                        </table>
                        
                        <h2 class="center borderb" > Permit History </h2>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <th>No.</th>
                            <th>Lot</th>
                            <th>Submitted</th>
                            <th>Start </th>
                            <th>Expiry </th>
                            <th></th>  
                          </tr>
                          <?php if(sizeof($registeredPermits)>0) { 
                          foreach($registeredPermits as $v){     ?>
                          <tr>
                            <td><?php echo $v->PermitNo; ?></td>
                            <td><?php echo $v->LotName; ?></td>
                            <td><?php echo DateFormating($v->RegisterDate , 'd-M-Y' ) ?></td>
                            <td><?php echo DateFormating($v->PermitStartDate , 'd-M-Y' ) ?></td>
                            <td><?php 
                              switch($v->DurationType)
                            {
                                case 'daily':
                                    echo DateFormating($v->PermitStartDate , 'd-M-Y' , '+'.$v->Duration.' day' );
                                break ;
                                case 'monthly':
                                    echo DateFormating($v->PermitStartDate , 'd-M-Y' , '+'. $v->Duration .' month' );
                                break ;
                                case 'quarter':
                                    echo DateFormating($v->PermitStartDate , 'd-M-Y' , '+90 day' );
                                break ;
                                case 'yearly':
                                    echo DateFormating($v->PermitStartDate , 'd-M-Y' , '+1 year' );
                                break ;
                                }								                            
                              ?></td>
                            <td>
                            <?php 
                            
                            switch($v->Status)
                            {
                                case 1:// Registered and Paid
                                    echo '<a id="product_'.$v->PURID.'" class="permthstry registeredpaid" href="javascript:void(0);"></a>';
                                break ;
                                case 2:// Registered But UnPaid
                                    echo '<a id="product_'.$v->PURID.'" class="permthstry permitstatus registeredunpaid" title="Please Click To Pay"  href="javascript:void(0);"></a>';
                                break ;
                                case 3:// Registered But Expired
                                    echo '<a id="product_'.$v->PURID.'" class="permthstry permitstatus registeredexp" title="Please Click To renew the payments" href="javascript:void(0);"></a>';
                                break ;
                                }
                            
                              ?>
                              </td>
                          </tr>
                          <?php }
                          }else{
                           ?>
                            <tr>
                            <td colspan="6">Record Not Found</td>
                          
                          </tr>
                           <?php } ?>
                        </table>
                          
                        <div id="RegisterPermit" class="form">
                         <form action="" method="post" name="RegisterPermitForm" id="RegisterPermitForm" >
                         <input type="hidden" value="RegisterPermit" name="action" />
                         <input type="hidden" value="" name="LOTID" />
                          
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="30%" >Registrant:    </td>
                            <td width="70%" ><?php echo $userInfo->first_name . ' ' . $userInfo->last_name; ?></td>
                          </tr>
                          <tr>
                            <td>Selected Lot:    </td>
                            <td id="LotName"> </td>
                          </tr>
                          <tr>
                            <td>Available Permits:    </td>
                            <td>
                            <select name="Permit"  id="Permit"   >
                            <option value="0"> Select Permit </option>
                            </select>
                           </td>
                          </tr>
                          <tr>
                            <td>Start Date:   </td>
                            <td> <input type="text" value="" name="StartingDate" id="StartingDate"   /> </td>
                          </tr>
                          <tr>
                            <td valign="top">Rental Term:  </td>
                            <td> 
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><input type="radio" checked="checked" value="yearly" name="Durationtype" id="Durationtype"   /> Yearly
                            <input type="hidden" value="" name="yearly"  /></td>
                            <td   >&nbsp;</td>
                            <td id="yearlyprice" >&nbsp;</td>
                          </tr>
                          <tr>
                            <td><input type="radio" value="quarter" name="Durationtype" id="Durationtype"   /> Quarterly
                            <input type="hidden" value="" name="quarter"  />
                            </td>
                            <td>&nbsp;</td>
                            <td  id="quarterprice" >&nbsp;</td>
                          </tr>
                          <tr>
                            <td><input type="radio" value="monthly" name="Durationtype" id="Durationtype"   /> Monthly
                             <input type="hidden" value="1" name="monthly"  />
                             </td>
                            <td><input type="text" value="1" name="monthlyDuration" class="smallinput" maxlength="4" /></td>
                            <td  id="monthlyprice"  >&nbsp;</td>
                          </tr>  
                          <tr>
                            <td><input type="radio" value="daily" name="Durationtype" id="Durationtype"   /> Daily
                            <input type="hidden" value="1" name="daily"  />
                            </td>
                            <td><input type="text" value="1" name="dailyDuration" class="smallinput" maxlength="4" /></td>
                            <td id="dalyprice" >&nbsp;</td>
                          </tr>
                        </table>
                            </td>
                          </tr>
                        </table>
                         
                         </form> 
                         </div>
                        
                        <div id="paymentForm">
                         
                        </div>
                           </div>
                           <div id="PaymentHistory">
                            
                        <h2 class="center borderb" > Payment History </h2>
                        <table width="100%" class="grids"  cellspacing="0" cellpadding="0">
                          <tr>
                            <th>ID</th>
                            <th>Lot</th>
                            <th>Permit No</th>
                            <th>Ammount </th>
                            <th>Payment Date </th>
                            <th>Status</th>
                            <th>Payment Type</th>  
                          </tr>
                          <?php if(sizeof($payments['data'])>0) { 
                          foreach($payments['data'] as $v){  ?>
                          <tr>
                            <td><?php echo $v->PaymentID; ?></td>
                            <td><?php echo $v->LotName; ?></td>
                            <td><?php echo $v->PermitNo ?></td>
                            <td><?php echo $v->PermitAmmount ?>$</td>
                            <td><?php echo DateFormating($v->ProcessingDate , 'd-M-Y' ); ?></td>
                            <td><?php echo $v->Confirmation ;  ?></td>
                            <td><?php echo $v->DurationType ;  ?></td>
                          </tr>
                          <?php }
                           
                          }else{
                           ?>
                            <tr>
                            <td colspan="7">Record Not Found</td>
                          
                          </tr>
                           <?php } ?>
                             <tr>
                            <td class="center" colspan="7"><?php echo $payments['pagination'] ;  ?></td>
                          
                          </tr>
                        </table>
                        
                          </div>
  
  <?php  } ?>

</div><!--  End rightpanel-->
</div><!--  End frontPanelContainer-->
				
	<?php						
	emptyMessage();
	
	
					}
// Short Code To Call The Front Panel

//Front Panel
add_shortcode('UserFrontPanel' , 'FrontUserPanel' ); 






?>