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
                    <td class="center">1</td>
                    <td class="center">Test</td>
                    <td class="center">Garcinia AU New Order Page</td>
                    <td class="center">65.00</td>
                    <td class="center"><a class="button small grey tooltip" href="#" target="_blank"><i class="icon-external-link"></i></a></td>
                    <td class="center">
                        <a href="#" class="button small grey" title="Edit"><i class="icon-pencil"></i> Edit</a>
                        <a class="button small grey" title="Delete" href="#"><i class="icon-remove"></i> Delete</a>
                    </td>
                </tr>
                <tr>
                    <td class="center">2</td>
                    <td class="center">Network</td>
                    <td class="center">data table</td>
                    <td class="center">100.00</td>
                    <td class="center"><a class="button small grey tooltip" href="#" target="_blank"><i class="icon-external-link"></i></a></td>
                    <td class="center">
                        <a href="#" class="button small grey" title="Edit"><i class="icon-pencil"></i> Edit</a>
                        <a class="button small grey" title="Delete" href="#"><i class="icon-remove"></i> Delete</a>
                    </td>
                </tr>
                <tr>
                    <td class="center">3</td>
                    <td class="center">gogle</td>
                    <td class="center">offer name</td>
                    <td class="center">23.00</td>
                    <td class="center"><a class="button small grey tooltip" href="#" target="_blank"><i class="icon-external-link"></i></a></td>
                    <td class="center">
                        <a href="#" class="button small grey" title="Edit"><i class="icon-pencil"></i> Edit</a>
                        <a class="button small grey" title="Delete" href="#"><i class="icon-remove"></i> Delete</a>
                    </td>
                </tr>
                <tr>
                    <td class="center">4</td>
                    <td class="center">php</td>
                    <td class="center">ballistic tracking</td>
                    <td class="center">80.00</td>
                    <td class="center"><a class="button small grey tooltip" href="#" target="_blank"><i class="icon-external-link"></i></a></td>
                    <td class="center">
                        <a href="#" class="button small grey" title="Edit"><i class="icon-pencil"></i> Edit</a>
                        <a class="button small grey" title="Delete" href="#"><i class="icon-remove"></i> Delete</a>
                    </td>
                </tr>
                <tr>
                    <td class="center">5</td>
                    <td class="center">jquery</td>
                    <td class="center">adrian</td>
                    <td class="center">200.00</td>
                    <td class="center"><a class="button small grey tooltip" href="#" target="_blank"><i class="icon-external-link"></i></a></td>
                    <td class="center">
                        <a href="#" class="button small grey" title="Edit"><i class="icon-pencil"></i> Edit</a>
                        <a class="button small grey" title="Delete" href="#"><i class="icon-remove"></i> Delete</a>
                    </td>
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
            "bPaginate": true, //pagination
            "bLengthChange": true, //select to change how many rows
            "bFilter": true, //search input
            "bSort": true, //arrows on header
            "bInfo": true, //Showing 1 to 5 of 5 entries
            "bAutoWidth": true
        });
    });
</script>