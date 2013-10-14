<?php

function VehicleMaker()// Display Admin Section
	{
	
	$VehiclMaker=getdata("SELECT *,(SELECT count(VehicleID) FROM  `{prefix}vehicle` as v where vm.VehicleMakerID=v.MakeID ) as vehicles FROM  `{prefix}vehiclemaker` as vm order by MakerName ASC");
		
		?>
 <div id="parkingsystem_container">
 <div id="adminlogo"></div>
  <h2 class="admintitles center" >Vehicle Makes</h2>
   <?php echo getMessage(); ?>
  <table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td colspan="5" align="center" valign="middle">
    <input type="submit" name="InsertNewLot" id="InsertNewLot" value="Add New">
    </td>
    </tr>
  <tr>
    <th>Serial</th>
    <th>Maker Name</th>
    <th>Vehicle Registered</th>
    <th>Options</th>
  </tr>
  
<?php 
$counter=1;
  if(sizeof($VehiclMaker)==0)
  	echo '<tr><td colspan="5" align="center" valign="middle">
   			No Record Found
    </td></tr>' ;
	else
  foreach($VehiclMaker  as $v) { ?>
  <tr>
    <td><?php echo $counter++ ?></td>
    <td><?php echo $v->MakerName ?></td>
    <td> Vehicles[<?php echo $v->vehicles ?>] </td>
    <td align="center">
     
     <a href="javascript:void(0);" id="getVehiclemaker_<?php echo $v->VehicleMakerID ?>" class="edit"> </a> 
     <a href="javascript:void(0);" id="DelVehiclemaker_<?php echo $v->VehicleMakerID ?>" class="del"> </a> 
     </td>
  </tr>
  <?php } ?>
   
</table>


<div id="AddNewLotForm">
<form action="" method="post"  id="AddNewVehiclMaker" >
<input type="hidden"  name="PID" value="" />
<input type="hidden"  name="action" value="AddNewVehiclMaker" />
<h3  class="center borderb"  >Insert New Vehicle Maker</h3>
 
      <table   style="margin:10px auto;">
         <tr>
            <th  >Vehicle Make: &nbsp;</th>
            <td  ><input type="text" value="" name="makername"></td>
         </tr>
          </table>   
      
</form>
</div>
 
 <script>
 
 
   // For The Edit
	  $('#parkingsystem_container  .edit').click(function(){
	
	iddata=$(this).attr('id');
	values=iddata.split('_');
	
	 $.post("",{InfoID:values[1],action:'ajaxe_'+ values[0]},function(result)
			{		  
				var Data = eval ("(" + result + ")");
				$( "#AddNewLotForm [name='action']" ).val('UpdateVehiclMaker');	
				$( "#AddNewLotForm [name='PID']" ).val(values[1]);		
				$( "#AddNewLotForm [name='makername']" ).val(Data[0].MakerName);
				$( "#AddNewLotForm h3" ).html( 'Update Vehicle Make' );
				$( "#AddNewLotForm" ).dialog({title:'Update Vehicle Make'});
 				$( "#AddNewLotForm" ).dialog('open');				
			  	});			
	});
	
		// For the Dell
	  $('#parkingsystem_container  .del').click(function(){
		iddata=$(this).attr('id');
		values=iddata.split('_');
		if(confirm('Are you sure you want to delete this record ?'))
		 $.post("",{InfoID:values[1],action:'ajaxe_'+ values[0]},function(result)
				{		  
					var Data = eval ("(" + result + ")");
					alert(Data.message);
					window.location=window.location;
					});
	});
	
 
 
  $(function() {
	 
	  	 $( "#AddNewLotForm" ).dialog({
      autoOpen: false,
	  title:'Add New Vehicle Maker',
      width: 450,
      modal: true,
      buttons: {
        "Submit": function() {
            $( "#AddNewVehiclMaker" ).submit() ;
        },
        Cancel: function() {
			
          $( this ).dialog( "close" );
        }
      },
      
    });
 		
		$( "#InsertNewLot" ).button();
		
 		$( "#InsertNewLot" ).click(function(){
			
			
			$( "#AddNewLotForm [name='action']" ).val('AddNewVehiclMaker');
			$( "#AddNewLotForm [name='makername']" ).val('');
			$( "#AddNewLotForm h3" ).html( 'Add New Vehicle Make' );
 			$( "#AddNewLotForm" ).dialog({title:'Add New Vehicle Make'});
			$( "#AddNewLotForm" ).dialog('open');
			
			});
  });
 
 
 
 
 
 
 
 </script>
</div>
	<?php
	
	}// End VehicleMaker();

function VehicleType()// Display Admin Section
	{
	$VehiclType=getdata("SELECT * ,(SELECT count(VehicleID) FROM  `{prefix}vehicle` as v where vt.VehicleTypeID=v.TypeID ) as vehicles FROM  `{prefix}vehicletype` as vt order by TypeName ASC ");
		
		?>

<div id="parkingsystem_container">
<div id="adminlogo"></div>
  <h2 class="admintitles center" >Vehicle Types</h2>
   <?php echo getMessage(); ?>
  <table width="100%" border="0" cellspacing="1" cellpadding="0">
   <tr>
    <td colspan="4" align="center" valign="middle">
    <input type="submit" name="InsertNewLot" id="InsertNewLot" value="Add New">
    </td>
    </tr>
  <tr>
    <th>Serial</th>
    <th>Vehicle Type Name</th>
    <th>Vehicle registered</th>    
    <th>Options</th>
  </tr>
  
  <?php 
  $counter=1;
  if(sizeof($VehiclType)==0)
  	echo '<tr><td colspan="5" align="center" valign="middle">
   			No Record Found
    </td></tr>' ;
	else
  foreach($VehiclType  as $v) { ?>
  <tr>
    <td><?php echo $counter++ ?></td>
    <td><?php echo $v->TypeName ?></td>
     <td> Vehicles[<?php echo $v->vehicles ?>] </td>
    <td><a href="javascript:void(0);" id="getVehicleType_<?php echo $v->VehicleTypeID ?>" class="edit">asas</a> 
     <a href="javascript:void(0);" id="DelVehicleType_<?php echo $v->VehicleTypeID ?>" class="del">asas</a></td>
  </tr>
  <?php } ?>
 </table>
<div id="AddNewLotForm">
<form action="" method="post"  id="AddNewVehicleType" >
<input type="hidden"  name="PID" value="" />
<input type="hidden"  name="action" value="AddNewVehicleType" />
<h3  class="center borderb"  >Insert New Vehicle Type</h3>
 
      <table   style="margin:10px auto;">
         <tr>
            <th  >Vehicle Type : &nbsp;</th>
            <td  ><input type="text" value="" name="typename"></td>
         </tr>
          </table>   
      
</form>
</div>
 
 <script>

 
  $(function() {
	  
	  // For The Edit
	  $('#parkingsystem_container  .edit').click(function(){
	
	iddata=$(this).attr('id');
	values=iddata.split('_');
	
	 $.post("",{InfoID:values[1],action:'ajaxe_'+ values[0]},function(result)
			{		  
				var Data = eval ("(" + result + ")");
				$( "#AddNewLotForm [name='action']" ).val('UpdateVehicleType');	
				$( "#AddNewLotForm [name='PID']" ).val(values[1]);		
				$( "#AddNewLotForm [name='typename']" ).val(Data[0].TypeName);
				$( "#AddNewLotForm h3" ).html( 'Update Vehicle Type' );
				$( "#AddNewLotForm" ).dialog({title:'Update Vehicle Type'});	
				$( "#AddNewLotForm" ).dialog('open');				
			  	});			
	});
	
		// For the Dell
	  $('#parkingsystem_container  .del').click(function(){
		iddata=$(this).attr('id');
		values=iddata.split('_');
		if(confirm('Are you sure you want to delete this record ?'))
		 $.post("",{InfoID:values[1],action:'ajaxe_'+ values[0]},function(result)
				{		  
					var Data = eval ("(" + result + ")");
					alert(Data.message);
					window.location=window.location;
					});
	});
	
	
	$( "#AddNewLotForm" ).dialog({
      autoOpen: false,
	  title:'Add New Vehicle Type',
      width: 450,
      modal: true,
      buttons: {
        "Submit": function() {
            $( "#AddNewVehicleType" ).submit() ;
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      
    });
 		
		$( "#InsertNewLot" ).button();
		
 		$( "#InsertNewLot" ).click(function(){
			$( "#AddNewLotForm [name='action']" ).val('AddNewVehicleType');
			$( "#AddNewLotForm [name='typename']" ).val('');
			$( "#AddNewLotForm [name='extinfo']" ).val('');
			$( "#AddNewLotForm h3" ).html( 'Add New Vehicle Type' );
			$( "#AddNewLotForm" ).dialog({title:'Add New Vehicle Type'});	
			$( "#AddNewLotForm" ).dialog('open');
			
			});
  });
 </script>
</div>
	<?php
	
	}// End VehicleType();	
 
 function ParkVehicle()
{ 
  $vehicles=getVehiclesAdmin($q);
	 
?>
<div id="parkingsystem_container" >
<div id="adminlogo"></div>
  <h2 class="admintitles center" >Vehicle Administration</h2>
    <?php echo getMessage(); ?>
  <div id="vehicles">
     <h2 class="center borderb" >Search Vehicles</h2>
   <div id="searchPanel" >
   <form action="" method="get" >
   <input type="hidden" name="page" value="<?php echo $_GET['page'] ?>" />
   <input type="hidden" name="action" value="search" />
   
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Licesence Plate</td>
    <td><input name="PlateNumber" type="text" value="<?php echo get('PlateNumber') ?>" /></td>
    <td>Year</td>
    <td><input name="Year" type="text" value="<?php echo get('Year') ?>" /></td>
  </tr>
  <tr>
    <td>Color</td>
    <td><input name="Color" type="text" value="<?php echo get('Color') ?>" /></td>
    <td>User</td>
    <td><input name="User" type="text" value="<?php echo get('User') ?>" /></td>
  </tr>
  <tr>
    <td>Maker</td>
    <td><select name="VehicleMaker" id="VehicleMaker">
    <option value="0"> Select Vehicle Make </option>
     <?php 
	$q='SELECT * FROM  `{prefix}vehiclemaker` ORDER BY `MakerName` ASC  ';
	echo makelists('','VehicleMakerID' ,'MakerName' , get('VehicleMaker') , $q  );
	?>
    </select></td>
    <td>Vehicle Type</td>
    <td> 
      <select name="VehicleType" id="VehicleType">
      <option value="0"> Select Vehicle Type </option>
      <?php 
	$q='SELECT * FROM  `{prefix}vehicletype` ORDER BY `TypeName` ASC ';
	echo makelists('','VehicleTypeID' ,'TypeName' , get('VehicleType') , $q  );
	?>
    </select></td>
  </tr>
  
  <tr>
    <td>State</td>
    <td><input name="state" type="text" value="<?php echo get('state') ?>" /></td>
    <td> </td>
    <td> </td>
  </tr>
  <tr>
     <td colspan="4" class="center"><input class="btn center" value=" Search " type="submit"  /></td>
   </tr>
</table>
    
    </form>
   </div>
    <table class="grids" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th>Plate</th>
    <th>Color</th>
    <th>Make</th>
    <th>Type</th>
    <th>State</th>
    <th>Year</th>
    <th>User</th>
    <th width="50">Options</th>
  </tr>
   <?php
   if(sizeof($vehicles['data'])){
 	 foreach($vehicles['data'] as $v){
   	 ?>
  <tr>
    <td><?php echo $v->PlateNumber; ?></td>
    <td><?php echo $v->Colour; ?></td>
    <td><?php echo $v->MakerName; ?></td>
    <td><?php echo $v->TypeName; ?></td>
    <td><?php echo $v->States; ?></td>
    <td><?php echo $v->Year; ?></td>
    <td><?php echo '<a href="'. get_edit_user_link( $v->ID ).'">'. esc_attr( $v->user_nicename ) .'</a>';  ?></td>
    <td width="60">
    <a href="javascript:void(0);" id="getVehicle_<?php echo $v->VehicleID ?>" class="edit"> </a>
     <a href="javascript:void(0);" id="DelVehicle_<?php echo $v->VehicleID ?>" class="del"></a>
     
     
     </td>
  </tr>
  
   <?php } ?>
    <tr> <td colspan="8" class="center" > &nbsp;<?php echo  $vehicles['pagination']; ?> </td></tr>
     <?php 
   }else{ ?>
   <tr> <td colspan="8" class="center" > &nbsp;<?php echo  $vehicles['pagination']; ?> </td></tr>
   <tr> <td colspan="8" class="center" > Record Not Found </td></tr>
   
    <?php }    ?>
 
</table>
   </div>
 </div>
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
                            <td>Status:  </td>
                            <td><select name="status"  id="status"    >
                            <option value="1"> Active </option>
                            <option value="0"> Deactive </option>
                            </select></td>
                          </tr> 
                           </table>
                           <input style="display:none" type="checkbox" checked="checked"  name="Terms" id="Terms" />
                       </form> 
                         </div>
<script>
  
  jQuery(function() {
	 
    // For The Del
	  jQuery('#parkingsystem_container  .btn').button();
	 jQuery( "#parkingsystem_container .del" ).click(function() {
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
	
	//For Edit
 	
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
	
	jQuery( "#parkingsystem_container .edit" ).click(function() {
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
  });
  
 </script>
 <?php
 }// End ParkVehicle();

?>