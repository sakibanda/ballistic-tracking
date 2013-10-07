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
        $.extend($.fn.dataTable.defaults, {
            "bPaginate": true, //pagination,
            "sPaginationType": "full_numbers", //two_button or full_numbers
            "bLengthChange": true, //select to change how many rows
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "iDisplayLength": 10, //results per page
            "iDisplayStart": 2, //page to start
            "bFilter": true, //search input
            "bSort": true, //arrows on header
            "bInfo": true, //Showing 1 to 5 of 5 entries
            "bAutoWidth": true,
            "bSortClasses": true, //large data it's better turn off
            "bStateSave": false, //save options, iCookieDuration seconds duration
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
            },
            "fnStateLoad": function (oSettings) {
                var data = localStorage.getItem('DataTables_'+window.location.pathname);
                return JSON.parse(data);
            },
            "aaSorting": [[ 0, "desc" ]], //default sort column
            "sDom": '<"top"lf>rt<"footer"ip><"clear">', //l=bLengthChange,f=bFilter,i=bInfo,p=bPaginate,t=table,r=pRocessing
            "bProcessing": true //loader text
        });
        $("#table_id").dataTable({
            "bServerSide": true,
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