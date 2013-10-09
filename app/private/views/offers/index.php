<?php $this->loadTemplate('message_boxes');  ?>

<h1 class="grid_12"><span>My Offers</span></h1>

<div class="grid_12 minwidth">
	<div class="box with-table">
		<div class="content">
			<div class="tabletools">
				<div class="left">
					<a class="open-add-client-dialog" id="add_offer_btn" href="javascript:void(0);"><i class="icon-plus"></i>Add Offer</a>
				</div>
				<div class="right">
				</div>
			</div>
            <table cellpadding="0" cellspacing="0" id="offers_table" class="dataTable"></table>
		</div>
	</div>
</div>

<div style="display: none;" id="add_offer_form_holder" title="Add an Offer">
    <form id="add_offer_form" action="" class="full">
        <input type="hidden" name="offer_id" id="offer_id"/>
        <div class="row">
            <label for="d1_textfield">
                <strong>Network</strong>
            </label>
            <div>
                <?php
                BTForm::createSelect('aff_network_id',AffNetworkModel::model()->getRows(),null,'aff_network_id','','name','aff_network_id');
                ?>
            </div>
        </div>
        <div class="row">
            <label for="d1_textfield">
                <strong>Offer Name</strong>
            </label>
            <div>
                <input class="required" type="text" name="name" id="name" />
            </div>
        </div>
        <div class="row">
            <label for="d1_textfield">
                <strong>Affiliate URL</strong>
            </label>
            <div>
                <input class="required" type="text" name="url" id="url" placeholder="http://" />
                <div class="sub_row">
                    <input type="button" value="[[clickid]]" onclick="$('#url').insertAtCaret('[[clickid]]');" />
                    <input type="button" value="[[subid1]]" onclick="$('#url').insertAtCaret('[[subid1]]');" />
                    <input type="button" value="[[subid2]]" onclick="$('#url').insertAtCaret('[[subid2]]');" />
                    <input type="button" value="[[subid3]]" onclick="$('#url').insertAtCaret('[[subid3]]');" />
                    <input type="button" value="[[subid4]]" onclick="$('#url').insertAtCaret('[[subid4]]');" />
                    <input type="button" value="[[keyword]]" onclick="$('#url').insertAtCaret('[[keyword]]');" />
                </div>
            </div>
        </div>
        <div class="row">
            <label for="d1_textfield">
                <strong>Payout</strong>
            </label>
            <div>
                <input class="required" type="text" name="payout" id="payout" />
            </div>
        </div>
    </form>
    <div class="actions">
        <div class="left">
            <button class="grey cancel">Cancel</button>
        </div>
        <div class="right">
            <button class="submit">Save</button>
        </div>
    </div>
</div>

<script src="/theme/js/scripts/offers.js"></script>