$(document).ready(function() {
    "use strict";

    $("#customReportContent").hide();
    $("#generate_custom_report").click(function(e) {
        e.preventDefault();
        $.post('/ajax/reports/customReport',$('#user_prefs').serialize(true),
            function(data) {
                $("#result_table").html(data);
                $("#customReportContent").show();
                //$("#result_table").dataTable();
            }
        );
        return false;
    });

    $("#allData").click(function(){
        $(".selectall").click();
    });

    $(".selectall").click(function(){
        var input = $(this);
        var inputs = input.closest(".reportOptions").next().find('input');
        inputs.prop('checked', input.is(":checked"));
    });

    $(".reportOptions input[class!='selectall']").click(function(){
        var input = $(this);
        var total = input.closest(".reportOptions").find("input").length;
        var selected = input.closest(".reportOptions").find("input:checked").length;
        var optAll = input.closest(".reportOptions").prev().find('.selectall');
        if (total == selected) {
            optAll.prop('checked', true);
        } else {
            optAll.prop('checked', false);
        }
    });

});

function exportCsv() {
    iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);
    iframe.src = '/overview/exportBreakdown?iSortCol_0=0&sSortDir_0=asc';
}