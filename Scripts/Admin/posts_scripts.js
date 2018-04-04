$(document).ready(function () {
    var btnDelete = $('.btn-danger'),
        btnEdit = $('.btn-warning'),
        btnFind = $('#btn-find'),
        btnOpen = $('#btn-open-container'),
        btnCreator = $('#btn-send'),
        findForm = $('#find-input'),
        creatorContainer = $('.creator-container');
    
    creatorContainer.css('display', 'none');

    btnOpen.click(function () {
        creatorContainer.slideToggle();
    })

    btnDelete.click(function () {
        for (var index = 0; index < btnDelete.length; index++) {
            if (btnDelete[index] == event.target) {
                var position = index +1,
                    post_id = $('tr:nth-child('+position+') .d-none').text();
                $.post('posts.php', {id: post_id}, function (response) {
                    if (response.length != 0) {
                        alert(response);
                    } else {
                        window.location.reload();
                    }
                })

            }
            
        }
    })

    btnEdit.click(function () {
      for (var index = 0; index < btnEdit.length; index++) {
          if(btnEdit[index] == event.target) {
            var position = index +1,
                post_id = $('tr:nth-child('+position+') .d-none').text();
                window.location.href = 'Info/postinfo.php?post='+ post_id;
          }
          
      }  
    })

    btnFind.click(function(){
        var login = findForm.val();
            try {
                if (login !== undefined && login !== null && login.length !=0) {
                    window.location.href = 'posts.php?login='+login;
                } else {
                    throw new Error('Empty Find Error');
                }
            } catch (error) {
                if (error.message == 'Empty Find Error') {
                    findForm.addClass('is-invalid');
                    findForm.prop('placeholder', 'Вы не ввели логин');
                }
                else {
                    alert(error.message);
                }
            }
    })
})