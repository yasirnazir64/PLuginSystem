<?php

function PermitSystem()// Display Admin Section
{
	
	$Lots= getPermits();
		
		?>

<div id="parkingsystem_container">
<div id="adminlogo"></div>
  <h2 class="admintitles center" >Permits Administration</h2>
  <?php echo getMessage();?>
  <table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr>
  <th>ID</th>
    <th>Permit No#</th>
    <th>Lot Name</th>
    <th>Lot Location</th>
    <th>Visible</th>
    <th>Archived</th>
    <th>Options</th>
  </tr>
  <?php
  
  if(sizeof($Lots['data']) > 0){  
   foreach($Lots['data'] as $v) {
	  ?>
  <tr>
  <th><?php echo $v->PermitId ?></th>
    <td><?php echo $v->PermitNo ?></td>
    <td><?php echo $v->LotName ?></td>
    <td><?php echo $v->Location ?></td>
    <td><?php echo $v->Visible?'Yes':'No' ?></td>
    <td><?php echo DateFormating($v->Archived ,'d M ,Y') ?></td>
    <td><a href="javascript:void(0);" id="getpermit_<?php echo $v->PermitId ?>" class="edit"> </a> 
     <a href="javascript:void(0);" id="Delpermit_<?php echo $v->PermitId ?>" class="del"> </a></td>
  </tr>
  <?php }?>
  <tr>
    <td colspan="7" align="center" valign="middle"><?php echo $Lots['pagination'] ?> &nbsp; </td>
  </tr>
  <?php } else {  ?>
  <tr>
    <td colspan="7" align="center" valign="middle"><?php echo $Lots['pagination'] ?> &nbsp; </td>
  </tr>
   <tr>
    <td colspan="7" align="center" valign="middle">Record Not Found </td>
  </tr>
   <?php }   ?>
  <tr>
    <td colspan="7" align="center" valign="middle">
    <input type="submit" name="InsertNewLot" id="InsertNewLot" value="Add New Permits">
    </td>
    </tr>
</table>
<div id="AddNewLotForm">
<form action="" method="post"  id="AddNewPermit" >
<input type="hidden"  name="PID" value="" />
<input type="hidden"  name="action" value="AddNewPermit" />
<h3 class="borderb center" >Create Permit</h3>
 
      <table class="ui-widget" style="margin:10px auto;">
         <tbody><tr>
            <th class="ui-widget-header ar">Visible: &nbsp;</th>
            <td class="ui-widget-content"><input type="checkbox" value="1" name="visible"></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Permit No.: &nbsp;</th>
            <td class="ui-widget-content"><input maxlength="10" size="10" value="" name="PermitNO"></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Lot: &nbsp;</th>
            <td class="ui-widget-content">
               <select name="LotNameID">
                  <option value="0"> Select Lot </option>
                  <?php
				  $q='SELECT * FROM  `{prefix}lot` ';
				  $data=getdata($q);
 				  echo makelists($data,'LotID' ,'LotName' );
 				  ?>
                </select>
            </td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Archived: &nbsp;</th>
            <td class="ui-widget-content"><input type="checkbox" value="1" name="archived">
               
            </td>
         </tr>
      </tbody></table>   
        <center>
         <b>This permit is linked to 0 payment records.</b>
 
      </center>
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
				
				//console.log(result);
				$( "#AddNewLotForm [name='action']" ).val('UpdatePermit');	
				$( "#AddNewLotForm [name='PID']" ).val(values[1]);		
				$( "#AddNewLotForm [name='PermitNO']" ).val(Data[0].PermitNo);
				$( "#AddNewLotForm [name='LotNameID']" ).val(Data[0].Lot);
				if(parseInt(Data[0].Visible)==1)
				$( "#AddNewLotForm [name='visible']" ).prop("checked",true);
				else
				$( "#AddNewLotForm [name='visible']" ).prop("checked",false);
				
				if( Data[0].Archived )
				$( "#AddNewLotForm [name='archived']" ).prop("checked",true);
				else
				$( "#AddNewLotForm [name='archived']" ).prop("checked",false);
				
				$( "#AddNewLotForm h3" ).html('Update Permit' );
				$( "#AddNewLotForm" ).dialog({title:'Update Permit'});
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
	 
	 ////////////////////////////////////////////////////////////////////////////////////
	  	 $( "#AddNewLotForm" ).dialog({
      autoOpen: false,
	  title:'Add New Permit',
      height: 330,
      width: 450,
      modal: true,
      buttons: {
        "Submit": function() {
            $( "#AddNewPermit" ).submit() ;
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      
    });
 		
		$( "#InsertNewLot" ).button();
		
 		$( "#InsertNewLot" ).click(function(){
			
			$( "#AddNewLotForm [name='action']" ).val('AddNewPermit');	
 			$( "#AddNewLotForm [name='PermitNO']" ).val('');
			$( "#AddNewLotForm [name='LotNameID']" ).val(0);
			$( "#AddNewLotForm [name='archived']" ).prop("checked",false);
			$( "#AddNewLotForm h3" ).html('Create Permit' );
			$( "#AddNewLotForm" ).dialog({title:'Add New Permit'});
			$( "#AddNewLotForm" ).dialog('open');
			
			});
  });
 
 </script>
</div>
	<?php
	
	}// End ParkLots();
 
?>