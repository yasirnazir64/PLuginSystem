<?php
 function AdminSideOptions($opt='')
	{
	
	$values=get_option( 'parkAdminSystemOption' );
	
 	$options=array( 'data'=>array(
					"parkadmin_heading"=>array('Label'=>'Front Panel Heading' ,'value'=>'Panel Main Heading' , 'type'=>'text') ,
					"paypal_account"=>array('Label'=>'Paypal Merchant Account' ,'value'=>'', 'type'=>'text') ,
					"paypal_key"=>array('Label'=>'Paypal Key' ,'value'=>'', 'type'=>'text') ,
					"paypal_custom_logo"=>array('Label'=>'Paypal Store Logo (<span class="small">Image Source Link<span>)' ,
												'value'=>'', 'type'=>'text' , 'ext' =>'<input type="file" name="paypal_custom_logo_ext" />') ,
					"successfull_payment"=>array('Label'=>'Successfull Payment Page' ,'value'=>'', 'type'=>'select') ,
					"error_payment"=>array('Label'=>'Cancel Payment Page' ,'value'=>'', 'type'=>'select') ,
					"LoginPage"=>array('Label'=>'User Log In Page' ,'value'=>'', 'type'=>'select') ,
					"taxammount"=>array('Label'=>'Tax Amount' ,'value'=>0, 'type'=>'text') ,
					"mail_notification"=>array('Label'=>'Mail Notification' ,'value'=>0, 'type'=>'select') ,
 					"max_permit_allowed"=>array('Label'=>'Maximam Permit Allowed' ,'value'=>1, 'type'=>'text') ,
 					"terms_ofservices"=>array('Label'=>'Terms Of Services' ,'value'=>'', 'type'=>'textarea') ,
					"renwl_reminder1"=>array('Label'=>'1st Renewal Reminder','cond'=> '(<span class="small">0=Disable<span>)' ,'value'=>0, 'type'=>'text') ,
					"renwl_reminder2"=>array('Label'=>'2nd Renewal Reminder','cond'=> '(<span class="small">0=Disable<span>)'  ,'value'=>0, 'type'=>'text') ,
					"renwl_reminder3"=>array('Label'=>'3rd Renewal Reminder','cond'=> '(<span class="small">0=Disable<span>)'  ,'value'=>0, 'type'=>'text') ,
					
  											),
					'key'=>'parkAdminSystemOption'
 											);
				 
	if(is_array($values) and sizeof($values) > 0)
		foreach($values as $k=>$v)
			{$options['data'][$k]['value'] = $v['value'] ;}
	if($opt!='')
		return $options['data'];
	else		
 		return $options;
	}

 function ParkAdminSystem()
	{
	$admin_options=AdminSideOptions();
	?>
  
  <div id="parkingsystem_container">
  <div id="adminlogo"></div>
  <div id="adminlogo"></div>
  <h2 class="admintitles center borderb paddingy" >ParkAdmin System Settings</h2>
    <?php echo getMessage(); ?>
 <div id="park_container">
  <p class="center red bold">Note:- Put the shortcode " <strong>[UserFrontPanel]</strong> " in the page contents where you need to display the user panel .</p>
 <form action="" method="post" name="ParkAdminOption" enctype="multipart/form-data" >
 <input type="hidden" name="action" value="SaveAdminData" />
 <table class="grids" width="80%" border="0" cellspacing="0" cellpadding="0">
  <?php foreach($admin_options['data'] as $k=>$v) { ?>
  <tr>
    <td valign="top" width="50%" ><?php echo $v['Label'] ?>:</td>
    <td width="50%" >
    
    <?php switch($v['type']){
		
		case "text": ?>
    	<input type="text" name="<?php echo $k; ?>" value="<?php echo $v['value'] ?>" /><?php echo isset($v['ext'])?$v['ext']:''; ?><?php echo isset($v['cond'])?$v['cond']:''; ?> 
    <?php break ;
 		case "select": ?>
       <select name="<?php echo $k; ?>"  >
      <?php if($k=='mail_notification') { ?>
       <option value="1" <?php echo ($v['value']==1)?'selected="selected"':''; ?> > Enable </option>
       <option value="0" <?php echo ($v['value']==0)?'selected="selected"':''; ?>  > Disable </option>
       <?php }
	   if($k=='LoginPage')
	   		echo '<option value="0"'.(($v['value']==0)?'selected="selected"':'').'  > Default </option>';
	   
	   if($k=='successfull_payment' or $k=='error_payment' or $k=='LoginPage' )
	   {
	   $Pages = array(
						'posts_per_page'   => 500,
						'orderby'          => 'post_title',
						'order'            => 'ASC',
						'post_type'        => 'page',
						'post_status'      => 'publish',
 											 );
 	
 	   echo makelists(get_posts($Pages),'ID','post_title' ,$v['value']);
	}?>
        
       </select><?php echo isset($v['ext'])?$v['ext']:''; ?><?php echo isset($v['cond'])?$v['cond']:''; ?> 
     <?php 
	 
	 break ;
 		case "textarea": ?>
        <textarea cols="45" rows="6" name="<?php echo $k; ?>" ><?php echo $v['value'] ?></textarea><?php echo isset($v['ext'])?$v['ext']:''; ?>
        <?php echo isset($v['cond'])?$v['cond']:''; ?> 
        
    <?php } ?>
    
    </td>
  </tr>
  <?php } ?>
  <tr>
     <td colspan="2" align="center"> <input class="btn" type="submit"  value=" Save " /></td>
  </tr>
</table>

  </form>
   
 </div>
 <script>
  
  $(function() {
	  
	  $('.btn').button();
 
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
			 $( "#AddNewLotForm" ).dialog('open');
			
			});
  });
  
 </script>
</div>
    
    <?php
	
	} 
	
function PaymentTransactions()
			{
				$data=array(
				 'UserID'=>get('User') ,
				 'PermitNo'=>get('PermitNo') ,
				 'TransactionID'=>get('TransactionID') ,
				 'PaypalTnxID'=>get('PaypalTnxID'),
 				 'FromDate'=>get('FromDate'),
				 'ToDate'=>get('ToDate')
				  );
				  
				$payments=PaymentsHistory($data);
 			?>
			<div id="parkingsystem_container" >
            <div id="adminlogo"></div>
			  <h2 class="admintitles center" >Registered Permits and Payments</h2>
			    <?php echo getMessage(); ?>
			  <div id="vehicles">
				 <h2 class="center borderb" >Search Payments</h2>
			   <div id="searchPanel" >
			   <form action="" method="get" >
			   <input type="hidden" name="page" value="<?php echo get('page'); ?>" />
			   <input type="hidden" name="action" value="search" />
			   
			   <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>Permit No.</td>
				<td><input name="PermitNo" type="text" value="<?php echo get('PermitNo') ?>" /></td>
				<td>User</td>
				<td><input name="User" type="text" value="<?php echo get('User') ?>" /></td>
			  </tr>
			  <tr>
				<td>Transaction ID</td>
				<td><input name="TransactionID" type="text" value="<?php echo get('TransactionID') ?>" /></td>
				<td>Paypal Transaction ID</td>
				<td><input name="PaypalTnxID" type="text" value="<?php echo get('PaypalTnxID') ?>" /></td>
			  </tr>
			  <tr>
				<td>From Date</td>
				<td><input name="FromDate" type="text" value="<?php echo get('FromDate') ?>" /></td>
				<td>To Date</td>
				<td> 
				 <input name="ToDate" type="text" value="<?php echo get('ToDate') ?>" /></td>
			  </tr>
			  <tr>
				 <td colspan="4" class="center"><input class="btn center" value=" Search " type="submit"  /></td>
			   </tr>
			</table>
 				</form>
			   </div>
				<table class="grids" width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<th>Trans ID</th>
				<th>Lot Name</th>
				<th>Permit</th>
				<th>Amount</th>
				<th>Payment Date</th>
				<th>Payment Status</th>
				<th>Payment Type</th>
				<th width="50">User</th>
			  </tr>
			   <?php
			   if(sizeof($payments['data'])){
				 foreach($payments['data'] as $v){
				 
  				 ?>
			  <tr>
                            <td><?php echo $v->PaymentID; ?></td>
                            <td><?php echo $v->LotName; ?></td>
                            <td><?php echo $v->PermitNo ?></td>
                            <td>$<?php echo num_format($v->PermitAmmount) ?></td>
                            <td><?php echo DateFormating($v->ProcessingDate , 'd-M-Y' ); ?></td>
                            <td><?php echo $v->Confirmation ;  ?></td>
                            <td><?php echo $v->DurationType ;  ?></td>
                            <td><?php echo '<a href="'. get_edit_user_link( $v->ID ).'">'. esc_attr( $v->user_nicename ) .'</a>';  ?></td>
                          </tr>
			  
			   <?php } ?>
               <tr> <td colspan="8" class="center" > &nbsp; <?php echo  $payments['pagination']; ?> </td></tr>
                <tr> <td colspan="6" class="center" >  </td>
                <td  class="center bold" >Total Amount </td>
                <td class="center bold" > &nbsp; $<?php echo  num_format($payments['total_ammount'][0]->total); ?> </td></tr>
               
               
				 <?php 
			   }else{ ?>
               <tr> <td colspan="8" class="center" > &nbsp; <?php echo  $payments['pagination']; ?> </td></tr>
			   <tr> <td colspan="8" class="center" > Record Not Found </td></tr>
			   
				<?php }    ?> 	
			
 			</table>
				
 			  </div>
 			</div>
			
			<script>
			  
			  $(function() {
				  
				  jQuery( "#parkingsystem_container [name='FromDate']" ).datepicker({ dateFormat: "yy-mm-dd" });
				  jQuery( "#parkingsystem_container [name='ToDate']" ).datepicker({ dateFormat: "yy-mm-dd" });
				 
				// For The Edit
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
  				 
			  });
			 
 			 </script>
			
			<?php
			
			}// End ParkVehicle();
  ?>