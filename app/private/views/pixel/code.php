<script type="text/javascript">	
	function pixel_data_changed() {
		var has_ssl = <?php echo (HAS_SSL) ? 1 : 0; ?>
		
		if(has_ssl) {
			var proto = 'https';
		}
		else {
			var proto = 'http';
		}
		
		var universal_pixel_code = '<iframe height="1" width="1" border="0" style="display: none;" frameborder="0" scrolling="no" src="' + proto + '://' + '<?php echo getTrackingDomain() ?>' + '/pixel/iframe?amount={1}"></iframe>';
		var postback_code = proto + '://<?php echo getTrackingDomain(); ?>/pixel/postback?amount={1}&clickid='		

		var pixelTypeValue = $("#pixel_form input[name=pixel_type]:checked").val(); 
		
		var amount_value = $('#amount_value').val();
		var offer_id_value = '';
		if($('#offer_id')) {
			offer_id_value = $('#offer_id').val();
		}
		
		if(pixelTypeValue == 0) {
			$('#unsecure_universal_pixel').val(universal_pixel_code.replace('{1}',amount_value));
			$("#pixel_type_universal_id").show();
			$("#pixel_type_postback").hide();
		}
		else {
			$('#pixel_type_postback_field').val(postback_code.replace('{1}',amount_value));
			$("#pixel_type_universal_id").hide();
			$("#pixel_type_postback").show();
		}
	}
	
	$(document).ready(function() {
		pixel_data_changed();
	});

    function set_duplicate(){
        if(document.pixel_form.allow_duplicate.checked){
            document.getElementById('allow_duplicate').setAttribute("value","1");
        }else{
            document.getElementById('allow_duplicate').setAttribute("value","0");
        }

        $.post('/ajax/tracker/code/updatedeDuplicate', $('#pixel_form').serialize(true), function(data) {
            $('#m-content').html(data);
            loadContent(page);
        });
    }
</script>
<?php
$page = '/ajax/trackercode/index';
$html['page'] = BTHtml::encode($page);
?>

<h1 class="grid_12"><span>Pixel or Post Back URL</span></h1>

<div class="grid_12">
	<form name="pixel_form" id="pixel_form" class="box" method="post">
	
		<div class="header">
			<h2>Get Your Pixel or Post Back URL</h2>
		</div>
		
		<div class="content">
		
			<div class="row">
				<label>Type:</label>
				<div>
                    <table>
                        <tr>
                            <td>
                                <div class="sub_row"><input type="radio" name="pixel_type" onchange="pixel_data_changed()" value="0" checked="checked" id="pixel_type_0" /> <label for="pixel_type_0">iFrame</label></div>
                                <div class="sub_row"><input type="radio" name="pixel_type" onchange="pixel_data_changed()" value="1" id="pixel_type_1" /> <label for="pixel_type_1">Postback</label></div>
                            </td>
                            <td>
                                <input style="margin-top: 10px;" id="allow_duplicate" name="allow_duplicate" type="checkbox" class="var_check" <?php if($campaign->allow_duplicate_conversion) echo 'checked="checked"'; ?> value ="" onClick="set_duplicate()" /><span>Allow Duplicate Conversions</span>
                                <input type="hidden" name="campaign_id" value="<?php echo $campaign->id(); ?>" />
                            </td>
                        </tr>
                    </table>
				</div>				
			</div>
			
			<div class="row">
				<label>Override Payout:</label>
				
				<div><input type="text" value="" onkeyup="pixel_data_changed()" id="amount_value" /> </div>
			</div>
		
		</div>
	</form>
</div>

<p>&nbsp;</p>
<div class="grid_12">
	<form class="box">
	
		<div class="header">
			<h2>Your Pixel or Post Back URL</h2>
		</div>
	
		<div class="content">
			<div class="row">
				<label><?php	for($i=0;$i<30;$i++)	{	echo '&nbsp;';	}	?></label>
				<div>
					<div class="sub_row" id="pixel_type_universal_id">
						<h2>Universal Pixel</h2>

						<textarea class="code_snippet" id="unsecure_universal_pixel" style="width: 100%; height: 50px;"></textarea><br/>
					</div>

					<div class="sub_row" id="pixel_type_postback" style="display:none;">
						<h2>Postback URL</h2>

						<textarea class="code_snippet" id="pixel_type_postback_field" style="width: 100%; height: 50px;"></textarea><br/>
					</div>
				</div>
			</div>
		</div>
	</form>
    <div id="m-content"></div>
</div>