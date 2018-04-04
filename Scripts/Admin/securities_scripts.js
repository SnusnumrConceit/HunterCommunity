$(document).ready(function () {
    var btnDelete = $('.btn-danger'),
        btnEdit = $('.btn-warning'),
        btnFind = $('#btn-find'),
        btnOpen = $('#btn-open-container'),
        btnCreator = $('#btn-send'),
        findForm = $('#find-input'),
        creatorContainer = $('.creator-container'),
        titleForm = $('#title'),
        countForm = $('#count');
    
    creatorContainer.css('display', 'none');

    btnOpen.click(function () {
        creatorContainer.slideToggle();
    })

    btnDelete.click(function () {
        for (var index = 0; index < btnDelete.length; index++) {
            if (btnDelete[index] == event.target) {
                var position = index +1,
                    security_id = $('tr:nth-child('+position+') .d-none').text();
                $.post('securities.php', {id: security_id}, function (response) {
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
                security_id = $('tr:nth-child('+position+') .d-none').text();
                window.location.href = 'Info/newinfo.php?new='+ security_id;
          }
          
      }  
    })

    btnFind.click(function(){
        var title = findForm.val();
            try {
                if (title !== undefined && title !== null && title.length !=0) {
                    window.location.href = 'securities.php?title='+title;
                } else {
                    throw new Error('Empty Find Error');
                }
            } catch (error) {
                if (error.message == 'Empty Find Error') {
                    findForm.addClass('is-invalid');
                    findForm.prop('placeholder', 'Вы не ввели название местности');
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

    countForm.blur(function () {  
        countForm.siblings().remove('.invalid-feedback');
        countForm.siblings().remove('.valid-feedback');
        if (countForm.hasClass('is-valid')) {
            countForm.removeClass('is-valid');
        } else if(countForm.hasClass('is-invalid')){
            countForm.removeClass('is-invalid');
        }
        var count = countForm.val();
        ValidateCount(count);
    })


    $('#btn-send').click(function () {  
        var title = titleForm.val(),
            count = countForm.val();

            if (ValidateTitle(title) && ValidateCount(count)) {
                var security = new Security(title, count);
                
                security = JSON.stringify(security);
                $.post('securities.php',{security:security}, function (response) {  
                    if (response.length != 0) {
                        $('.invalid-feedback').remove();
                        if (countForm.hasClass('is-valid') && titleForm.hasClass('is-valid')) {
                            titleForm.addClass('is-invalid');
                            countForm.addClass('is-invalid');    
                        }
                        try {
                            errors = JSON.parse(response);
                            if (errors.title !== null && errors.title !== undefined && errors.title != 0) {
                                titleForm.after('<div class="invalid-feedback">'+errors.title+'</div>'); 
                            }
                            if (errors.count !== null && errors.count !== undefined && errors.count != 0) {
                                countForm.after('<div class="invalid-feedback">'+errors.count+'</div>');
                            }
                        } catch (error) {
                            countForm.after('<div class="invalid-feedback">'+response+'</div>');
                        }
                        
                    } else {
                        window.location.reload();
                    }
                })

                function Security(title, count) {
                    this.title = title,
                    this.count = count;
                }


            }

    })    
    
    function ValidateTitle(title) {
        try {
            if (title !== undefined && title !== null && title.length !== 0) {
                if (title.length >= 6 && title.length <= 30) {
                    if (/([а-яёА-ЯЁ-])+/.exec(title) !== null) {
                        if (/([а-яёА-ЯЁ-])+/.exec(title)[0] === title) {
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
                titleForm.after('<div class="invalid-feedback">Вы не ввели название местности!</div>');
            }
            if (error.message === 'Length Title Error') {
                titleForm.addClass('is-invalid');
                titleForm.after('<div class="invalid-feedback">Длина названия местности должна быть от 6 до 30 символов!</div>');
            }
            if (error.message === 'Wrong Title Error') {
                titleForm.addClass('is-invalid');
                titleForm.after('<div class="invalid-feedback">Ваше название местности должно состоять из киррилицы!</div>');
            }
        }
    }

        function ValidateCount(count) {
            try {
                if (count !== null && count !== undefined && count.length != 0) {
                    if (!isNaN(count)) {
                        if (count > 0 && count <= 30) {
                            countForm.addClass('is-valid');
                            return true;
                        } else {
                            throw new Error('Length Count Error');
                        }
                    } else {
                        throw new Error('Wrong Count Error');
                    }
                } else {
                    throw new Error('Empty Count Error');
                }
            } catch (error) {
                countForm.siblings().remove('.invalid-feedback');
                if (error.message === 'Empty Count Error') {
                    countForm.addClass('is-invalid');
                    countForm.after('<div class="invalid-feedback">Вы не ввели количество охранников!</div>');
                }
                if (error.message === 'Length Count Error') {
                    countForm.addClass('is-invalid');
                    countForm.after('<div class="invalid-feedback">Число охранников на местность не может превышать 30 человек!</div>');
                }
                if (error.message === 'Wrong Count Error') {
                    countForm.addClass('is-invalid');
                    countForm.after('<div class="invalid-feedback">Количество охранников должно состоять из цифр!</div>');
                }
            }
        }
})
