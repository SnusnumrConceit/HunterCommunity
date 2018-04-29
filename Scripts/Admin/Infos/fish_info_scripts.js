$(document).ready(function (){
    btnSender = $('#btn-send'),
    titleForm = $('#title'),
    minPriceForm = $('#min-price'),
    maxPriceForm = $('#max-price'),
    photoForm = $('#photo');

    btnSender.click(function () {  
    var id = window.location.search.split('='),
        title = titleForm.val(),
        photo = photoForm.prop('files')[0],
        minPrice = minPriceForm.val(),
        maxPrice = maxPriceForm.val(),
        formData = new FormData();

        if (ValidateTitle(title) && ValidatePrice(minPrice, maxPrice)) {
            var fish = new Fish(id, title, minPrice, maxPrice);
                formData.append('photo', photo);
                fish = JSON.stringify(fish);
                formData.append('fish', fish);
            
                $.ajax({
                    type: 'POST',
                    data: formData,
                    url: 'fishinfo.php',
                    contentType: false,
                    processData: false,
                    success: function (response) {  
                        if (response.length != 0) {
                            alert(response);
                        } else {
                            window.location.href = '../fishes.php';
                        }
                    }
                })
                

            

            function Fish (id, title, minPrice, maxPrice) {
                this.id = id[1],
                this.title = title,
                this.minPrice = minPrice,
                this.maxPrice = maxPrice;
            }
        }

    function ValidateTitle(title) {
        try {
            if (title !== null && title !== undefined && title.length != 0) {
                if (title.length >= 2 && title.length <=20) {
                    if (/([А-ЯЁ][а-яё ]+)/.exec(title) !== null) {
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
                alert('Название должно быть от 2 до 20 символов!');
            }
            
            if (error.message === 'Uncorrect Title Error') {
                alert('Название должно состоять из букв русского алфавита!');
            }
        }
    }

    function ValidatePrice(min, max) {
        try {
            if (min !== undefined && min !== null && min.length !== 0) {
                if (!isNaN(min)) {
                    if (min >= 5000) {
                        if (max !== undefined && max !== null && max.length !== 0) {
                            if (!isNaN(max)) {
                                if (max <= 80000) {
                                    if (parseInt(min) < parseInt(max)) {
                                        return true;
                                    } else {
                                        throw new Error('Wrong Prices Error');
                                    }
                                } else {
                                    throw new Error('Length Max Price Error');
                                }
                            } else {
                                throw new Error('Uncorrect Max Price Error');
                            }
                        } else {
                            throw new Error('Empty Max Price Error');
                        }
                    } else {
                        throw new Error('Length Min Price Error');
                    }
                } else {
                    throw new Error('Uncorrect Min Price Error');
                }
            } else {
                throw new Error('Empty Min Price Error');
            }
        } catch (error) {
            if (error.message === 'Empty Min Price Error') {
                alert('Вы не ввели минимальную цену!');
            }
            if (error.message === 'Length Min Price Error') {
                alert('Размер минимальной цены не должен быть ниже 5000 рублей!');
            }
            if (error.message === 'Uncorrect Min Price Error') {
                alert('Минимальная цена должна состоять из цифр!');
            }
            if (error.message === 'Empty Max Price Error') {
                alert('Вы не ввели максимальную цену!');
            }
            if (error.message === 'Length Max Price Error') {
                alert('Размер максимальной цены не должен превышать 80000 рублей!');
            }
            if (error.message === 'Uncorrect Max Price Error') {
                alert('Максимальная цена должна состоять из цифр!');
            }
            if (error.message = 'Wrong Prices Error') {
                alert('Минимальная цена не может быть больше максимальной!');
            }
        }
    }
})

})