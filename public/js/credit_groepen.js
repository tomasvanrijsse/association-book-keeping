$("#transactions li").draggable({
    revert: "invalid",
    containment : '#dragcontainment',
    cursor:'move'
});

$('#budgetten li').droppable({
    hoverClass: "accept",
    tolerance : 'pointer',
    drop:function(event,ui){
        var $post = $(this);
        ui.draggable.fadeOut(function(){
            $(this).remove();
            // pajinate refresh/update call is needed
            // zonder is de lijst fucked na wisselen van pagina's
            //$('#transactions li:visible').last().next().fadeIn();
        });
        $.ajax({
            url:'/credit/transactieGroep',
            data:{
                tid:ui.draggable.data('id'),
                gid:$post.data('id')
            },
            success:function(data){
                $post.find('span.saldo').html(data);
                var $cur = $('#budgetten .current');
                $cur.data('saldo',($cur.data('saldo')-ui.draggable.data('bedrag')));
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
    $('#budgetten').parent().pajinate({
        items_per_page : 10,
        show_first_last: false,
        item_container_id:'#budgetten',
        abort_on_small_lists: true
    });
}); 
