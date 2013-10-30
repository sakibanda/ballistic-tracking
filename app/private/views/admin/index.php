<?php $this->menu();  ?>

<h1 class="grid_12">
	<span>Admin Settings</span>
</h1>

<div class="grid_12">
    <form action="/admin/settings/save" method="post">
        <input type="hidden" name="id" value="<?php echo @$settings->Id(); ?>"/>
        <label for="keyId">Key Id</label>
        <input type="text" name="keyId" value="<?php echo @$settings->keyId; ?>"/>
        <label for="domain">Domain</label>
        <input type="text" name="domain" value="<?php echo @$settings->domain; ?>"/>
        <button>Save</button>
    </form>
</div>