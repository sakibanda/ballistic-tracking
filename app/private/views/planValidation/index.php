<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_6 center_col">
    <div><br><br><br></div>
    <form method="post" action="/planValidation" class="box">
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
                    <label for="domain_name" style="">
                        Domain
                    </label>
                    <div style="">
                        <input tabindex="1" type="text" class="required noerror" name="domain_name" id="domain_name" value="<?php echo $_SERVER['HTTP_HOST'];?>" />
                    </div>
                </div>

                <div class="row">
                    <label for="key">
                        Key
                    </label>
                    <div>
                        <input tabindex=2 type="text" class="required noerror" name="key" id="key" />
                    </div>
                </div>
            </div><!-- End of .form-box -->
        </div>

        <div class="actions">
            <div class="right">
                <input tabindex=3 type="submit" value="Save" name="saved_btn" />
            </div>
        </div><!-- End of .actions -->
    </form>
</div>