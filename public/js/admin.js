function init() {
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.phone').mask('(00) 0000-0000');
    $('.phone_us').mask('(000) 000-0000');
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('.money').mask("#.##0,00", {reverse: true});
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', {reverse: true});
    $('.mask').each(function(){
        $(this).mask($(this).data('mask'), $(this).data());
    });

    $('.select2').select2();
    
    $('form.needs-validation').submit(function(event) {
        if (this.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
    // Fix .input-group rounded corner
    $('.input-group .invalid-feedback, .input-group .valid-feedback').each(function(index, item){
        $(item).prev()
            .addClass('rounded-right')
            .find('*')
            .addClass('rounded-right');
    });
    
    $('.file').change(function(){
        var filename = Object.values(this.files).map(function(item){ return item.name; }).join(', ');
        $(this).closest('.input-group').find('.selected-file').val(filename);
    });
}

function addSubitem(id){
    var row = $($('.subitem .model').html());
    var tbody = $('.subitem table.subitems tbody');
    tbody.append(row);

    if(id) row.find('select option').each(function(){
        if($(this).val() == id)
        $(this).attr('selected', true);
    });
    row.find('select').attr('name', 'subitems[]').select2();

    updateSubitems();
}

function deleteSubitem(button){
    $(button).closest('tr').remove();
    updateSubitems();
}

function updateSubitems(){
    var tbody = $('.subitem table.subitems tbody');
    var rows = tbody.find('tr:not(.no-subitems)');
    tbody.find('.no-subitems').toggle(rows.length < 1);
    $('.subitem .total').text(rows.length);
}

window.addEventListener('load', init);