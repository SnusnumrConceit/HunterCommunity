$(document).ready(function () {
    var btnDelete = $('.btn-danger'),
        btnEdit = $('.btn-warning'),
        btnFind = $('#btn-find'),
        btnOpen = $('#btn-open-container'),
        btnCreator = $('#btn-send'),
        findForm = $('#find-input'),
        creatorContainer = $('.creator-container'),
        titleForm = $('#title'),
        newsForm = $('#news');
    
    creatorContainer.css('display', 'none');

    btnOpen.click(function () {
        creatorContainer.slideToggle();
    })

    btnDelete.click(function () {
        for (var index = 0; index < btnDelete.length; index++) {
            if (btnDelete[index] == event.target) {
                var position = index +1,
                    new_id = $('tr:nth-child('+position+') .d-none').text();
                $.post('news.php', {id: new_id}, function (response) {
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
                new_id = $('tr:nth-child('+position+') .d-none').text();
                window.location.href = 'Info/newinfo.php?new='+ new_id;
          }
          
      }  
    })

    btnFind.click(function(){
        var title = findForm.val();
            try {
                if (title !== undefined && title !== null && title.length !=0) {
                    window.location.href = 'news.php?title='+title;
                } else {
                    throw new Error('Empty Find Error');
                }
            } catch (error) {
                if (error.message == 'Empty Find Error') {
                    findForm.addClass('is-invalid');
                    findForm.prop('placeholder', 'Вы не ввели заголовок');
                }
                else {
                    alert(error.message);
                }
            }
    })
    
    titleForm.blur(function () {  
        titleForm.siblings().remove('.invalid-feedback');
        titleForm.siblings().remove('.valid-feedback');
        if (titleForm.hasClass('is-valid')) {
            titleForm.removeClass('is-valid');
        } else if(titleForm.hasClass('is-invalid')){
            titleForm.removeClass('is-invalid');
        }
        var title = titleForm.val();
        ValidateTitle(title);
    })

    newsForm.blur(function () {  
        newsForm.siblings().remove('.invalid-feedback');
        newsForm.siblings().remove('.valid-feedback');
        if (newsForm.hasClass('is-valid')) {
            newsForm.removeClass('is-valid');
        } else if(newsForm.hasClass('is-invalid')){
            newsForm.removeClass('is-invalid');
        }
        var news = newsForm.val();
        ValidateNew(news);
    })


    $('#btn-send').click(function () {  
        var title = titleForm.val(),
            news = newsForm.val();

            if (ValidateTitle(title) && ValidateNew(news)) {
                var news = new News(title, news);
                news = JSON.stringify(news);
                $.post('news.php',{news:news}, function (response) {  
                    if (response.length != 0) {
                        $('.invalid-feedback').remove();
                        if (newsForm.hasClass('is-valid') && titleForm.hasClass('is-valid')) {
                            titleForm.addClass('is-invalid');
                            newsForm.addClass('is-invalid');    
                        }
                        try {
                            errors = JSON.parse(response);
                            if (errors.title !== null && errors.title !== undefined && errors.title != 0) {
                                titleForm.after('<div class="invalid-feedback">'+errors.title+'</div>'); 
                            }
                            if (errors.news !== null && errors.news !== undefined && errors.news != 0) {
                                newsForm.after('<div class="invalid-feedback">'+errors.news+'</div>');
                            }
                        } catch (error) {
                            newsForm.after('<div class="invalid-feedback">'+response+'</div>');
                        }
                        
                    } else {
                        window.location.reload();
                    }
                })

                function News(title, news) {
                    this.title = title,
                    this.news = news;
                }


            }

    })    
    
        function ValidateNew(news) {
            try {
                if (news !== null && news !== undefined && news.length != 0) {
                    if (news.length <= 500) {
                        if (/([а-яёА-ЯЁ0-9,.!?:\- ])+/.exec(news) !== null) {
                            if (/([а-яёА-ЯЁ0-9,.!?:\- ])+/.exec(news)[0] === news){
                                newsForm.addClass('is-valid');
                                return true;
                            } else {
                                throw new Error('Wrong New Error');
                            }
                        } else {
                            throw new Error('Wrong New Error');
                        }
                    } else {
                        throw new Error('Length New Error');
                    }
                } else {
                    throw new Error('Empty New Error');
                }
            } catch (error) {
                newsForm.siblings().remove('.invalid-feedback');
                if (error.message === 'Empty New Error') {
                    newsForm.addClass('is-invalid');
                    newsForm.after('<div class="invalid-feedback">Вы не ввели текст новости!</div>');
                }
                if (error.message === 'Length New Error') {
                    newsForm.addClass('is-invalid');
                    newsForm.after('<div class="invalid-feedback">Текст новости не может содержать более 500 символов!</div>');
                }
                if (error.message === 'Wrong New Error') {
                    newsForm.addClass('is-invalid');
                    newsForm.after('<div class="invalid-feedback">Ваш текст новости содержит латинские символы и запрещённые знаки! Пожалуйста, используйте в своём комментарии буквы кириллицы и следующие знаки препинания: <ul><li>точка</li><li>тире</li><li>восклицательный знак</li><li>вопросительный знак</li><li>запятая</li></div>');
                }
            }
        }
    

    function ValidateTitle(title) {
        try {
            if (title !== undefined && title !== null && title.length !== 0) {
                if (title.length >= 6 && title.length <= 50) {
                    if (/([а-яёА-ЯЁ0-9,.!?:\- ])+/.exec(title) !== null) {
                        if (/([а-яёА-ЯЁ0-9,.!?:\- ])+/.exec(title)[0] === title) {
                            titleForm.addClass('is-valid');
                            return true;
                        } else {
                            throw new Error('Wrong Title Error');    
                        }
                    } else {
                        throw new Error('Wrong Title Error');
                    }
                } else {
                    throw new Error('Length Title Error');
                }
            } else {
                throw new Error('Empty Title Error');
            }
        } catch (error) {
            titleForm.siblings().remove('.invalid-feedback');
            if (error.message === 'Empty Title Error') {
                titleForm.addClass('is-invalid');
                titleForm.after('<div class="invalid-feedback">Вы не ввели заголовок!</div>');
            }
            if (error.message === 'Length Title Error') {
                titleForm.addClass('is-invalid');
                titleForm.after('<div class="invalid-feedback">Длина заголовка должна быть от 6 до 50 символов!</div>');
            }
            if (error.message === 'Wrong Title Error') {
                titleForm.addClass('is-invalid');
                titleForm.after('<div class="invalid-feedback">Ваш заголовок содержит латинские символы и запрещённые знаки! Пожалуйста, используйте в своём комментарии буквы кириллицы и следующие знаки препинания: <ul><li>точка</li><li>тире</li><li>восклицательный знак</li><li>вопросительный знак</li><li>запятая</li>.</div>');
            }
        }
    }
})
