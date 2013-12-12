<h1 class="grid_12"><span>Ballistic Tracking Software Configuration</span></h1>
<div class="grid_12">
    <div class="grid_6">
        <form id="dbSettings_form" method="post" action="/config" class="box">
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
        <p><b>Note: </b>Please be sure there is not a database with same name.<br/>
        The installation process will delete it if there is one.</p>
    </div>
    <div class="grid_6">
        <div id="checker">
            <h3>Ballistic Tracker Requirement Checker</h3>
            <table id="table_checker">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Results</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PHP Version</td>
                        <?php if(version_compare(PHP_VERSION,"5.0.0") === 1){
                            echo "<td class='passed'>".PHP_VERSION." Passed</td>";
                        }else{
                            echo "<td class='failed'>Failed. PHP5 is required but you have ".PHP_VERSION."</td>";
                        }?>
                    </tr>
                    <tr>
                        <td>PHP Module Rewrite</td>
                        <?php if(in_array('mod_rewrite', apache_get_modules())){
                            echo "<td class='passed'>Passed</td>";
                        }else{
                            echo "<td class='failed'>Failed</td>";
                        }?>
                    </tr>
                    <tr>
                        <td>Zip Archive</td>
                        <?php if(class_exists("ZipArchive",false)){
                            echo "<td class='passed'>Passed</td>";
                        }else{
                            echo "<td class='failed'>Failed</td>";
                        }?>
                    </tr>
                    <tr>
                        <td>PDO</td>
                        <?php if(class_exists("PDO")){
                            echo "<td class='passed'>Passed</td>";
                        }else{
                            echo "<td class='failed'>Failed</td>";
                        }?>
                    </tr>
                    <tr>
                        <td>MySQLi</td>
                        <?php if(class_exists("MySQLi")){ // SQL Driver for PHP: function_exists("sqlsrv_connect")
                            echo "<td class='passed'>Passed</td>";
                        }else{
                            echo "<td class='failed'>Failed</td>";
                        }?>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php if($details){ ?>
            <div id="install_details">
                <h3>Installation Details</h3>
                <?=$details;?>
                <p>If everything looks OK!, please active the plan <a href="/plan">here</a></p>
            </div>
        <?php } ?>
    </div>
</div>