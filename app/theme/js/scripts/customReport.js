$(document).ready(function() {
    "use strict";

    var oTable = null;
    $("#customReportContent").hide();
    $("#generate_custom_report").click(function(e){
        e.preventDefault();

        $("#errorData").hide();
        $("#customReportContent").show();
        if($("input[name='clickData[]']:checked").length==0 && $("input[name='campaignData[]']:checked").length ==0 &&
            $("input[name='deviceData[]']:checked").length==0 && $("input[name='tokenData[]']:checked").length==0 &&
            $("input[name='carrierData[]']:checked").length==0){
            $("#errorData").show();
            return false;
        }

        if(oTable != null){
            oTable.fnDestroy();
            oTable = null;
            $("#result_table").empty();
        }
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
                "aTargets": [i],
                "sClass": "center"
            });
        };

        oTable = $("#result_table").dataTable({
            "sDom": '<"top"lT>rt<"footer"ip><"clear">',
            "oTableTools": {
                "sSwfPath": "/theme/swf/copy_csv_xls_pdf.swf"
            },
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