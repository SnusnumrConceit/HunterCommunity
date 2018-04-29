$(document).ready(function () {
    var btnDelete = $('.btn-danger'),
        btnEdit = $('.btn-warning'),
        btnFind = $('#btn-find'),
        btnOpen = $('#btn-open-container'),
        btnCreator = $('#btn-send'),
        findForm = $('#find-input'),
        creatorContainer = $('.creator-container'),
        titleForm = $('#title'),
        photoForm = $('#photo'),
        countForm = $('#count'),
        placesForm = $('#places'),
        priceForm = $('#price');
    
    creatorContainer.css('display', 'none');

    btnOpen.click(function () {
        creatorContainer.slideToggle();
    })

    btnDelete.click(function () {
        for (var index = 0; index < btnDelete.length; index++) {
            if (btnDelete[index] == event.target) {
                var position = index +1,
                    house_id = $('tr:nth-child('+position+') .d-none').text();
                $.post('houses.php', {id: house_id}, function (response) {
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
                house_id = $('tr:nth-child('+position+') .d-none').text();
                window.location.href = 'Info/houseinfo.php?house='+ house_id;
          }
          
      }  
    })

    btnFind.click(function(){
        var title = findForm.val();
            try {
                if (title !== undefined && title !== null && title.length !=0) {
                    window.location.href = 'houses.php?title='+title;
                } else {
                    throw new Error('Empty Find Error');
                }
            } catch (error) {
                if (error.message == 'Empty Find Error') {
                    findForm.addClass('is-invalid');
                    findForm.prop('placeholder', 'Вы не ввели название');
                }
                else {
                    alert(error.message);
                }
            }
    })

    btnCreator.click(function () {  
        var title = titleForm.val(),
            photo = photoForm.prop('files')[0],
            count = countForm.val(),
            places = placesForm.val(),
            price = priceForm.val(),
            formData = new FormData();

            if (ValidateTitle(title) && ValidatePhoto(photo) && ValidateCount(count)
                && ValidatePlaces(places) && ValidatePrice(price)) {
                var house = new House(title, count, places, price);
                    formData.append('photo', photo);
                    house = JSON.stringify(house);
                    formData.append('house', house);
                
                    $.ajax({
                        type: 'POST',
                        data: formData,
                        url: 'houses.php',
                        contentType: false,
                        processData: false,
                        success: function (response) {  
                            if (response.length != 0) {
                                alert(response);
                            } else {
                                window.location.reload();
                            }
                        }
                    })
                    
                

                function House (title, count, places, price) {
                    this.title = title,
                    this.count = count,
                    this.places = places,
                    this.price = price
                }
            }
    })

    function ValidateTitle(title) {
        try {
            if (title !== null && title !== undefined && title.length != 0) {
                if (title.length >= 4 && title.length <=50) {
                    if (/([А-ЯЁ][а-яё ])+/.exec(title) !== null) {
                        //console.log(/([А-ЯЁ][а-яё ]+)/.exec(title)[0]);
                        if (/([А-ЯЁ][а-яё ]+)/.exec(title)[0] == title) {
                            return true;
                        } else {
                            throw new Error('Uncorrect Title Error');    
                        }
                    } else {
                        throw new Error('Uncorrect Title Error');    
                    }
                } else {
                    throw new Error('Length Title Error');
                }
            } else {
                throw new Error('Empty Title Error');
            }
        } catch (error) {
            if (error.message === 'Empty Title Error') {
                alert('Вы не ввели название!');
            }
            
            if (error.message === 'Length Title Error') {
                alert('Название должно быть от 4 до 50 символов!');
            }
            
            if (error.message === 'Uncorrect Title Error') {
                alert('Название должно состоять из букв русского алфавита!');
            }
        }
    }

    function ValidateCount(count) {
        try {
            if (count !== null && count !== undefined && count.length != 0) {
                if (!isNaN(count)) {
                    if (count > 0 && count <= 10) {
                        return true;
                    } else {
                        throw new Error('Length Count Error');
                    }
                } else {
                    throw new Error('Uncorrect Count Error');    
                }
            } else {
                throw new Error('Empty Count Error');
            }
        } catch (error) {
            if (error.message === 'Empty Count Error') {
                alert('Вы не ввели кол-во домов!');
            }
            
            if (error.message === 'Length Count Error') {
                alert('Количество домов не должно превышать 10!');
            }
            
            if (error.message === 'Uncorrect Count Error') {
                alert('Количество домов должно состоять из цифр!');
            }
        }
    }

    function ValidatePlaces(places) {
        try {
            if (places !== null && places !== undefined && places.length != 0) {
                if (!isNaN(places)) {
                    if (places > 0 && places <= 30) {
                        return true;
                    } else {
                        throw new Error('Length Places Error');
                    }
                } else {
                    throw new Error('Uncorrect Places Error');    
                }
            } else {
                throw new Error('Empty Places Error');
            }
        } catch (error) {
            if (error.message === 'Empty Places Error') {
                alert('Вы не ввели кол-во мест!');
            }
            
            if (error.message === 'Length Places Error') {
                alert('Количество мест не должно превышать 30!');
            }
            
            if (error.message === 'Uncorrect Places Error') {
                alert('Количество мест должно состоять из цифр!');
            }
        }
    }

    function ValidatePrice(price) {
        try {
            if (price !== null && price !== undefined && price.length != 0) {
                if (!isNaN(price)) {
                    if (price >= 600 && price <= 2000) {
                        return true;
                    } else {
                        throw new Error('Length Price Error');
                    }
                } else {
                    throw new Error('Uncorrect Price Error');    
                }
            } else {
                throw new Error('Empty Price Error');
            }
        } catch (error) {
            if (error.message === 'Empty Price Error') {
                alert('Вы не ввели количество домов!');
            }
            
            if (error.message === 'Length Price Error') {
                alert('Цена должна быть от 600 до 2000 рублей!');
            }
            
            if (error.message === 'Uncorrect Price Error') {
                alert('Цена должна состоять из цифр!');
            }
        }
    }

    function ValidatePhoto(photo) {
        try {
            if (photo !== undefined && photo !== null && photo.length != 0) {
                return true;
            } else {
                throw new Error('Download Photo Error');
            }
        } catch (error) {
            if (error.message === 'Download Photo Error') {
                alert('Вы не загрузили фотографию!');
            }
        }
    }
})