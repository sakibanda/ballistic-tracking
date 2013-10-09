$(function() {
    "use strict";

    var oTable = $("#spending_table").dataTable({
        "bServerSide": false, //only ajax
        sAjaxSource: "/ajax/spending/data_spending",
        aoColumns: [
            { "bSortable": false }, //date
            { }, //campaign
            { "sClass":"center" }, //spend
            { "bSortable": false,"sClass":"center" } //actions
        ],
        fnDrawCallback: function() {
            $(".delete_spend").click(function(e) {
                e.preventDefault();
                if(!confirm("Are you sure you want to delete this entry?")) {
                    return;
                }
                var spending_id = $(this).parents('tr').find('.spending_id').val();
                var postdata = {spending_id:spending_id};
                $.post("/ajax/spending/postDelete",postdata,function(data) {
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