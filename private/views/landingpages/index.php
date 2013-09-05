<script type="text/javascript">
	function submitForm()	{
		$('#add_lp_form').submit(); 
	}
</script>
	<!-- Landing Pages Dialog -->
	<div style="display: none;" id="dialog_landing_page" title="Add Landing Page">
		<form id="add_lp_form" method="post" action="/landingpages" class="full" style="border:0px;">
			<input id="landing_page_id" name="landing_page_id" type="hidden" /> 
			<div class="row">
				<label>LP Name</label>
				<div>
					<input type="text" id="name" name="name" style="width: 200px;"/>
				</div>
			</div>
			<div class="row">
				<label>Landing Page URL</label>
				<div>
					<input type="text" id="url" name="url" style="width: 200px; display: inline;" />
				</div>
			</div>
		</form>

		<div class="actions">
			<div class="left">
				<button class="grey cancel">Cancel</button>
			</div>
			<div class="right">
				<button class="submit" onclick="submitForm()">Save</button>
				
			</div>
		</div>	
	</div>
	<script type="text/javascript">
	/* <![CDATA[ */
	
	$(document).ready(function() {
		$( "#dialog_landing_page" ).dialog({
			autoOpen: false,
			modal: true,
			width: 400,
			open: function(){ $(this).parent().css('overflow', 'visible'); }
		}).find('button.submit').click(function(){
			var $el = $(this).parents('.ui-dialog-content');
			$el.find('form')[0].reset();
			$el.dialog('close');
		}).end().find('button.cancel').click(function(){
			var $el = $(this).parents('.ui-dialog-content');
			$el.find('form')[0].reset();
			$el.dialog('close');
		});

		$( ".open-landing-page-dialog" ).click(function() {
			$("#landing_page_id").val('');
			$("#name").val('');
			$("#url").val('');

			$('#dialog_landing_page').dialog('option', 'title', 'Add Landing Page');
			$( "#dialog_landing_page" ).dialog( "open" );
			return false;
		});
	});

	function editLandingPage(landing_page_id, name, url)	{
		$("#landing_page_id").val(landing_page_id);
		$("#name").val(name);
		$("#url").val(url);

		$('#dialog_landing_page').dialog('option', 'title', 'Edit Landing Page');
		$("#dialog_landing_page" ).dialog( "open" );
	}
	
	/* ]]> */
	</script>
	<!-- End Landing Pages Dialog -->

<h1 class="grid_12"><span>Landing Page Setup</span></h1>

<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_12">
	<div class="box with-table">
	
		<div class="header">
			<h2>My Landing Pages</h2>
		</div>
		<div class="tabletools">
			<div class="left">
				<a class="open-landing-page-dialog" href="javascript:void(0);"><i class="icon-plus"></i>Add</a>
			</div>
			<div class="right"></div>
		</div>
		
		<div class="content">
			<table class="dynamic styled with-prev-next" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>Landing Page</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php 

					foreach($lp_data as $landing_page_row) {
						$html['name'] = BTHtml::encode($landing_page_row->get('name'));
						$html['landing_page_id'] = BTHtml::encode($landing_page_row->get('landing_page_id'));
						$html['url'] = BTHtml::encode($landing_page_row->get('url'));
						?>
						<tr>
							<td><?php printf('%s',$html['name']); ?></td>
							<td class="center">
								<a onclick="editLandingPage('<?php printf('%s',$html['landing_page_id']); ?>','<?php printf('%s',$html['name']); ?>','<?php printf('%s',$html['url']); ?>')" class="button small grey tooltip" alt="Edit" title="Edit"><i class="icon-pencil"></i></a>
								<a href="/landingpages/delete?id=<?php printf('%s',$html['landing_page_id']); ?>" class="button small grey tooltip" alt="Delete" title="Delete" onclick="if (confirm('Are You Sure You Want To Delete This Landing Page?')) {return true;} else {return false;}"><i class="icon-remove"></i></a>
							</td>
						</tr>
						<?php	
					} ?>
				</tbody>
			</table>
		</div><!-- End of .content -->
	</div><!-- End of .box -->
</div><!-- End of .grid_12 -->