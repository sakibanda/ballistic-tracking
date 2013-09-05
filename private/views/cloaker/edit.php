<?php $this->menu();  ?>

<h1 class="grid_12"><span>General Settings For: <?php echo $cloaker->name; ?></span></h1>

<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_6">
	<form method="post" action="/cloaker/edit?id=<?php echo $_GET['id']; ?>" class="box">
		<div class="header"><h2>General Options</h2></div>
		
		<div class="content">
			
			<div class="row">
				<label class="tooltip" title="Name your redirect something descriptive.">Name:</label>
				<div>
					<input type="text" name="name" value="<?php echo $cloaker->name; ?>" />
				</div>
			</div>
			
			<div class="row">
				<label class="tooltip" title="The install URL for the API file. You can use the same API file for multiple redirects, but the slugs must be different.">API Install URL:</label>
				<div>
					<input type="text" name="url" value="<?php echo $cloaker->url; ?>" />
				</div>
			</div>
			
			<div class="row">
				<label class="tooltip" title="All cloaked visits will goto this URL.">Default Safe URL</label>
				<div>
					<input type="text" value="<?php echo $options['exclude_url']; ?>" name="exclude_url" /> <br />
				</div>
			</div>
		</div>
		
		<div class="actions">
			<div class="right">
				<input type="submit" value="Save" style="" />
			</div>
		</div>
	</form>
</div>

