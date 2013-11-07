<?php if(isset($campaign) && $campaign->id()){ ?>
    <div class="grid_12" style="text-align: center; margin-bottom: 15px;">
        <h2><?php echo $campaign->name; ?></h2>
        <a href="/tracker/code?campaign_id=<?=$campaign->id()?>"><b>Edit Campaign</b></a>
        <input type="hidden" id="camp_id" value="<?=$campaign->id()?>"/>
    </div>
<?php } ?>

<div class="grid_12">
    <div class="box with-table">
        <div class="content">
            <div class="tabletools">
                <div class="left">
                    <h3>Campaign Overview</h3>
                </div>
            </div>
            <table class="dataTable" id="campaign_overview">
                <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Clicks</th>
                    <th>LP Views</th>
                    <th>Offer Clicks</th>
                    <th>LP CTR</th>
                    <th>Leads</th>
                    <th>Offer CVR</th>
                    <th>LP CVR</th>
                    <th>EPC</th>
                    <th>CPC</th>
                    <th>Rev.</th>
                    <th>Cost</th>
                    <th>Profit</th>
                    <th>ROI</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="grid_12">
    <div class="box with-table">
        <div class="content">
            <div class="tabletools">
                <div class="left">
                    <h3>Offer Overview</h3>
                </div>
            </div>
            <table class="dataTable" id="offer_overview">
                <thead>
                <tr>
                    <th>Offers</th>
                    <th>Offer Clicks</th>
                    <th>LP CTR</th>
                    <th>Leads</th>
                    <th>Offer CVR</th>
                    <th>LP CVR</th>
                    <th>EPC</th>
                    <th>CPC</th>
                    <th>Rev.</th>
                    <th>Cost</th>
                    <th>Profit</th>
                    <th>ROI</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="grid_12">
    <div class="box with-table">
        <div class="content">
            <div class="tabletools">
                <div class="left">
                    <h3>Landing Page Overview</h3>
                </div>
            </div>
            <table class="dataTable" id="lp_overview">
                <thead>
                <tr>
                    <th>Landing Pages</th>
                    <th>Clicks</th>
                    <th>LP Views</th>
                    <th>Offer Clicks</th>
                    <th>LP CTR</th>
                    <th>Leads</th>
                    <th>Offer CVR</th>
                    <th>LP CVR</th>
                    <th>EPC</th>
                    <th>CPC</th>
                    <th>Rev.</th>
                    <th>Cost</th>
                    <th>Profit</th>
                    <th>ROI</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="grid_12">
    <div class="box with-table">
        <div class="content">
            <div class="tabletools">
                <div class="left">
                    <h3>Subid Overview</h3>
                </div>
            </div>
            <table class="dataTable" id="subid_overview">
                <thead>
                <tr>
                    <th>Subid1</th>
                    <th>Subid2</th>
                    <th>Subid3</th>
                    <th>Subid4</th>
                    <th>Clicks</th>
                    <th>Offer Clicks</th>
                    <th>Leads</th>
                    <th>Offer CVR</th>
                    <th>AVG Payout</th>
                    <th>EPC</th>
                    <th>Income</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script src="/theme/js/scripts/stats.js"></script>