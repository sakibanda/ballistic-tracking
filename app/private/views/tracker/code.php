<h1 class="grid_12"><span><?php echo ($campaign->id()) ? 'Edit' : 'Add A';?> Campaign</span></h1>

<form id="tracking_form" method="post">
	<input type="hidden" name="campaign_id" value="<?php echo $campaign->id(); ?>" />
	
	<div class="grid_12">
		<div class="box">
			<div class="header"><h2>Campaign Settings</h2></div>	
			
			<div class="content">
				
				<div class="row longLabel">
					<label class="tooltip" title="Give a descriptive name. This is used on the overview and manage campaign pages">Name</label>
					
					<div>
						<input type="text" name="name" value="<?php echo $campaign->name;?>" />
					</div>
				</div>
				
				<div class="row longLabel" <?php echo ($campaign->id()) ? 'style="display: none;"' : ''; ?>>
					<label>Type</label>
					
					<div>
						<div>
							<input type="radio" class="tracker_type" id="tracker_type_0" name="tracker_type" value="direct" onclick="type_checked(this.value);" <?php if($campaign->type == 2) echo 'checked="checked"'; ?>> <label for="tracker_type_0">Direct Link</label>
						</div>
						
						<div>
							<input type="radio" class="tracker_type" id="tracker_type_1" name="tracker_type" value="lp" onclick="type_checked(this.value);" <?php if($campaign->type == 1 || !$campaign->type) echo 'checked="checked"'; ?>> <label for="tracker_type_1">Landing Page</label>
						</div>
					</div>
				</div>
				
				<div class="row longLabel" <?php echo ($campaign->id()) ? 'style="display: none;"' : ''; ?>>
					<label>Traffic Source</label>
					<div>
						<?php
							BTForm::createSelect('traffic_source_id',$traffic_sources,null,'traffic_source_id','','name','traffic_source_id');
						?>
					</div>
				</div>
				
				<div class="row longLabel">
					<label class="tooltip" title="This is how users will be redirected to the offer.">Redirect Method</label>
					<div>
						<?php
						
						$redir_methods = array(
							array('label'=>"301 Redirect (Permanent)",'value'=>REDIRECT_TYPE_301),
							array('label'=>"302 Redirect (Found)",'value'=>REDIRECT_TYPE_302),
							array('label'=>"307 Redirect (Temporary)",'value'=>REDIRECT_TYPE_307),
							array('label'=>"Double Meta (Flush Referer)",'value'=>REDIRECT_TYPE_DOUBLE_META),
							array('label'=>"Javascript",'value'=>REDIRECT_TYPE_JS),
							array('label'=>"Javascript + Meta (Flush Referer)",'value'=>REDIRECT_TYPE_JSMETA)
						);
						
						BTForm::createSelect('opt[redirect_method]',$redir_methods,$campaign->option('redirect_method')->value,'','','label','value');
						
						?>
					</div>
				</div>
				
				<div class="row longLabel">
					<label class="tooltip" title="Select an advanced redirect. Please note: changing the redirect on existing campaigns WILL change the campaign URL">Advanced Redirect</label>
					<div>
						<?php
						BTForm::createSelect('cloaker_id',$redirects,$campaign->cloaker_id,'cloaker_id','','name','cloaker_id','None');
						?>
					</div>
				</div>
				
				<div class="row longLabel only_adv_redirect">
					<label class="tooltip" title="The URL slug used for advanced redirects">Slug</label>
					
					<div>
						<input type="text" name="slug" value="<?php echo $campaign->slug; ?>" />
					</div>
				</div>
				
				<div class="row longLabel only_adv_redirect">
					<label class="tooltip" title="If the redirect is paused, users will be forced to the default safe url.">Redirect Status</label>
					
					<div>
						<div>
							<input type="radio" name="opt[advanced_redirect_status]" id="paused_no" value="1"  <?php if($campaign->options['advanced_redirect_status']->value) echo 'checked="checked"'; ?>> <label for="paused_no">Running</label>
						</div>
						
						<div>
							<input type="radio" name="opt[advanced_redirect_status]" id="paused_yes" value="0" <?php if(!$campaign->options['advanced_redirect_status']->value) echo 'checked="checked"'; ?>> <label for="paused_yes">Paused</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="grid_12" id="offer_holder" class="offer_holder">
		<div class="box with-table">
			<div class="header">
				<h2>Offers</h2>
			</div>
			
			<div class="content">
				<div class="tabletools">
					<div class="left">
						<a href="#" onclick="add_offer(0,'direct'); return false;"><i class="icon-plus"></i> Add Offer</a>
						<a class="show_offer_btn" href="javascript:void(0);"><i class="icon-plus"></i> Use Existing Offer</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="grid_12" id="lpoffer_holder" class="offer_holder">
		<div class="box with-table">
			<div class="header">
				<h2>LP Offers</h2>
			</div>
			
			<div class="content">
				<div class="tabletools">
					<div class="left">
						<a href="#" onclick="add_offer(0,'lp'); return false;"><i class="icon-plus"></i> Add Offer</a>
                        <a class="show_offer_btn" href="javascript:void(0);"><i class="icon-plus"></i> Use Existing Offer</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="grid_12" id="lp_holder">
		<div class="box with-table">
			<div class="header">
				<h2>Landing Pages</h2>
			</div>
			
			<div class="content">
				<div class="tabletools">
					<div class="left">
						<a href="#" onclick="add_lp();return false;"><i class="icon-plus"></i> Add LP</a>
					</div>
				</div>
				
				<table class="dataTable" id="lp_table">
					<thead>
						<tr>
							<th style="width: 200px;">Name</th>
							<th>URL</th>
							<th style="width: 130px;">Weight</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<div class="grid_12">
		<div class="box with-table">
			<div class="header">
				<h2>Variables</h2>
			</div>
			<div class="content">
				<table class="dataTable" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th>Variables</th>
							<th>Name</th>
							<th>Value</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Keyword</td>
							<td><input type="text" value="<?php echo BTHtml::encode($campaign->option('var_kw')->value); ?>" name="opt[var_kw]" /></td>
							<td><input type="text" value="<?php echo BTHtml::encode($campaign->option('default_var_kw')->value); ?>" name="opt[default_var_kw]" /></td>
						</tr>
						<tr>
							<td>Subid1</td>
							<td><input type="text" value="<?php echo BTHtml::encode($campaign->option('var_v1')->value); ?>" name="opt[var_v1]" /></td>
							<td><input type="text" value="<?php echo BTHtml::encode($campaign->option('default_var_v1')->value); ?>" name="opt[default_var_v1]" /></td>
						</tr>
						<tr>
							<td>Subid2</td>
							<td><input type="text" value="<?php echo BTHtml::encode($campaign->option('var_v2')->value); ?>" name="opt[var_v2]" /></td>
							<td><input type="text" value="<?php echo BTHtml::encode($campaign->option('default_var_v2')->value); ?>" name="opt[default_var_v2]" /></td>
						</tr>
						<tr>
							<td>Subid3</td>
							<td><input type="text" value="<?php echo BTHtml::encode($campaign->option('var_v3')->value); ?>" name="opt[var_v3]" /></td>
							<td><input type="text" value="<?php echo BTHtml::encode($campaign->option('default_var_v3')->value); ?>" name="opt[default_var_v3]" /></td>
						</tr>
						<tr>
							<td>Subid4</td>
							<td><input type="text" value="<?php echo BTHtml::encode($campaign->option('var_v4')->value); ?>" name="opt[var_v4]" /></td>
							<td><input type="text" value="<?php echo BTHtml::encode($campaign->option('default_var_v4')->value); ?>" name="opt[default_var_v4]" /></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

    <div class="grid_12">
        <div class="box with-table">
            <div class="header">
                <h2>Variable Passthrough</h2>
            </div>
            <div class="content">
                <div class="tabletools">
                    <div class="left">
                        <a href="#" onclick="add_var_pass();return false;"><i class="icon-plus"></i> Add</a>
                    </div>
                </div>
                <table class="dataTable" id="vp_table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th style="width: 300px;"><label class="tooltip" title="Explain the reason of this variable.">Note</label></th>
                        <th style="width: 60px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach($campaign->options as $option) {
                            if(strpos($option->name,'pass_') === 0) {
                                $val = json_decode($option->value);
                                echo '<tr><td>';
                                echo '<div class="grid_8"><input type="text" name="variable_name[]" value="' . substr($option->name,5) . '" /></div>';
                                if($campaign->type == 1) {
                                    echo '<div class="grid_4 lp_var_pass_opts">';
                                    echo '<input type="hidden" name="variable_lp[]" value="' . (($val->lp) ? '1' : '0') . '"/>';
                                    echo '<input type="checkbox" class="var_check" '.(($val->lp) ? 'checked' : '').'/><span>LP</span>';
                                    echo '<input type="hidden" name="variable_offer[]" value="' . (($val->offer) ? '1' : '0') . '"/>';
                                    echo '<input type="checkbox" class="var_check" '.(($val->offer) ? 'checked' : '').'/><span>Offer</span>';
                                    echo '</div>';
                                }
                                echo '</td><td><input type="text" name="variable_note[]" value="'.$option->note.'" /></td>';
                                echo '<td><img src="/theme/img/icons/16x16/delete.png" onclick="deleteVarPassTable(this);" style="cursor: pointer; width: 16px; height: 16px;"></td></tr>';
                            }}
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
	
	<div class="grid_12">
		<div class="box">
			<div class="header">
				<h2>3rd Party Pixel</h2>
			</div>
			<div class="content">
				<div class="row">
					<label>Pixel Type</label>
					<div>
						<?php						
						BTForm::createSelect('opt[pixel_type]',CampaignModel::getPixelTypes(),$campaign->option('pixel_type')->value,'','','label','value');
						?>
					</div>
				</div>
				<div class="row">
					<label>Code</label>
					<div>
						<textarea name="opt[pixel_code]"><?php echo $campaign->option('pixel_code')->value; ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="grid_12">
		<div class="box">
			<div class="actions">
				<div class="right">
					<button onclick="return false;" id="add_adv_campaign" style="display: none;">Add Another Offer</button>
					<button id="generate_code" class="blue">Save</button>
					<img id="tracking_link_loading" style="display: none;" src="/theme/img/loader-small.gif"/>     
				</div>
			</div>
		</div>
	</div>

</form>

<a name="tracking_url" id="tracking_url"></a>

<div id="campaign_return">
	<?php if($campaign->id()) {
		$this->loadView('tracker/campaign_tracking_code');
		$this->loadView('pixel/code');
	} ?>
</div>

<div style="display: none;" id="showOffers" title="Use Existing Offer">
    <div class="row">
        <label for="affNetwork">
            <strong>Aff Network</strong>
        </label>
        <div>
            <?php
            BTForm::createSelect('affNetwork',AffNetworkModel::model()->getRows(),null,'affNetwork','','name','aff_network_id');
            ?>
        </div>
    </div>
    <div class="row">
        <label for="offerList">
            <strong>Select an Offer</strong>
        </label>
        <div>
            <select name="offerList" id="offerList"></select>
        </div>
    </div>
    <div class="actions">
        <div class="left">
            <button class="grey cancel">Cancel</button>
        </div>
        <div class="right">
            <button class="submit">Save</button>
        </div>
    </div>
</div>

<a name="tracking_bottom"></a>
<script src="/theme/js/scripts/campaigns.js"></script>
<script>
    $(function(){
		<?php if($campaign->id()) {
			if($campaign->type == 2) {
				foreach($campaign->offers as $offer) {
					printf("add_offer(%d,'direct');",$offer->id());
				}
			}else{
				foreach($campaign->offers as $offer) {
					printf("add_offer(%d,'lp');",$offer->id());
				}
				foreach($campaign->landing_pages as $lp) {
					printf("add_lp(%d);",$lp->id());
				}
			}
		}else{
			echo 'add_offer(0,"lp");';
			echo 'add_lp();';
			echo 'add_var_pass();';
			echo 'add_offer(0,"direct");';
		}?>
	});
</script>
</script>