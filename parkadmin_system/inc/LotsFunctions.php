<?php

function ParkLots()// Display Admin Section
{ 	$Lots=getparkinglots(); 	?>

<div id="parkingsystem_container">
<div id="adminlogo"></div>
  <h2 class="admintitles center" >Lot Administration</h2>
   <?php echo getMessage(); ?>
  <table width="100%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <th>Lot Name</th>
    <th>Short Name</th>
    <th>Yearly Cost</th>
    <th>Monthly Cost</th>
    <th>DailyCost</th>
    <th>Visible</th>
    <th>Archived</th>
    <td>Options</td>
  </tr>
  <?php
  if(sizeof($Lots['data']) > 0 ){
   foreach($Lots['data'] as $v) {?>
  <tr>
    <td><?php echo $v->LotName ?></td>
    <td><?php echo $v->LotShortName ?></td>
    <td><?php echo $v->YearlyCost ?></td>
    <td><?php echo $v->MonthlyCost ?></td>
    <td><?php echo $v->DailyCost ?></td>
    <td><?php echo $v->Visible?'Yes':'No' ?></td>
    <td><?php echo DateFormating($v->Archived ,'d M ,Y') ?></td>
    <td><a href="javascript:void(0);" id="getLot_<?php echo $v->LotID ?>" class="edit"> </a> 
     <a href="javascript:void(0);" id="DelLot_<?php echo $v->LotID ?>" class="del"> </a></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="8" align="center" valign="middle"> &nbsp; <?php echo $Lots['pagination'] ?> </td>
  </tr>
   <?php }else { ?>
   <tr>
    <td colspan="8" align="center" valign="middle"> &nbsp; <?php echo $Lots['pagination'] ?> </td>
  </tr>
   <tr>
    <td colspan="8" align="center" valign="middle">Record Not Found  </td>
  </tr>
   
   
   <?php } ?>
  <tr>
    <td colspan="8" align="center" valign="middle">
    <input type="submit" name="InsertNewLot" id="InsertNewLot" value="Add New Lot">
    </td>
    </tr>
</table>


<div id="AddNewLotForm">
<form action="" method="post"  id="AddNewLotRecord" >
<input type="hidden"  name="action" value="AddLots" />
<input type="hidden" name="PID" value="" />
<h3>Enter Information Of New Lot</h3>
<table   style="margin:10px auto;">
         <tbody><tr>
            <th class="ui-widget-header ar">Visible: &nbsp;</th>
            <td class="ui-widget-content"><input type="checkbox" value="1" name="visible"></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Lot Name: &nbsp;</th>
            <td class="ui-widget-content"><input maxlength="100"   value="" name="LotName"></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Lot Short Name: &nbsp;</th>
            <td class="ui-widget-content"><input maxlength="25" size="15" value="" name="ShortName"></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Location: &nbsp;</th>
            <td class="ui-widget-content"><textarea name="location" cols="25" rows="3"></textarea></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Deposit: &nbsp;</th>
            <td class="ui-widget-content"><input maxlength="10" size="10" value="" name="depositAmount">
            <small>0.00 = option not available</small></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Yearly Cost: &nbsp;</th>
            <td class="ui-widget-content"><input maxlength="10" size="10" value="" name="yearlyCost">
            <small>0.00 = option not available</small></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Quarterly Cost: &nbsp;</th>
            <td class="ui-widget-content"><input maxlength="10" size="10" value="" name="quarterlyCost">
            <small>0.00 = option not available</small></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Monthly Cost: &nbsp;</th>
            <td class="ui-widget-content"><input maxlength="15" size="10" value="" name="monthlyCost">
            <small>0.00 = option not available</small></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Daily Cost: &nbsp;</th>
            <td class="ui-widget-content"><input maxlength="15" size="10" value="" name="dailyCost">
            <small>0.00 = option not available</small></td>
         </tr>
         <tr>
            <th class="ui-widget-header ar">Archived: &nbsp;</th>
            <td class="ui-widget-content"><input type="checkbox" value="1" name="archived">
               
            </td>
         </tr>
      </tbody></table>


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
				 
								
				$( "#AddNewLotForm [name='action']" ).val('UpdateLot');	
				$( "#AddNewLotForm [name='PID']" ).val(values[1]);		
				$( "#AddNewLotForm [name='LotName']" ).val(Data[0].LotName);
				$( "#AddNewLotForm [name='ShortName']" ).val(Data[0].LotShortName);
				$( "#AddNewLotForm [name='location']" ).val(Data[0].Location);		
				$( "#AddNewLotForm [name='depositAmount']" ).val(Data[0].Deposit);
				$( "#AddNewLotForm [name='yearlyCost']" ).val(Data[0].YearlyCost);
				$( "#AddNewLotForm [name='quarterlyCost']" ).val(Data[0].QuarterlyCost);
				$( "#AddNewLotForm [name='monthlyCost']" ).val(Data[0].MonthlyCost);		
				$( "#AddNewLotForm [name='dailyCost']" ).val(Data[0].DailyCost);				
 				
				
				
				if(parseInt(Data[0].Visible)==1)
				$( "#AddNewLotForm [name='visible']" ).prop("checked",true);
				else
				$( "#AddNewLotForm [name='visible']" ).prop("checked",false);
				
				if( Data[0].Archived )
				$( "#AddNewLotForm [name='archived']" ).prop("checked",true);
				else
				$( "#AddNewLotForm [name='archived']" ).prop("checked",false);
				$( "#AddNewLotForm h3" ).hide();
				$( "#AddNewLotForm" ).dialog({title:'Update Lot'});
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
	
	 
	 
	 
	 
	 ////////////////////////////////////////////////////////////////////////////////////////////////////
	 
	  	 $( "#AddNewLotForm" ).dialog({
      autoOpen: false,
	  title:'Add New Lot',
      height: 530,
      width: 450,
      modal: true,
      buttons: {
        "Submit": function() {
            $( "#AddNewLotRecord" ).submit() ;
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      
    });
 		
		$( "#InsertNewLot" ).button();
		
 		$( "#InsertNewLot" ).click(function(){
			
			
			$( "#AddNewLotForm [type='text']" ).val('');
			$( "#AddNewLotForm [name='archived']" ).prop("checked",false);				
			$( "#AddNewLotForm [name='action']" ).val('AddLots');
			$( "#AddNewLotForm h3" ).show();	
			$( "#AddNewLotForm" ).dialog({title:'Add New Lot'});
			$( "#AddNewLotForm" ).dialog('open');
			
			});
  });
 
 </script>
</div>
	<?php
	
	}// End ParkLots();
  	
?>