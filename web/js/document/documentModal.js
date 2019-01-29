$('#documentSaveBtn').on('click', function(){
    var $modal = $(this).parents('.modal');
    $modal.find('form').submit();
});