$(document).ready(function() {
    "use strict";

    $( "#dialog_manage_account" ).dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        open: function(){ $(this).parent().css('overflow', 'visible'); }
    }).find('button.submit').click(function(){
        var $el = $(this).parents('.ui-dialog-content');
        $el.find('form')[0].reset();
        $el.dialog('close');
    }).end().find('button.cancel').click(function(){
        var $el = $(this).parents('.ui-dialog-content');
        $el.find('form')[0].reset();
        $el.dialog('close');
    });

    loadManageAccountList();
});

function deleteUser(userid) {
    var id = userid;
    if(confirm("Are you sure you want to delete this account? All settings, campaigns, forum posts, and other user data will be permanently removed.")) {
        $.post('/ajax/admin/accounts/post_delete',{user_id: id},function() {
            loadManageAccountList();
            showSuccessMessage("Account deleted");
        });
    }
}

function editUser(userid) {
    var id = userid;
    $.getJSON('/ajax/admin/accounts/json_user',{user_id: id},function(data) {
        $("#user_id").val(data.user_id);
        $("#user_name").val(data.user_name);
        $("#email").val(data.email);

        $("#privilege").val(data.privilege);
        /*
         $("#pass").rules("remove", "required" );
         $("#pass").rules("remove", "equalTo" );
         $("#pass_confirm").rules("remove", "required" );
         $("#pass_confirm").rules("remove", "equalTo" );
         */
        $('#dialog_manage_account').dialog('option', 'title', 'Edit User Account');
        $("#dialog_manage_account" ).dialog( "open" );
    });
}

function submitForm()	{
    var action = "add";
    if($("#user_id").val()) {
        action = "edit";
    }
    $.ajax({
        type: "POST",
        url: "/ajax/admin/accounts/post_" + action,
        data: $("#add_user_form").serialize(),
        beforeSend: function() {
            $('#load_addedituser').html("<img src=/theme/img/loader-small.gif alt='loading...' title='loading...' width='25' height='25' />");
        },
        success: function(response) {
            $('#load_addedituser').html('');
            if(response != '0')	{
                showErrorMessage(response);
            } else {
                $( "#dialog_manage_account" ).dialog('close');
                showSuccessMessage("User saved");
            }
            loadManageAccountList();
        }
    });
}

function addNewUser(){
    $("#user_id").val('');
    $("#user_name").val('');
    $("#email").val('');

    $("#privilege").val('');

    /*$("#pass").rules("add", {
     required: true,
     messages: {
     required: "Enter a password."
     }
     });*/
    /*
     $("#pass_confirm").rules("add", {
     required: true,
     equalTo: "#pass",
     messages: {
     required: "Confirm the password",
     equalTo: "Does not match with the password."
     }
     });
     */
    $('#dialog_manage_account').dialog('option', 'title', 'Add User Account');
    $('#dialog_manage_account').dialog('open');
}

function loadManageAccountList(){
    $.ajax({
        type: "GET",
        url: "/ajax/admin/accounts/view_accountlist",
        data: '',
        beforeSend: function() {
            $('#load_manageaccountlist').html("<img src=/theme/img/loader-small.gif alt='loading...' title='loading...' width='25' height='25' />");
        },
        success: function(response){
            $('#load_manageaccountlist').html(response);
        }
    });
}

function viewAsUser(userid) {
    if(confirm("Are you sure you want to browse Ballistic Tracking as this user?")) {
        window.location = "/login/viewAs?id=" + userid;
    }
}