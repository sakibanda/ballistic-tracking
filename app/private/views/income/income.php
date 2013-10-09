<h1 class="grid_12"><span>Update Income</span></h1>

<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_12">
    <div class="grid_6">
        <p>Here is where you can update your Revenue Data.</p>
    </div>
</div>

<div class="grid_6">
    <div class="grid_12">
        <form id="updateincomeform" action="" method="post" class="box">
            <div class="header">
                <h2>Add an Income</h2>
            </div>
            <div class="content">
                <div class="row">
                    <label for="">Date</label>
                    <div>
                        <input class="date" type="text" name="date" id="date" value="<?php echo date('m/d/Y',time() - 60*60*24); ?>" tabindex="20"/>
                    </div>
                </div>
                <div class="row">
                    <label for="">Campaign</label>
                    <div>
                        <select name="campaign_id" id="campaign_id">
                            <?php
                            foreach($campaigns as $row) {
                                printf('<option value="%s">%d - %s</option>', $row->id(),$row->id(),$row->name);
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label for="">Amount</label>
                    <div>
                        <input type="text" id="amount" name="amount" value="" style="display: inline;" />
                    </div>
                </div>
            </div>
            <div class="actions">
                <div class="left">
                    <button class="grey cancel">Cancel</button>
                </div>
                <div class="right">
                    <input type="submit" value="Add" />
                </div>
            </div>
        </form>
    </div>
</div>

<div class="grid_6">
    <div class="grid_12">
        <div class="box with-table">
            <div class="header">
                <h2>Incomes</h2>
            </div>
            <div class="content">
                <table id="income_table" class="dataTable" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Campaign</th>
                        <th>Income</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/theme/js/scripts/income.js"></script>