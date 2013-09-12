$(document).ready(function(){
    "use strict";

    $("#toggleSpyTimer").click(function() {
        if(theSpyTimer) {
            $(this).html("<i class=\"icon-play\"></i> Start Spy");
            stopSpyTimer();
        }
        else {
            $(this).html("<i class=\"icon-pause\"></i> Pause Spy");
            startSpyTimer();
        }
    });

    $("#visitors_table").dataTable({
        //sDom: '<"filters"fl>rt<"footer"ip>',
        sDom: '<"filters"p<"clear">>rt<"footer"ip>',
        bProcessing: false,
        sPaginationType: 'full_numbers',
        bServerSide: true,
        bSearchable: false,
        bFilter: false,
        iDisplayLength: 100,
        sAjaxSource: "/ajax/spy/data",
        fnInitComplete: function() {
            //$(".dataTables_length").hide();
            //$(".dataTables_processing").hide();
        },
        fnDrawCallback: function() {
            setupTooltip();
        },
        aoColumns: [
            { "bSortable": false },
            { "bSortable": false },
            { "bSortable": false },
            { "bSortable": false },
            { "bSortable": false },
            { "bSortable": false },
            { "bSortable": false },
            { "bSortable": false, "sClass":"center" }
        ]
    });

    startSpyTimer();

});

function startSpyTimer() {
    theSpyTimer = setInterval(function() { runSpy(); },5000);
}

function stopSpyTimer() {
    clearInterval(theSpyTimer);
    theSpyTimer = 0;
}

function runSpy() {
    $("#visitors_table").dataTable().fnDraw(false);
}
