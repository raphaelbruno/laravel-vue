function init() {
    $('.date-picker').datepicker({
        language: top.LOCALE ? top.LOCALE : 'en',
        format: top.DATE_FORMAT ? top.DATE_FORMAT : 'yyyy/mm/dd',
        autoclose: true
    });
    
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.phone').mask('(00) 0000-0000');
    $('.phone_us').mask('(000) 000-0000');
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('.money').mask("#.##0,00", {reverse: true});
    $('.integer').mask("#.##0", {reverse: true});
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', {reverse: true});

    $('.select2').select2();
    $('.wysiwyg').each(function(index, item){
        var config = {}
        if($(item).data('height')) config.height = $(item).data('height');
        $(item).summernote(config);
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

    $('body').prepend('<div class="ajaxloader"></div>');
    $(document).ajaxStart(loaderShow).ajaxStop(loaderHide);

    $('form').submit(loaderShow);
    $('form.needs-validation').submit(function(event) {
        if (this.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
            loaderHide();
        }
        $(this).addClass('was-validated');
    });
}

function loaderShow(){
    $('body').addClass("loading");
}

function loaderHide(){
    $('body').removeClass("loading");
}

function openDeleteComfirmation(url, item){
    $('#deleteConfirm form').attr('action', url);
    if(item) $('#deleteConfirm form .item').html(' <b>('+item+')</b>');
    $('#deleteConfirm').modal('show');
}



window.addEventListener('load', init);