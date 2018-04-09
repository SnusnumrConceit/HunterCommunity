$(document).ready(function (){
    btnSender = $('#btn-send'),
    titleForm = $('#title'),
    countForm = $('#count'),
    priceForm = $('#price'),
    photoForm = $('#photo');

    btnSender.click(function () {  
    var id = window.location.search.split('='),
        title = titleForm.val(),
        photo = photoForm.prop('files')[0],
        count = countForm.val(),
        price = priceForm.val(),
        formData = new FormData();

        if (ValidateTitle(title) && ValidatePhoto(photo) && ValidateCount(count)
            && ValidatePrice(price)) {
            var car = new Car(id, title, count, price);
                formData.append('photo', photo);
                car = JSON.stringify(car);
                formData.append('car', car);
            
                $.ajax({
                    type: 'POST',
                    data: formData,
                    url: 'carinfo.php',
                    contentType: false,
                    processData: false,
                    success: function (response) {  
                        if (response.length != 0) {
                            alert(response);
                            console.log(response);
                        } else {
                            window.location.href = '../cars.php';
                        }
                    }
                })
                
            

            function Car (id, title, count, price) {
                this.id = id[1],
                this.title = title,
                this.count = count,
                this.price = price
            }
        }


function ValidateTitle(title) {
    try {
        if (title !== null && title !== undefined && title.length != 0) {
            if (title.length >= 4 && title.length <=50) {
                if (/([А-ЯЁ][а-яё ])+/.exec(title) !== null) {
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
    if (photo !== undefined && photo !== null && photo.length != 0) {
            return true;
        } else {
            photo = null;
            return true;
        }

}
})

})