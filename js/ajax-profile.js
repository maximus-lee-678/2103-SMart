$(document).ready(function () {

    let fname, lname, email, phone;

    $('#myaddresstable #myaddressdata tr').each(function () {// table 
        console.log($(this));

        $(this).click(() => {
            setTableRowClickable($(this));
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
            "address": $('#user_address1').val(),
            "postal": $('#user_postalcode1').val()
        };

        update_address(data);
    });

    $('#addaddressBtn').click(() => {

        let payload = {
            "address": $('#user_address1').val(),
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

    function setTableRowClickable(tr) {
        $('#user_addressid1').val(tr.attr('id').slice(8));
        $('#user_address1').val(tr.find("td:eq(0)").text());
        $('#user_postalcode1').val(tr.find("td:eq(1)").text());
    }

    function update_profile(data) {

        $.ajax({
            type: 'POST',
            url: 'profile-backend.php',
            data: {type: 'profile', mode: "update", data: data},
            success: function (response) {
                let resObj = $.parseJSON(response);
                console.log(resObj);
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
                    $('#submission_feedback1').html("Failed!<br>" + resObj["message"]);
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

    function update_address(payload) {

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

                    $(`#address_${data['id']} .address_data`).text(newData['address']);
                    $(`#address_${data['id']} .postal_data`).text(newData['postal']);

                    setTimeout(clearFeedback2, 3000);
                } else {
                    $('#submission_feedback2').removeAttr('hidden');
                    $('#submission_feedback2').html("Failed!<br>" + resObj["message"]);
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
                console.log(resObj);
                if (resObj["success"]) {
                    let newData = resObj["data"];
                    $('#submission_feedback2').removeAttr('hidden');
                    $('#submission_feedback2').html("Success!");

                    $('#user_addressid1').val("");
                    $('#user_address1').val("");
                    $('#user_postalcode1').val("");

                    $('#myaddresstable > #myaddressdata').append(
                            `<tr id="address_${newData['id']}" class="addressRow">` +
                            `<td class="address_data">${newData['address']}</td>` +
                            `<td class="postal_data">${newData['postal']}</td>` +
                            `</tr>`);

                    let tr = $(`#address_${newData['id']}`);
                    tr.click(() => {
                        setTableRowClickable(tr);
                    });

                    setTimeout(clearFeedback2, 3000);
                } else {
                    $('#submission_feedback2').removeAttr('hidden');
                    $('#submission_feedback2').html("Failed!<br>" + resObj["message"]);
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
                    $('#user_address1').val("");
                    $('#user_postalcode1').val("");

                    $(`#myaddresstable > #myaddressdata > #address_${payload['id']}`).remove();

                    setTimeout(clearFeedback2, 3000);
                } else {
                    $('#submission_feedback2').removeAttr('hidden');
                    $('#submission_feedback2').html("Failed!<br>" + resObj["message"]);
                    setTimeout(clearFeedback2, 3000);
                }
            }
        });
    }

});