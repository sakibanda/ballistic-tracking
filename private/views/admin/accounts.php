<?php $this->menu();  ?>

<?php $this->loadTemplate('message_boxes'); ?>

<!-- Manage Account Dialog -->
<div style="display: none;" id="dialog_manage_account" title="Add Account">
    <form action="" method="post" class="full" id="add_user_form">
        <div class="row">
            <label>Username</label>
            <div>
                <input type="hidden" name="user_id" id="user_id" />
                <input type="text" name="user_name" id="user_name" />
            </div>
        </div>
        <div class="row">
            <label>Email</label>
            <div><input type="text" name="email" id="email" /></div>
        </div>
        <div class="row">
            <label>Password</label>
            <div><input type="password" id="pass" name="pass"></div>
        </div>
        <div class="row"><label>Confirm Password</label>
            <div><input type="password" id="pass_confirm" name="pass_confirm"></div>
        </div>
        <div class="row">
            <label>User Type</label>
            <div>
                <select name="privilege" id="privilege">
                    <option value="1">Normal</option>
                    <option value="10">Admin</option>
                </select>
            </div>
        </div>
        <input type="hidden" value="Create" name="user_submit" />
    </form>
    <div class="actions">
        <div class="left">
            <button class="grey cancel">Cancel</button>
        </div>
        <div id="load_addedituser" class="center"></div>
        <div class="right">
            <button class="submit" onclick="submitForm()">Save</button>
        </div>
    </div>
</div>
<!-- End Manage Account Dialog -->

<h1 class="grid_12"><span>Manage Accounts</span></h1>

<div class="grid_12">
	<div id="load_manageaccountlist"></div>
</div><!-- End of .grid_12 -->

<script type="text/javascript" src="/theme/js/scripts/accounts.js"></script>