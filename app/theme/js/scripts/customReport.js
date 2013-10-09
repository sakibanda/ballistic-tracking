$(document).ready(function() {
    "use strict";

    var oTable = null;
    //$("#customReportContent").hide();
    $("#generate_custom_report").click(function(e){

        if(oTable != null) oTable.fnDestroy();
        var aryColTableChecked = [];
        $("#reportOptions input:checked").each(function(){
            var columnName = $(this).data("column");
            if(typeof columnName !== "undefined")
                aryColTableChecked.push(columnName);
        });
        var aryJSONColTable = [];
        for (var i=0; i < aryColTableChecked.length; i++ ) {
            aryJSONColTable.push({
                "sTitle": aryColTableChecked[i],
                "aTargets": [i]
            });
        };

        oTable = $("#result_table").dataTable({
            "aoColumnDefs": aryJSONColTable,
            "bServerSide": true, //only ajax
            "sAjaxSource": '/ajax/reports/customReport',
            "bDeferRender": true,
            "iDisplayLength": 100,
            "bRetrieve": true,
            "bDestroy": true,
            "bFilter": false,
            "bSort": false,
            "sServerMethod": "POST",
            fnServerParams:function(aoData) {
                var serializedForm = $('form#user_prefs').serializeArray();
                for (var n in serializedForm) {
                    var tmpobj = serializedForm[n];
                    var key = tmpobj['name'];
                    var value = tmpobj['value'];
                    aoData.push({"name":key,"value":value});
                }
            }
        });



        return false;
        /*
        if(oTable != null) oTable.fnDestroy();
        e.preventDefault();
        $("#loading").show();
        $.post('/ajax/reports/customReport',$('#user_prefs').serialize(true),
            function(data) {
                $("#loading").hide();
                $("#result_table").html(data);
                initTable();
                $("#customReportContent").show();
            }
        );
        return false;
        */
    });
    /*
    function initTable(){
        oTable = $("#result_table").dataTable({
            "bRetrieve": true,
            "bDestroy": true,
            "bFilter": false,
            "bSortClasses": false,
            "bSort": false,
            "iDisplayLength": 100
        });
    }
*/
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