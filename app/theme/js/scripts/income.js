$(function() {
    "use strict";

    $("#income_table").dataTable({
        sDom: '<"filters"fl>rt<"footer"ip>',
        bProcessing: false,
        sPaginationType: 'full_numbers',
        bServerSide: true,
        bPaginate: false,
        bSearchable: false,
        bSort: false,
        bInfo: false,
        bFilter: false,
        iDisplayLength: 100,
        sAjaxSource: "/ajax/income/data_income",
        aoColumns: [
            { "bSortable": false }, //date
            { "bSortable": false }, //campaign
            { "bSortable": false }, //spend
            { "bSortable": false } //actions
        ],
        fnDrawCallback: function() {
            $(".delete_income").click(function(e) {
                e.preventDefault();
                if(!confirm("Are you sure you want to delete this entry?")) {
                    return;
                }
                var income_id = $(this).parents('tr').find('.income_id').val();
                var postdata = {income_id:income_id};
                $.post("/ajax/income/postDelete",postdata,function(data) {
                    if(data != 1) {
                        alert("An error ocurred while deleting the entry");
                    } else {
                        $("#income_table").dataTable().fnDraw();
                    }
                });
            });
        }
    });

});