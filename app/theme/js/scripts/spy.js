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
        bServerSide: true,
        iDisplayLength: 50,
        sAjaxSource: "/ajax/spy/data",
        "sDom": '<"top"fl<"clear">>rt<"bottom"lip<"clear">>',
        fnInitComplete: function() {
            //$(".dataTables_length").hide();
            //$(".dataTables_processing").hide();
        },
        fnDrawCallback: function() {
            setupTooltip();
        },
        aoColumns: [
            { "bSortable": false,"sType": "string", "sClass":"center" },
            { "bSortable": false,"bSearchable": false },
            { "bSortable": false, "sType": "string", "bSearchable": false },
            { "bSortable": false,"bSearchable": false },
            { "bSortable": false, "sType": "string" },
            { "bSortable": false,"bSearchable": false },
            { "bSortable": false,"bSearchable": false },
            { "bSortable": false,"bSearchable": false, "sClass":"center" }
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
    $('.tooltip').tooltip( "close" );
    $("#visitors_table").dataTable().fnDraw(false);
}
