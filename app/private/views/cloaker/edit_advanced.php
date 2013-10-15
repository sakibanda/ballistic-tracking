<?php $this->menu();  ?>

<h1 class="grid_12"><span>Advanced Options For: <?php echo $cloaker->name; ?></span></h1>

<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_12">
	<form method="post" action="/cloaker/edit/advanced?id=<?php echo $_GET['id']; ?>">
		<div class="box with-table">

			<!-- IPS -->
			<div class="header"><h2>IP Addresses</h2></div>

			<div class="content">	
				<div class="tabletools">
					<div class="left">
						<a class="open-add-client-dialog" id="add_ip_range" onclick="add_ip_range();" href="javascript:void(0);"><i class="icon-plus"></i>Add Range</a>
					</div>
					<div class="right">
					</div>
				</div>

				<table class="dataTable" id="exclude_ip_holder" cellpadding="0" cellspacing="0">
					<tr class="no_row_borders">
						<th class="tooltip" title="The starting address for the IP range.">From</th>
						<th></th>
						<th class="tooltip" title="The ending address for the IP range. Leave blank if you are adding just a single IP.">To</th>
						<th></th>
						<th class="tooltip" title="URL to send the cloaked visitors to.">URL</th>
						<th class="tooltip" title="A memo. That's all.">Memo</th>
						<th></th>
					</tr>
					
					<?php						
						if(!$cloaker->ips) {
							$_GET['ip_from'] = '';
							$_GET['ip_to'] = '';
							$_GET['url'] = '';
							$_GET['memo'] = '';
							$this->loadView("cloaker/view_iprange");
						}
						else {
							foreach($cloaker->ips as $ip) {
								$_GET['ip_from'] = $ip->ip_from;
								$_GET['ip_to'] = $ip->ip_to;
								$_GET['url'] = $ip->url;
								$_GET['memo'] = $ip->memo;
								$_GET['regex'] = $ip->regex;
								$this->loadView("cloaker/view_iprange");
							}
						}
					?>
				</table>
			</div>
			<!-- END IPS-->
		</div>
		
		<br /><br />
		<div class="box with-table">
			<!-- REFERERS -->
			<div class="header "><h2>Referers</h2></div>

			<div class="content">	
				<div class="tabletools">
					<div class="left">
						<a class="open-add-client-dialog" id="add_referer" onclick="add_referer();" href="javascript:void(0);"><i class="icon-plus"></i>Add Referer</a>
					</div>
					<div class="right">
					</div>
				</div>

				<table class="dataTable" id="exclude_referer_holder" cellpadding="0" cellspacing="0">
					<tr class="no_row_borders">
						<th class="tooltip" title="You can either use an asterisk (*) as a simple wildcard, or check this box to use regular expressions">Regex</th>
						<th class="tooltip" title="The Referer to cloak. Use * as a wildcard">Referer</th>
						<th></th>
						<th class="tooltip" title="URL to send the cloaked visitors to.">URL</th>
						<th class="tooltip" title="A memo. That's all.">Memo</th>
						<th></th>
					</tr>
					
					<?php						
						if(!$cloaker->referers) {
							$_GET['referer'] = '';
							$_GET['url'] = '';
							$_GET['memo'] = '';
							$_GET['regex'] = 0;
							$this->loadView("cloaker/view_referer");
						}
						else {
							foreach($cloaker->referers as $referer) {
								$_GET['referer'] = $referer->referer;
								$_GET['url'] = $referer->url;
								$_GET['memo'] = $referer->memo;
								$_GET['regex'] = $referer->regex;
								$this->loadView("cloaker/view_referer");
							}
						}
					?>
				</table>
			</div>
			<!-- END REFERERS-->
		</div>
		
		<br /><br />
		<div class="box with-table">
			<!-- HOSTNAMES -->
			<div class="header "><h2>Hostnames</h2></div>

			<div class="content">	
				<div class="tabletools">
					<div class="left">
						<a class="open-add-client-dialog" id="add_hostname" onclick="add_hostname();" href="javascript:void(0);"><i class="icon-plus"></i>Add Hostname</a>
					</div>
					<div class="right">
					</div>
				</div>

				<table class="dataTable" id="exclude_hostname_holder" cellpadding="0" cellspacing="0">
					<tr class="no_row_borders">
						<th class="tooltip" title="You can either use an asterisk (*) as a simple wildcard, or check this box to use regular expressions">Regex</th>
						<th class="tooltip" title="The resolved hostname to cloak. Use * for wildcard.">Hostname</th>
						<th></th>
						<th class="tooltip" title="URL to send the cloaked visitors to.">URL</th>
						<th class="tooltip" title="A memo. That's all.">Memo</th>
						<th></th>
					</tr>
					
					<?php						
						if(!$cloaker->hostnames) {
							$_GET['hostname'] = '';
							$_GET['url'] = '';
							$_GET['memo'] = '';
							$_GET['regex'] = 0;
							$this->loadView("cloaker/view_hostname");
						}
						else {
							foreach($cloaker->hostnames as $hostname) {
								$_GET['hostname'] = $hostname->hostname;
								$_GET['url'] = $hostname->url;
								$_GET['memo'] = $hostname->memo;
								$_GET['regex'] = $hostname->regex;
								$this->loadView("cloaker/view_hostname");
							}
						}
					?>
				</table>
			</div>
			<!-- END HOSTNAMES-->
		</div>
		
		<br /><br />
		<div class="box with-table">
			<!-- USER AGENTS -->
			<div class="header"><h2>User Agents</h2></div>

			<div class="content">	
				<div class="tabletools">
					<div class="left">
						<a class="open-add-client-dialog" id="add_user_agent" onclick="add_user_agent();" href="javascript:void(0);"><i class="icon-plus"></i>Add User Agent</a>
					</div>
					<div class="right">
					</div>
				</div>

				<table class="dataTable" id="exclude_user_agent_holder" cellpadding="0" cellspacing="0">
					<tr class="no_row_borders">
						<th class="tooltip" title="You can either use an asterisk (*) as a simple wildcard, or check this box to use regular expressions">Regex</th>
						<th class="tooltip" title="The user agent to cloak. Use * as wildcard">User Agent</th>
						<th></th>
						<th class="tooltip" title="URL to send the cloaked visitors to.">URL</th>
						<th class="tooltip" title="A memo. That's all.">Memo</th>
						<th></th>
					</tr>
					
					<?php
						if(!$cloaker->user_agents) {
							$_GET['user_agent'] = '';
							$_GET['url'] = '';
							$_GET['memo'] = '';
							$_GET['regex'] = 0;
							$this->loadView('cloaker/view_useragent');
						}
						else {
							foreach($cloaker->user_agents as $ua) {
								$_GET['user_agent'] = $ua->user_agent;
								$_GET['url'] = $ua->url;
								$_GET['memo'] = $ua->memo;
								$_GET['regex'] = $ua->regex;
								$this->loadView("cloaker/view_useragent");
							}
						}
					?>
				</table>
			</div>
			<!-- END USER AGENTS-->
		</div>
		
		<br /><br />
		<div class="box with-table">
			<div class="actions">
				<div class="right">
					<input type="submit" value="Save" name="save" style="" />
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">

function add_ip_range() {
	$.get("/cloaker/viewiprange",function(data) {
		$("#exclude_ip_holder").append(data);
	});
}

function add_user_agent() {	
	$.get("/cloaker/viewuseragent", function(data) {
		$("#exclude_user_agent_holder").append(data);
	});
}

function add_hostname() {	
	$.get("/cloaker/viewhostname", function(data) {
		$("#exclude_hostname_holder").append(data);
	});
}

function add_referer() {	
	$.get("/cloaker/viewreferer", function(data) {
		$("#exclude_referer_holder").append(data);
	});
}

function deleteDataRow(obj) {
	var holder = $(obj).parents('tr');
	holder.remove();
}

$(document).ready(function() {
	$(".regex_checkbox").click(function() {		
		var hidden = $(this).siblings("input[type=hidden]");
		
		if($(this).is(':checked')) {
			hidden.val('1');
		}
		else {
			hidden.val('0');
		}
	});
});
</script>