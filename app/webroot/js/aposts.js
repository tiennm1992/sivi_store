// setup date picker
$('#datetimepicker1').datetimepicker(
    {
        format: 'YYYY-MM-DD'

    }
);
$('#datetimepicker2').datetimepicker(
    {
        format: 'YYYY-MM-DD'
    }
);

function resetForm($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
        .removeAttr('checked').removeAttr('selected');
}
$("#reset").click(function() {
    resetForm($('#form_site'));
    return false;
});