$(document).ready(function () {
    var btnDelete = $('.btn-danger'),
        btnOpen = $('#btn-open-container'),
        btnCreator = $('#btn-send'),
        creatorContainer = $('.creator-container'),
        photoForm = $('#photo');

        creatorContainer.css('display', 'none');

    btnOpen.click(function () {
        creatorContainer.slideToggle();
    })

    btnDelete.click(function () {
        for (var index = 0; index < btnDelete.length; index++) {
            if (btnDelete[index] == event.target) {
                var position = index +1,
                    photo_id = $(this).parent().siblings('.d-none').text();
                $.post('gallery.php', {id: photo_id}, function (response) {
                    if (response.length != 0) {
                        alert(response);
                    } else {
                        window.location.reload();
                    }
                })

            }
            
        }
    })

    btnCreator.click(function () {  
        var formData = new FormData();
            formData.append('photo', photoForm.prop('files')[0]);
        $.ajax({
            type: 'POST',
            url: 'Gallery.php',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {  
                if (response.length != 0) {
                    alert(response);
                } else {
                    window.location.reload();
                }
            },
            error: function (response) {  
                alert(response);
            }
        })
    })
})