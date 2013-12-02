<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_6 center_col">
    <form method="post" action="/plan" class="box">
        <div class="header">
            <h2>Plan Validation</h2>
        </div>

        <div class="content">
            <!-- Login messages -->
            <div class="login-messages">
                <div class="message welcome">Enter plan data acquired</div>
            </div>

            <div class="form-box">
                <div class="row">
                    <label for="domain_name">Domain</label>
                    <div>
                        <input type="text" class="required noerror" name="domain_name" id="domain_name" value="<?php echo $_SERVER['HTTP_HOST'];?>" />
                    </div>
                </div>

                <div class="row">
                    <label for="api_key">Api Key</label>
                    <div>
                        <input type="text" class="required noerror" name="api_key" id="api_key" />
                    </div>
                </div>

                <div class="row">
                    <label for="email">E-mail User Admin</label>
                    <div>
                        <input type="text" class="required noerror" name="email" id="email" style="margin-bottom: 0;" />
                        <span style="font-style: italic;font-size: 10px;color: #444444;">It might be same email of ballistictracking.com</span>
                    </div>
                </div>
            </div><!-- End of .form-box -->
        </div>

        <div class="actions">
            <div class="right">
                <input type="submit" value="Save" name="saved_btn" />
            </div>
        </div><!-- End of .actions -->
    </form>
</div>