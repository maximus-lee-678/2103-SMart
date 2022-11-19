$(document).ready(function () {

    let fname, lname, email, phone;

    $('#myaddresstable #myaddressdata tr').each(function () {
        $(this).click(() => {
            setAddressTableRowClickable($(this));
        });
    });

    $('#mycardtable #mycarddata tr').each(function () {
        $(this).click(() => {
            setCardTableRowClickable($(this));
        });
    });

    $('#editmyprofileBtn').click(() => {

        fname = $('#user_firstname').attr('value');
        lname = $('#user_lastname').attr('value');
        phone = $('#user_phonenum').attr('value');
        email = $('#user_email').attr('value');
        $('#user_firstname').removeAttr('readonly');
        $('#user_lastname').removeAttr('readonly');
        $('#user_phonenum').removeAttr('readonly');
        $('#user_email').removeAttr('readonly');
        $('#editmyprofileBtn').attr('type', 'hidden');
        $('#updatemyprofileBtn').attr('type', 'button');
        $('#cancelBtn').attr('type', 'button');
    });

    $('#cancelBtn').click(() => {

        $('#user_firstname').val(fname);
        $('#user_lastname').val(lname);
        $('#user_phonenum').val(phone);
        $('#user_email').val(email);
        $('#user_firstname').attr('readonly', true);
        $('#user_lastname').attr('readonly', true);
        $('#user_phonenum').attr('readonly', true);
        $('#user_email').attr('readonly', true);
        $('#editmyprofileBtn').attr('type', 'button');
        $('#updatemyprofileBtn').attr('type', 'hidden');
        $('#cancelBtn').attr('type', 'hidden');
        clearFeedback1();
    });

    $('#updatemyprofileBtn').click(() => {

        let payload = {
            "staff": document.getElementById("myaddress") ? 0 : 1,
            "fname": $('#user_firstname').val(),
            "lname": $('#user_lastname').val(),
            "phone": $('#user_phonenum').val(),
            "email": $('#user_email').val()
        };
        
        update_profile(payload);
    });

    $('#updateaddressBtn').click(() => {

        let data = {
            "id": $('#user_addressid1').val(),
            "alias": $('#user_alias').val(),
            "address": $('#user_address1').val(),
            "unitno": $('#user_unitno').val(),
            "postal": $('#user_postalcode1').val()
        };

        update_address(data);
    });

    $('#addaddressBtn').click(() => {

        let payload = {
            "alias": $('#user_alias').val(),
            "address": $('#user_address1').val(),
            "unitno": $('#user_unitno').val(),
            "postal": $('#user_postalcode1').val()
        };

        add_address(payload);
    });

    $('#deleteaddressBtn').click(() => {

        let payload = {
            "id": $('#user_addressid1').val()
        };

        delete_address(payload);

    });

    $('#changepasswordBtn').click(() => {

        let payload = {
            "staff": document.getElementById("myaddress") ? 0 : 1,
            "old_password": $('#user_old_password').val(),
            "new_password": $('#user_new_password').val(),
            "confirm_password": $('#user_confirm_password').val()
        };

        change_password(payload);

    });

    $('#updatecardBtn').click(() => {

        let data = {
            "id": $('#user_payid').val(),
            "paytype": $('#user_payment_type').val(),
            "owner": $('#user_owner').val(),
            "accno": $('#user_accountno').val(),
            "expiry": $('#user_cardexpirydate').val()
        };

        update_card(data);
    });

    $('#addcardBtn').click(() => {

        let data = {
            "paytype": $('#user_payment_type').val(),
            "owner": $('#user_owner').val(),
            "accno": $('#user_accountno').val(),
            "expiry": $('#user_cardexpirydate').val()
        };

        add_card(data);
    });

    $('#deletecardBtn').click(() => {

        let payload = {
            "id": $('#user_payid').val()
        };

        delete_card(payload);

    });

    function setAddressTableRowClickable(tr) {
        $('#user_addressid1').val(tr.attr('id').slice(8));
        $('#user_alias').val(tr.find("td:eq(0)").text());
        $('#user_address1').val(tr.find("td:eq(1)").text());
        $('#user_unitno').val(tr.find("td:eq(2)").text());
        $('#user_postalcode1').val(tr.find("td:eq(3)").text());
    }

    function setCardTableRowClickable(tr) {
        $('#user_payid').val(tr.attr('id').slice(8));
        $('#user_payment_type').val(tr.find("td:eq(0)").text());
        $('#user_owner').val(tr.find("td:eq(1)").text());
        $('#user_accountno').val(tr.find("td:eq(2)").text());
        $('#user_cardexpirydate').val(tr.find("td:eq(3)").text());
    }

    function update_profile(data) {

        $.ajax({
            type: 'POST',
            url: 'profile-backend.php',
            data: {type: 'profile', mode: "update", data: data},
            success: function (response) {
                let resObj = $.parseJSON(response);
                if (resObj["success"]) {
                    $('#submission_feedback1').removeAttr('hidden');
                    $('#submission_feedback1').html("Success!");
                    $('#user_firstname').attr('readonly', true);
                    $('#user_lastname').attr('readonly', true);
                    $('#user_phonenum').attr('readonly', true);
                    $('#user_email').attr('readonly', true);
                    let newData = resObj["data"];
                    $('#user_firstname').val(newData["fname"]);
                    $('#user_lastname').val(newData["lname"]);
                    $('#user_phonenum').val(newData["phone"]);
                    $('#user_email').val(newData["email"]);
                    $('#editmyprofileBtn').attr('type', 'button');
                    $('#updatemyprofileBtn').attr('type', 'hidden');
                    $('#cancelBtn').attr('type', 'hidden');
                    setTimeout(clearFeedback1, 3000);
                } else {
                    $('#submission_feedback1').removeAttr('hidden');
                    $('#submission_feedback1').html("Failed!<br>" + resObj["data"]);
                }
            }
        });
    }

    function clearFeedback1() {
        $('#submission_feedback1').attr('hidden');
        $('#submission_feedback1').html("");
    }

    function clearFeedback2() {
        $('#submission_feedback2').attr('hidden');
        $('#submission_feedback2').html("");
    }

    function clearFeedback3() {
        $('#submission_feedback3').attr('hidden');
        $('#submission_feedback3').html("");
    }

    function clearFeedback4() {
        $('#submission_feedback4').attr('hidden');
        $('#submission_feedback4').html("");
    }

    function update_address(payload) {
        
        console.log(payload);

        $.ajax({
            type: 'POST',
            url: 'profile-backend.php',
            data: {type: 'address', mode: "update", data: payload},
            success: function (response) {
                let resObj = $.parseJSON(response);
                if (resObj["success"]) {
                    let newData = resObj["data"];
                    $('#submission_feedback2').removeAttr('hidden');
                    $('#submission_feedback2').html("Success!");

                    $(`#address_${payload['id']} .alias_data`).text(newData['alias']);
                    $(`#address_${payload['id']} .address_data`).text(newData['address']);
                    $(`#address_${payload['id']} .unitno_data`).text(newData['unitno']);
                    $(`#address_${payload['id']} .postal_data`).text(newData['postal']);
                    
                    $(`#address_${payload['id']}`).attr('id', `address_${newData['id']}`);  
                    $('#user_addressid1').val(newData['id']);

                    setTimeout(clearFeedback2, 3000);
                } else {
                    $('#submission_feedback2').removeAttr('hidden');
                    $('#submission_feedback2').html("Failed!<br>" + resObj["data"]);
                    setTimeout(clearFeedback2, 3000);
                }
            }
        });

    }

    function add_address(payload) {

        $.ajax({
            type: 'POST',
            url: 'profile-backend.php',
            data: {type: 'address', mode: "add", data: payload},
            success: function (response) {
                let resObj = $.parseJSON(response);
                if (resObj["success"]) {
                    let newData = resObj["data"];
                    $('#submission_feedback2').removeAttr('hidden');
                    $('#submission_feedback2').html("Success!");

                    $('#user_addressid1').val("");
                    $('#user_alias').val("");
                    $('#user_address1').val("");
                    $('#user_unitno').val("");
                    $('#user_postalcode1').val("");

                    $('#myaddresstable > #myaddressdata').append(
                            `<tr id="address_${newData['id']}" class="addressRow">` +
                            `<td class="alias_data">${newData['alias']}</td>` +
                            `<td class="address_data">${newData['address']}</td>` +
                            `<td class="unitno_data">${newData['unitno']}</td>` +
                            `<td class="postal_data">${newData['postal']}</td>` +
                            `</tr>`);

                    let tr = $(`#address_${newData['id']}`);
                    tr.click(() => {
                        setAddressTableRowClickable(tr);
                    });

                    setTimeout(clearFeedback2, 3000);
                } else {
                    $('#submission_feedback2').removeAttr('hidden');
                    $('#submission_feedback2').html("Failed!<br>" + resObj["data"]);
                    setTimeout(clearFeedback2, 3000);
                }
            }
        });

    }

    function delete_address(payload) {
        $.ajax({
            type: 'POST',
            url: 'profile-backend.php',
            data: {type: 'address', mode: "delete", data: payload},
            success: function (response) {
                let resObj = $.parseJSON(response);
                if (resObj["success"]) {
                    $('#submission_feedback2').removeAttr('hidden');
                    $('#submission_feedback2').html("Success!");

                    $('#user_addressid1').val("");
                    $('#user_alias').val("");
                    $('#user_address1').val("");
                    $('#user_unitno').val("");
                    $('#user_postalcode1').val("");

                    $(`#myaddresstable > #myaddressdata > #address_${payload['id']}`).remove();

                    setTimeout(clearFeedback2, 3000);
                } else {
                    $('#submission_feedback2').removeAttr('hidden');
                    $('#submission_feedback2').html("Failed!<br>" + resObj["data"]);
                    setTimeout(clearFeedback2, 3000);
                }
            }
        });
    }

    function change_password(payload) {

        $.ajax({
            type: 'POST',
            url: 'profile-backend.php',
            data: {type: 'password', data: payload},
            success: function (response) {
                let resObj = $.parseJSON(response);
                if (resObj["success"]) {
                    $('#submission_feedback3').removeAttr('hidden');
                    $('#submission_feedback3').html("Success!");

                    setTimeout(clearFeedback3, 3000);
                } else {
                    $('#submission_feedback3').removeAttr('hidden');
                    $('#submission_feedback3').html("Failed!<br>" + resObj["data"]);

                    setTimeout(clearFeedback3, 3000);
                }
            }
        });

    }

    function add_card(payload) {

        $.ajax({
            type: 'POST',
            url: 'profile-backend.php',
            data: {type: 'card', mode: "add", data: payload},
            success: function (response) {
                let resObj = $.parseJSON(response);

                if (resObj["success"]) {
                    let newData = resObj["data"];
                    $('#submission_feedback4').removeAttr('hidden');
                    $('#submission_feedback4').html("Success!");

                    $('#user_payment_type').val("");
                    $('#user_owner').val("");
                    $('#user_accountno').val("");
                    $('#user_cardexpirydate').val("");

                    $('#mycardtable > #mycarddata').append(
                            `<tr id="payment_${newData['id']}" class="paymentRow">` +
                            `<td class="paytype_data">${newData['paytype']}</td>` +
                            `<td class="owner_data">${newData['owner']}</td>` +
                            `<td class="acc_data">${newData['accno']}</td>` +
                            `<td class="expiry_data">${newData['expiry']}</td>` +
                            `</tr>`);

                    let tr = $(`#payment_${newData['id']}`);
                    tr.click(() => {
                        setCardTableRowClickable(tr);
                    });

                    setTimeout(clearFeedback4, 3000);
                } else {
                    $('#submission_feedback4').removeAttr('hidden');
                    $('#submission_feedback4').html("Failed!<br>" + resObj["data"]);
                    setTimeout(clearFeedback4, 3000);
                }
            }
        });

    }

    function update_card(payload) {

        $.ajax({
            type: 'POST',
            url: 'profile-backend.php',
            data: {type: 'card', mode: "update", data: payload},
            success: function (response) {
                let resObj = $.parseJSON(response);
                if (resObj["success"]) {
                    let newData = resObj["data"];
                    $('#submission_feedback4').removeAttr('hidden');
                    $('#submission_feedback4').html("Success!");

                    $(`#payment_${payload['id']} .paytype_data`).text(newData['paytype']);
                    $(`#payment_${payload['id']} .owner_data`).text(newData['owner']);
                    $(`#payment_${payload['id']} .acc_data`).text(newData['accno']);
                    $(`#payment_${payload['id']} .expiry_data`).text(newData['expiry']);
                    
                    $(`#payment_${payload['id']}`).attr('id', `payment_${newData['id']}`);  
                    $('#user_payid').val(newData['id']);

                    setTimeout(clearFeedback4, 3000);
                } else {
                    $('#submission_feedback4').removeAttr('hidden');
                    $('#submission_feedback4').html("Failed!<br>" + resObj["data"]);
                    setTimeout(clearFeedback4, 3000);
                }
            }
        });

    }

    function delete_card(payload) {
        $.ajax({
            type: 'POST',
            url: 'profile-backend.php',
            data: {type: 'card', mode: "delete", data: payload},
            success: function (response) {
                let resObj = $.parseJSON(response);
                if (resObj["success"]) {
                    $('#submission_feedback4').removeAttr('hidden');
                    $('#submission_feedback4').html("Success!");

                    $('#user_payment_type').val("");
                    $('#user_owner').val("");
                    $('#user_accountno').val("");
                    $('#user_cardexpirydate').val("");

                    $(`#mycardtable > #mycarddata > #payment_${payload['id']}`).remove();

                    setTimeout(clearFeedback4, 3000);
                } else {
                    $('#submission_feedback4').removeAttr('hidden');
                    $('#submission_feedback4').html("Failed!<br>" + resObj["data"]);
                    setTimeout(clearFeedback4, 3000);
                }
            }
        });
    }

});