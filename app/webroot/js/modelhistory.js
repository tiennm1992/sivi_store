jQuery(document).ready(function() {
    // setup date picker
    var pDate = jQuery('input[name="_month"]').length == 1 ? new Date(parseInt(jQuery('input[name="_month"]').val())) : new Date() ;
    jQuery('#order-datepicker').datetimepicker(
        {
            viewMode: 'years',
            format: 'YYYY/MM',
            defaultDate: pDate
        }
    ).on("dp.change",function (e) {
            window.location = '/admin/transactions/index/' + jQuery('input[name="month"]').val();
    });

    if(jQuery('.ec-item').length > 0){
        jQuery('.ec-item').click(function(e) {
            e.preventDefault();
            jQuery(this).parent().find('div').toggleClass( "ec-item-detail" );
        });
    }
});