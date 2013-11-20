<h1 class="grid_12"><span>Database Settings</span></h1>
<br/><br/><br/><br/>
<div class="grid_6 center_col">
    <form id="dbSettings_form" method="post" action="/dbconfig" class="box">
        <div class="header"><h2>Database Settings</h2></div>

        <div class="content">
            <!-- Login messages -->
            <div class="login-messages">
                <div class="message welcome">Enter the following information</div>
                <div class="message failure" style="display: <?php echo ($success) ? 'none' : 'block'; ?>;"><?php echo $message?></div>
            </div>

            <div class="form-box">
                <div class="row">
                    <label for="host_name" style="">
                        Host Name
                    </label>
                    <div style="">
                        <input tabindex="1" type="text" class="required noerror" name="host_name" id="host_name" value="" />
                    </div>
                </div>

                <div class="row">
                    <label for="db_name">
                        Database Name
                    </label>
                    <div>
                        <input tabindex=2 type="text" class="required noerror" name="db_name" id="db_name" />
                    </div>
                </div>
                <div class="row">
                    <label for="user_name" style="">
                        User
                    </label>
                    <div style="">
                        <input tabindex="3" type="text" class="required noerror" name="user_name" id="user_name" value="" />
                    </div>
                </div>

                <div class="row">
                    <label for="pw_user">
                        Password
                    </label>
                    <div>
                        <input tabindex=4 type="password" class="required noerror" name="pw_user" id="pw_user" />
                    </div>
                </div>
            </div><!-- End of .form-box -->
        </div>

        <div class="actions">
            <div class="right">
                <input tabindex=5 type="submit" value="Create file" name="createFile_btn" />
            </div>
        </div><!-- End of .actions -->
    </form>
</div>