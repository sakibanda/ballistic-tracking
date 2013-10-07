<div class="alert error" style="display: none;">
	<span class="icon"></span>
	<strong>Error:</strong>
	<span class="message"></span>
</div>

<div class="alert success" style="display: none;">
	<span class="icon"></span>
	<strong>Success:</strong>
	<span class="message"></span>
</div>

<h1 class="grid_12"><span>Manage Redirects</span></h1>

<div class="grid_12 minwidth">
	<div class="box with-table">
		<div class="content">
			<div class="tabletools">
				<div class="left">
					<a class="open-add-client-dialog" id="add_cloaker_btn" href="javascript:void(0);"><i class="icon-plus"></i>Add Redirect</a>
					<a class="open-add-client-dialog" id="download_cloaker_api" href="javascript:void(0);"><i class="icon-download-alt"></i>Download API File</a>
				</div>
				<div class="right">
				</div>
			</div>
			
			<table class="dataTable" id="cloakers_table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>
							Name
						</th>
						<th>
							API Install URL
						</th>
						<th>
							Actions
						</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$( "#add_cloaker_form_holder").dialog({
			autoOpen: false,
			modal: true,
			width: 400,
			resizable: false,
			open: function(){ $(this).parent().css('overflow', 'visible'); }
		}).find('button.cancel').click(function(){
			var $el = $(this).parents('.ui-dialog-content');
			$el.find('form')[0].reset();
			$el.dialog('close');
		}).end().find('button.submit').click(function() {
			$.post('/ajax/cloaker/post_cloaker_add',$('#add_cloaker_form').serialize(true),
				function(data) {
					if(data == 1) {
						$(".alert.success .message").text("Redirect added");
						$(".alert.success").show();
						$(".alert.error").hide();
					}
					else {
						$(".alert.error .message").text("An error occurred while adding your redirect url");
						$(".alert.error").show();
						$(".alert.success").hide();
					}
					
					refreshCloakerTable();
				}
			);
			
			var $el = $(this).parents('.ui-dialog-content');
			$el.find('form')[0].reset();
			$el.dialog('close');
		});
		
		$("#add_cloaker_form").submit(function(e) {
			e.preventDefault();
			$("#add_cloaker_form_holder button.submit").click();
			
		});
		
		$( "#add_cloaker_btn").click(function(e) {
			e.preventDefault();
			$("#add_cloaker_form_holder").dialog( "open" );
			return false;
		});
		
		$("#download_api_dialog").dialog({
			autoOpen: false,
			modal: true,
			width: 400,
			resizable: false,
			open: function(){ $(this).parent().css('overflow', 'visible');  }
		});
		
		$("#download_cloaker_api").click(function(e) {
			e.preventDefault();
			$("#download_api_dialog").dialog("open");
			return false;
		});
		
		$("#download_api_button").click(function() {
			window.location = "/ajax/cloaker/misc_download";
			var $el = $(this).parents('.ui-dialog-content');
			$el.dialog('close');
		});
				
		refreshCloakerTable();
	});
	
	function refreshCloakerTable() {
		$.get('/ajax/cloaker/view_cloaker_list',function(data) {			
			$("#cloakers_table tbody").html(data);
            /*
            $('#cloakers_table').table({
                "bPaginate": false,
                "aoColumns": [
                    null,
                    null,
                    {"bSortable": false }
                ]
            });
			*/
			$(".delete_cloaker").click(function(e) {
				e.preventDefault();
				
				var id = $(this).prop('rel');
				var row = $(this).parents('tr');
								
				if(confirm("Are you sure you want to delete this cloaker?")) {
					$.post('/ajax/cloaker/post_cloaker_delete',{id:id},function() {
                        $(".alert.success .message").text("Offer Deleted");
                        $(".alert.success").show();
                        $(".alert.error").hide();
						refreshCloakerTable();
					});
				}
			});
			
			$(".copy_cloaker").click(function(e) {
				e.preventDefault();
				
				var id = $(this).prop('rel');
				var row = $(this).parents('tr');
								
				if(confirm("Are you sure you want to duplicate this cloaker?")) {
					$.post('/ajax/cloaker/post_cloaker_duplicate',{id:id},function() {
						refreshCloakerTable();
					});
				}
			});
		});
	}
</script>

<div style="display: none;" id="add_cloaker_form_holder" title="Add A Redirect">
	<form id="add_cloaker_form" action="" class="full validate">
		<div class="row">
			<label for="d1_textfield">
				<strong>Redirect Name</strong>
			</label>
			<div>
				<input class="required" type="text" name="name" id="name" />
			</div>
		</div>
		<div class="row">
			<label for="d1_textfield">
				<strong>API Install URL</strong>
			</label>
			<div>
				<input class="required" type="text" name="url" id="url" placeholder="http://" />
			</div>
		</div>
	</form>
	<div class="actions">
		<div class="left">
			<button class="grey cancel">Cancel</button>
		</div>
		<div class="right">
			<button class="submit">Submit</button>
		</div>
	</div>
</div>

<div style="display: none;" id="download_api_dialog" title="Download API File">
	<p>To add an advanced redirect you must upload this file to your website. Enter the URL to
        the uploaded file when you create a new redirect.</p>
	<div style="text-align: center;">
		<button class="submit" id="download_api_button">Download</button>
	</div>
</div>