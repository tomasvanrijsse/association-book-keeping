$("#transactions li").draggable({
    revert: "invalid",
    containment : 'div.row',
    cursor:'move'
});

$('#budgetten li').droppable({
    hoverClass: "accept",
    tolerance : 'pointer',
    drop:function(event,ui){
        var $budget = $(this);
        ui.draggable.fadeOut(function(){
            $(this).remove();
        });
        $.ajax({
            url:'/debet/transactieBudget',
            data:{
                tid:ui.draggable.data('id'),
                bid:$budget.data('id')
            },
            success:function(data){
                $budget.find('span.saldo').html(data);
                var $cur = $('#budgetten .current');
                $cur.data('saldo',($cur.data('saldo')+ui.draggable.data('bedrag')));
                $cur.find('span.saldo').html('€ '+$cur.data('saldo'));
            },
            method:'POST'
        });
    }
});

$(document).ready(function(){
    $('#transactions').parent().pajinate({
        items_per_page : 10,
        show_first_last: false,
        item_container_id:'#transactions',
        abort_on_small_lists: true
    });
}); 