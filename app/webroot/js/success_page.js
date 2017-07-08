$("#agreement_check").change(function() {
    if($(this).is(':checked')){
    	$("#login-submit").removeAttr('disabled');
    }else{
    	$("#login-submit").attr('disabled', 'disabled');
    }
    $(this).removeClass('checkbox-error');
});
