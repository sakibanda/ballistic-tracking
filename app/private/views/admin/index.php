<?php $this->menu();  ?>

<h1 class="grid_12">
	<span>Admin Settings</span>
</h1>

<div class="grid_6" id="settings">
    <form action="/admin/settings/save" method="post">
        <input type="hidden" name="id" value="<?php echo @$settings->Id(); ?>"/>
        <div class="box">
            <div class="header">
                <h2>Register a Key</h2>
            </div>
            <div class="content">
                <div class="row">
                    <label for="keyId">Api KEY</label>
                    <div>
                        <input type="text" name="api_key" value="<?php echo @$settings->api_key; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <label for="domain">Domain</label>
                    <div>
                        <input type="text" name="domain" value="<?php echo @$settings->domain; ?>"/>
                    </div>
                </div>
            </div>
            <div class="actions">
                <div class="right">
                    <button>Save</button>
                </div>
            </div>
        </div>
    </form>
</div>