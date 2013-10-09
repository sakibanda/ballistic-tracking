<h1 class="grid_12"><span>Campaign Stats</span></h1>
<script type="text/javascript">
    $('body').addClass('flex_width');
</script>
<p>Under Construction ...</p>
<div class="grid_12 minwidth">
    <div class="box with-table">
        <div class="content">
            <div class="tabletools">
                <div class="left">
                    <a class="open-add-client-dialog" id="add_offer_btn" href="javascript:void(0);"><i class="icon-plus"></i>Add Offer</a>
                </div>
                <div class="right">
                    <a href="#" onclick="exportCsv(); return false;">CSV</a>
                </div>
            </div>
            <table class="dataTable" id="table_id">
                <thead>
                <tr>
                    <th>Offer ID</th>
                    <th>Network</th>
                    <th>Offer Name</th>
                    <th>Payout</th>
                    <th>URL</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="6" class="dataTables_empty">Loading data from server</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th>Offer ID</th>
                    <th>Network</th>
                    <th>Offer Name</th>
                    <th>Payout</th>
                    <th>URL</th>
                    <th>Actions</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#table_id").dataTable({
            "bServerSide": true, //only ajax
            "sAjaxSource": '../ajax/stats/data',
            "aoColumns": [
                {"sClass":"center","bSearchable": false,"sType": "numeric"},
                {"sClass":"center","sType": "string"},
                {"sType": "string"},
                {"sClass":"center","sType": "numeric"},
                {"bSortable": false,"sClass":"center","bSearchable": false},
                {"bSortable": false,"bSearchable": false}
            ]
        });
    });
</script>