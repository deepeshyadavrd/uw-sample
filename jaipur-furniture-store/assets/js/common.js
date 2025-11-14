function formRule(formid) {
    var validatebool = 1;
    $('.errorResponse').remove();
    $('[id^=' + formid + '] input,[id^=' + formid + '] textarea').each(function(index) {
        var domelement = $(this);
        var domename = domelement.attr('name');
        var inputVal = domelement.val().trim();
        var radioReview = $('input[name=rate]:checked', '#review_product_form').val();
        var errorResponse = '';
		domelement.css('border','');
        if (domename && domelement.attr('type') != 'hidden') {
            if (domename == 'email' && inputVal == '') {
                errorResponse = 'Please enter email';
            } else if (domename == 'email' && !ve(inputVal)) {
                errorResponse = 'Please enter valid email';
            } else if (domename == 'register_email' && inputVal == '') {
                errorResponse = 'Please enter email';
            } else if (domename == 'register_email' && !ve(inputVal)) {
                errorResponse = 'Please enter valid email';
            } else if ((domename == 'name' || domename == 'firstname' || domename == 'lastname') && inputVal == '' || /[z0-9-!$%^&*()_+|~=`\\#{}\[\]:";'<>?,.\/]/.test($('input[name=name]').val())) {
                errorResponse = 'Please enter valid name';
            } else if ((domename == 'telephone') && inputVal.length < 10) {
                errorResponse = 'Please enter 10 digit mobile';
            } else if ((domename == 'telephone') && !vm(inputVal)) {
                errorResponse = 'Please enter valid mobile no.';
            } else if ((domename == 'mobile') && inputVal.length < 10) {
                errorResponse = 'Please enter 10 digit mobile';
            } else if ((domename == 'mobile') && !vm(inputVal)) {
                errorResponse = 'Please enter valid mobile no.';
            } else if ((inputVal == '' && domename == 'pincode') && inputVal.length < 6) {
                errorResponse = 'Please enter 6 digit pincode';
            } else if ((domename == 'pincode') && !vp(inputVal)) {
                errorResponse = 'Please enter valid pincode';
            } else if (domename == 'password' && inputVal == '') {
                errorResponse = 'Please enter valid password';
            } else if (inputVal != '' && domename == 'password' && inputVal.length < 6) {
                errorResponse = 'Please enter at least 6 characters password';
            } else if (inputVal != '' && domename == 'password' && inputVal.length > 20) {
                errorResponse = 'Please enter between 6 and 20 characters!';
            } else if (domename == 'confirm' && inputVal != $('#password').val()) {
                errorResponse = 'Confirm password does not match';
            } else if (domename == 'city' && inputVal == '') {
                errorResponse = 'Please enter city name';
            } else if (domename == 'country' && inputVal == '') {
                errorResponse = 'Please enter country name';
            } else if (domename == 'rate' && typeof(radioReview) == 'undefined') {
                console.log('kkk')
                errorResponse = 'Please select a start rating';
            } else if (domename == 'title' && inputVal == '') {
                errorResponse = 'Please enter title';
            } else if (domename == 'message' && inputVal == '') {
                errorResponse = 'Please enter message';
            } else if (domename == 'address_1' && inputVal == '') {
                errorResponse = 'Please enter House No';
            } else if (domename == 'address_3' && inputVal == '') {
                errorResponse = 'Please enter Street Address';
            }
        }
        if (errorResponse) {
            validatebool = 0;
			domelement.css('border','1px solid red');
            domelement.after(' <div class="errorResponse" style="height:15px;"><p class="" style="font-size: 12px;color: red;position: absolute;padding: 2px;">' + errorResponse + '</p><div>');
            return false;
        }
    });
    return validatebool;
}
function ve(mail) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
        return (true)
    }
    return (false)
}
function vm(mvalue) {
    var defaultMvalue = 6;
    var mnumber = mvalue.toString()[0];
    if (Number(mnumber) < defaultMvalue || !/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/.test(mvalue)) {
        return 0;
    }
    return 1;
}
function vp(pvalue) {
    if (pvalue.length == 6 && pvalue.match(/^[1-9][0-9]{5}$/)) {
        return 1
    } else {
        return 0;
    }
}