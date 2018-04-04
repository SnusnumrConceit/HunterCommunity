$(document).ready(function () { 
    var loginForm = $('#login'),
        commentForm = $('#comment');

    loginForm.blur(function () {  
        loginForm.siblings().remove('.invalid-feedback');
        loginForm.siblings().remove('.valid-feedback');
        if (loginForm.hasClass('is-valid')) {
            loginForm.removeClass('is-valid');
        } else if(loginForm.hasClass('is-invalid')){
            loginForm.removeClass('is-invalid');
        }
        var login = loginForm.val();
        ValidateLogin(login);
    })

    commentForm.blur(function () {  
        commentForm.siblings().remove('.invalid-feedback');
        commentForm.siblings().remove('.valid-feedback');
        if (commentForm.hasClass('is-valid')) {
            commentForm.removeClass('is-valid');
        } else if(commentForm.hasClass('is-invalid')){
            commentForm.removeClass('is-invalid');
        }
        var comment = commentForm.val();
        ValidateComment(comment);
    })


    $('#btn-send').click(function () {  
        var login = loginForm.val(),
            comment = commentForm.val();

            if (ValidateLogin(login) && ValidateComment(comment)) {
                var post = new Post(login, comment);
                post = JSON.stringify(post);
                $.post('posts.php',{post:post}, function (response) {  
                    if (response.length != 0) {
                        $('.invalid-feedback').remove();
                        if (commentForm.hasClass('is-valid') && loginForm.hasClass('is-valid')) {
                            loginForm.addClass('is-invalid');
                            commentForm.addClass('is-invalid');    
                        }
                        try {
                            errors = JSON.parse(response);
                            if (errors.login !== null && errors.login !== undefined && errors.login != 0) {
                                loginForm.after('<div class="invalid-feedback">'+errors.login+'</div>'); 
                            }
                            if (errors.comment !== null && errors.comment !== undefined && errors.comment != 0) {
                                commentForm.after('<div class="invalid-feedback">'+errors.comment+'</div>');
                            }
                        } catch (error) {
                            commentForm.after('<div class="invalid-feedback">'+response+'</div>');
                        }
                        
                    } else {
                        window.location.reload();
                    }
                })

                function Post(login, comment) {
                    this.login = login,
                    this.comment = comment;
                }


            }

    })    
    
        function ValidateComment(comment) {
            try {
                if (comment !== null && comment !== undefined && comment.length != 0) {
                    if (comment.length <= 1000) {
                        if (/([а-яА-Я0-9,.!?:\- ])+/.exec(comment) !== null) {
                            if (/([а-яА-Я0-9,.!?:\- ])+/.exec(comment)[0] === comment){
                                commentForm.addClass('is-valid');
                                return true;
                            } else {
                                throw new Error('Wrong Comment Error');
                            }
                        } else {
                            throw new Error('Wrong Comment Error');
                        }
                    } else {
                        throw new Error('Length Comment Error');
                    }
                } else {
                    throw new Error('Empty Comment Error');
                }
            } catch (error) {
                commentForm.siblings().remove('.invalid-feedback');
                if (error.message === 'Empty Comment Error') {
                    commentForm.addClass('is-invalid');
                    commentForm.after('<div class="invalid-feedback">Вы не ввели комментарий!</div>');
                }
                if (error.message === 'Length Comment Error') {
                    commentForm.addClass('is-invalid');
                    commentForm.after('<div class="invalid-feedback">Комментарий не может содержать более 1000 символов!</div>');
                }
                if (error.message === 'Wrong Comment Error') {
                    commentForm.addClass('is-invalid');
                    commentForm.after('<div class="invalid-feedback">Ваш комментарий содержит латинские символы и запрещённые знаки! Пожалуйста, используйте в своём комментарии буквы кириллицы и следующие знаки препинания: <ul><li>точка</li><li>тире</li><li>восклицательный знак</li><li>вопросительный знак</li><li>запятая</li>.</div>');
                }
            }
        }
    

    function ValidateLogin(login) {
        try {
            if (login !== undefined && login !== null && login.length !== 0) {
                if (login.length >= 6 && login.length <= 24) {
                    if (/[A-Za-z][a-zA-Z0-9_.]{5,}/.exec(login) !== null) {
                        if (/[A-Za-z][a-zA-Z0-9_.]{5,}/.exec(login)[0] === login) {
                            loginForm.addClass('is-valid');
                            return true;
                        } else {
                            throw new Error('Wrong Login Error');    
                        }
                    } else {
                        throw new Error('Wrong Login Error');
                    }
                } else {
                    throw new Error('Length Login Error');
                }
            } else {
                throw new Error('Empty Login Error');
            }
        } catch (error) {
            loginForm.siblings().remove('.invalid-feedback');
            if (error.message === 'Empty Login Error') {
                loginForm.addClass('is-invalid');
                loginForm.after('<div class="invalid-feedback">Вы не ввели логин!</div>');
            }
            if (error.message === 'Length Login Error') {
                loginForm.addClass('is-invalid');
                loginForm.after('<div class="invalid-feedback">Длина логина должна быть от 6 до 24 символов!</div>');
            }
            if (error.message === 'Wrong Login Error') {
                loginForm.addClass('is-invalid');
                loginForm.after('<div class="invalid-feedback">Логин должен состоять из латинских букв, цифр, точки и нижнего подчёркивания!</div>');
            }
        }
    }
})