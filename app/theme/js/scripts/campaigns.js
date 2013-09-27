$(function(){
    "use strict";

    $("#generate_code").click(function(e) {
        e.preventDefault();
        $.post('/ajax/tracker/code/generateTrackingLink',$('#tracking_form').serialize(true),
            function(data) {
                var obj = $.parseJSON(data);
                if(obj.message == '1') { //nothing fancy - just redirect.
                    window.location.href = "/tracker/code/?campaign_id=" + obj.campaign_id + "#tracking_bottom";
                }else if(obj.message == '2') {
                    window.location.reload();
                }else {
                    var $dialog = $("<div>" + obj.message + "<\/div>")
                        .dialog({
                            autoOpen: false,
                            title: "Get Tracking Links",
                            width: 600,
                            modal: true
                        });

                    $dialog.dialog('open');

                    $(".close_link_dialog").click(function() {
                        $dialog.dialog('close');
                    });
                }
            }
        );
        return false;
    });

    $(body).on('click','.textarea_hoverover',function(e) {
        e.stopPropagation();
    });

    $("#lpoffer_holder").on('click','textarea',function(e) {
        e.stopPropagation();
        showOfferTextareaHover(this);
    });

    $("#offer_holder").on('click','textarea',function(e) {
        e.stopPropagation();
        showOfferTextareaHover(this);
    });

    $("#lp_holder").on('click','input[name^="lp_url"]',function(e) {
        e.stopPropagation();
        showOfferTextareaHover(this);
    });

    $("#vp_table").on('click','input:checkbox',function(e){
        e.stopPropagation();
        var input = $(this).prev('input:hidden:first');
        if(this.checked)
            input.val("1");
        else
            input.val("0");
    });

    $(body).click(function() {
        $(".textarea_hoverover").remove();
    });

    $("#offer_holder .content").on('keyup','.weight',function() {
        calculateWeights($("#offer_holder .content"));
    });

    type_checked($(".tracker_type:checked").val());

    $("#cloaker_id").change(function() {
        if($(this).val() == 0) {
            $(".only_adv_redirect").hide();
        }else{
            $(".only_adv_redirect").show();
        }
    });
    $("#cloaker_id").change();

    $("#affNetwork").change(function(){
        offersByAffNetwork();
    });

    $("#addOffer").click(function(){
        addExistingOffer();
    });

    $(".show_offer_btn").click(function(e){
        e.preventDefault();
        $("#showOffers").dialog("open");
        return false;
    });

    $("#showOffers").dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        resizable: false,
        open: function(){
            offersByAffNetwork();
            $(this).parent().css('overflow', 'visible');
        }
    }).find('button.cancel').click(function(){
        var $el = $(this).parents('.ui-dialog-content');
        $el.dialog('close');
    }).end().find('button.submit').click(function(){
        addExistingOffer();
        var $el = $(this).parents('.ui-dialog-content');
        $el.dialog('close');
    });
});

function type_checked(type) {
    if(type == 'lp') {
        $("#offer_holder").hide();
        $("#lp_holder").show();
        $("#lpoffer_holder").show();
        $(".lp_var_pass_opts").show(); //Show checkbox for var pass
    }
    else {
        $("#offer_holder").show();
        $("#lp_holder").hide();
        $("#lpoffer_holder").hide();
        $(".lp_var_pass_opts").hide(); //Hidden checkbox for var pass
    }
}

//Todo: clean this later. It's a messy beast
function showOfferTextareaHover(textarea) {
    $(".textarea_hoverover").remove();

    var obj = $("<div></div>");
    obj.css('background-color','#eee');
    obj.css('color','#000');
    obj.css('padding','5px 5px');
    obj.css('border','1px solid #ddd');

    obj.addClass("textarea_hoverover");

    obj.css('position','absolute');
    obj.css('z-index','10000');
    var pos = $(textarea).offset();
    obj.css('left',(pos.left) + "px");
    obj.css('top',(pos.top + $(textarea).outerHeight()) + "px");

    var current_focused_textarea = $(textarea);

    obj.append("<button>[[clickid]]</button>");
    obj.append("<button>[[subid1]]</button>");
    obj.append("<button>[[subid2]]</button>");
    obj.append("<button>[[subid3]]</button>");
    obj.append("<button>[[subid4]]</button>");
    obj.append("<button>[[keyword]]</button>");
    obj.find('button').css('margin-right','5px');

    obj.find('button').click(function() {
        current_focused_textarea.insertAtCaret($(this).text());
    });
    $(body).append(obj);
}

function add_offer(offer_id,type) {
    $.get('/tracker/code/offerRow',{"campaign_offer_id":offer_id,"campaign_offer_type":type}, function(data) {
        if(type!="lp"){
            var offer = $('#offer_holder');
            offer.find(".content").append(data);
            calculateWeights(offer.find(".content"));
        }else{
            var lpOffer = $('#lpoffer_holder');
            lpOffer.find(".content").append(data);
            calculateWeights(lpOffer.find(".content"));
        }
    });
}

var countOffer = 0;
var countLPOffer = 0;
function addExistingOffer(){
    var offer_id = $("#offerList").val();
    var type = $("input:radio[name='tracker_type']:checked").val();
    $.get('/tracker/code/offerExistingRow',{"offer_id":offer_id,"offer_type":type}, function(data) {
        if(type!="lp"){
            var offer = $('#offer_holder');
            if(offer.find('.offer_table').length == 1 && countOffer == 0){
                offer.find('.offer_table:lt(1)').remove();
                countOffer++;
            }
            offer.find(".content").append(data);
            calculateWeights(offer.find(".content"));
        }else{
            var lpOffer = $('#lpoffer_holder');
            if(lpOffer.find('.offer_table').length == 1 && countLPOffer == 0){
                lpOffer.find('.offer_table:lt(1)').remove();
                countLPOffer++;
            }
            lpOffer.find(".content").append(data);
            calculateWeights(lpOffer.find(".content"));
        }
    });
}

/* show the existing offers associated to Aff Network selected */
function offersByAffNetwork(){
    var affNetwork = $("#affNetwork").val();
    $.getJSON('/tracker/code/offersByAffNetwork',{"aff_network_id":affNetwork}, function(data){
        $("#offerList").empty();
        $.each(data, function(index,item) {
            $("#offerList").append("<option value=" + item.id + ">" + item.name + "</option>");
        });
        $("div#showOffers").show();
    });
}

/* Landing Page */
function add_lp(lp_id) {
    $.get('/tracker/code/landingPageRow',{"campaign_lp_id":lp_id},function(data) {
        $("#lp_table").append(data);
    });
}

/* Variable Passthrough */
function add_var_pass(){
    var type = $("input:radio[name='tracker_type']:checked").val();
    $.get('/tracker/code/variablePassRow',{},function(data) {
        $("#vp_table").append(data);
        if(type=="direct")
            $(".lp_var_pass_opts").hide();
        else
            $(".lp_var_pass_opts").show();
    });
}

function deleteVarPassTable(obj) {
    if(!confirm("Are you sure you want to delete this Variable Passthrough?")) {
        return;
    }
    $(obj).parents('tr').remove();
}

function deleteDataRow(obj) {
    $(obj).parents('tr').remove();
}

function deleteOfferTable(obj) {
    if(!confirm("Are you sure you want to delete this offer?")) {
        return;
    }
    $(obj).parents('.offer_table').remove();
    calculateWeights($("#offer_holder .content"));
}

function calculateWeights(section_obj) {
    var total = 0;
    $(section_obj).find('.weight').each(function() {
        var weight = parseInt($(this).val(),10);
        if(isNaN(weight)) {
            weight = 0;
        }
        total += weight;
    });

    $(section_obj).find('.weight').each(function() {
        var display = $(this).siblings(".weight_display");
        var weight = parseInt($(this).val(),10);
        if(isNaN(weight)) {
            weight = 0;
        }
        var percent = ((weight / total) * 100);
        percent = Math.round(percent * 100) / 100;
        if(isNaN(percent)) {
            display.text('0%');
        } else {
            display.text(percent + "%");
        }
    });
}