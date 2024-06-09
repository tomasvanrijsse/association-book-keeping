/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy'
    });

    $('button.btn-form').click(function(){
        $(this).next().fadeToggle();
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
})

function _remove(el){
    if(confirm('Weet je het zeker?')){
        $.get($(el).data('url'),function(response){
            if(response == 'success'){
                $(el).parents('tr').remove();
            } else {
                console.warn('er ging iets mis');
            }
        });
    }
}
