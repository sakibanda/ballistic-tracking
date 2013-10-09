$(function() {
    "use strict";

    var oTable = $("#income_table").dataTable({
        "bServerSide": false, //only ajax
        sAjaxSource: "/ajax/income/data_income",
        aoColumns: [
            { "bSortable": false }, //date
            { }, //campaign
            { "sClass":"center" }, //spend
            { "bSortable": false,"sClass":"center" } //actions
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
                        oTable.fnReloadAjax();
                    }
                });
            });
        }
    });

});