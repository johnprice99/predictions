$('.deleteButton').bind('click', function() {
    return confirm($(this).data('msg'))
})
