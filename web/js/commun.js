$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

$('.showChildren').on('click', function(e){
    e.preventDefault();

    var parentId = $(this).data('id');

    if ($(this).find('.fa-plus-square').length > 0){
        $('.childrenOf_' + parentId).css('display', 'table-row');

        $(this).find('i').removeClass('fa-plus-square');
        $(this).find('i').addClass('fa-minus-square');
    }
    else{
        $('.childrenOf_' + parentId).css('display', 'none');

        $(this).find('i').removeClass('fa-minus-square');
        $(this).find('i').addClass('fa-plus-square');
    }
});