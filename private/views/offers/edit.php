<?php $this->loadTemplate('message_boxes');  ?>

<h1 class="grid_12"><span>Edit Offer</span></h1>

<div class="grid_9">
    <form id="edit_offer_form" method="post" action="/offers/update" class="box">
        <input type="hidden" name="offer_id" id="offer_id"value="<?php echo $offer->offer_id; ?>"/>
        <div class="header"><h2>Offer</h2></div>
        <div class="content">
            <div class="row">
                <label class="tooltip" title="Name your offer">Offer Name:</label>
                <div>
                    <input type="text" name="name" id="name" class="required" value="<?php echo $offer->name; ?>" />
                </div>
            </div>
            <div class="row">
                <label class="tooltip" title="Affiliate URL">Affiliate URL:</label>
                <div>
                    <input type="text" id="url" name="url" class="required" value="<?php echo $offer->url; ?>" />
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
                <label class="tooltip" title="Payout">Payout</label>
                <div>
                    <input type="text" class="required" value="<?php echo $offer->payout; ?>" name="payout" /> <br />
                </div>
            </div>
            <div class="row">
                <label for="d1_textfield">
                    <strong>Network</strong>
                </label>
                <div>
                    <?php
                    BTForm::createSelect('aff_network_id',AffNetworkModel::model()->getRows(),@$offer->network->aff_network_id,'aff_network_id','','name','aff_network_id');
                    ?>
                </div>
            </div>
        </div>
        <div class="actions">
            <div class="right">
                <input type="submit" value="Save" />
            </div>
        </div>
    </form>
</div>

<script>
    $(function(){
        $("#edit_offer_form").validate();
    });
</script>