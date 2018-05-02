$('.navbar-nav li a').click(function () {  
    $('#main').empty().append('<div class="offset-md-5"><img src="Media/Images/ajax_loader.gif"></div>');
    $('.navbar-nav li').removeClass('active');
    $(this).parent().addClass('active');

    $.ajax({url: this.href, success: function (html) {  
        $('#main').empty().append(html);
    } })
    return false;
})