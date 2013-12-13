// ! Your application

$(document).ready(function() {
	$('input[type=date]').datepicker();
	$('input.date').datepicker();
	setupTooltip();

    //Jquery Datatable Config by default
    $.extend($.fn.dataTable.defaults, {
        "bPaginate": true, //pagination,
        "sPaginationType": "full_numbers", //two_button or full_numbers
        "bLengthChange": true, //select to change how many rows
        "aLengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "iDisplayLength": 10, //results per page
        "iDisplayStart": 0, //page to start
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
    /* array of objects
    "aoColumns": [
        { "mData": "engine" },
        { "mData": "browser" },
        { "mData": "platform" },
        { "mData": "version" },
        { "mData": "grade" }
    ]
    */

    $.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw ){
        if ( sNewSource !== undefined && sNewSource !== null ) {
            oSettings.sAjaxSource = sNewSource;
        }
        // Server-side processing should just call fnDraw
        if ( oSettings.oFeatures.bServerSide ) {
            this.fnDraw();
            return;
        }
        this.oApi._fnProcessingDisplay( oSettings, true );
        var that = this;
        var iStart = oSettings._iDisplayStart;
        var aData = [];
        this.oApi._fnServerParams( oSettings, aData );
        oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
            /* Clear the old information from the table */
            that.oApi._fnClearTable( oSettings );
            /* Got the data - add it to the table */
            var aData =  (oSettings.sAjaxDataProp !== "") ?
                that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;

            for ( var i=0 ; i<aData.length ; i++ ){
                that.oApi._fnAddData( oSettings, aData[i] );
            }
            oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
            that.fnDraw();
            if ( bStandingRedraw === true ){
                oSettings._iDisplayStart = iStart;
                that.oApi._fnCalculateEnd( oSettings );
                that.fnDraw( false );
            }
            that.oApi._fnProcessingDisplay( oSettings, false );
            /* Callback user function - for event handlers etc */
            if ( typeof fnCallback == 'function' && fnCallback !== null ){
                fnCallback( oSettings );
            }
        }, oSettings );
    };

    TableTools.BUTTONS.download = {
        "sAction": "text",
        "sTag": "default",
        "sFieldBoundary": "",
        "sFieldSeperator": "\t",
        "sNewLine": "<br>",
        "sToolTip": "",
        "sButtonClass": "DTTT_button_text",
        "sButtonClassHover": "DTTT_button_text_hover",
        "sButtonText": "Download",
        "mColumns": "all",
        "bHeader": true,
        "bFooter": true,
        "sDiv": "",
        "fnMouseover": null,
        "fnMouseout": null,
        "fnClick": function( nButton, oConfig ) {
            var oParams = this.s.dt.oApi._fnAjaxParameters( this.s.dt );
            var iframe = document.createElement('iframe');
            iframe.style.height = "0px";
            iframe.style.width = "0px";
            iframe.src = oConfig.sUrl+"?o=csv&"+$.param(oParams);
            document.body.appendChild( iframe );
        },
        "fnSelect": null,
        "fnComplete": null,
        "fnInit": null
    };
});

function setupTooltip() {
	$('.tooltip').tooltip({ position: { my: "center top+15", at: "center" } });
}

function showLoadingScreen()	{
	$('#loadingnew-overlay').show();
	$('#loadingnew').show();
}

function hideLoadingScreen()	{
	$('#loadingnew-overlay').fadeOut(500);
	$('#loadingnew').fadeOut(500);
}

function showErrorMessage(message) {
	$(".alert.success").hide();
	$(".alert.warning").hide();
	$(".alert.error").show();
	
	$(".alert.error .message").html(message);
}

function showWarningMessage(message) {
	$(".alert.success").hide();
	$(".alert.warning").show();
	$(".alert.error").hide();
	
	$(".alert.warning .message").html(message);
}

function showSuccessMessage(message) {
	$(".alert.success").show();
	$(".alert.warning").hide();
	$(".alert.error").hide();
	
	$(".alert.success .message").html(message);
}

$.fn.table = function(opts) {
	var defaultOptions = {
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "iDisplayLength": 20,
		"sDom": 't<"footer"ip>',
		"sPaginationType": 'full_numbers'
	};
	
	if(opts == 'undefined') {
		opts = {};
	}
	
	var useOpts = $.extend({},defaultOptions,opts);
	
	$(this).dataTable(useOpts);
}