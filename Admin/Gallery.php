<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

echo('<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Галлерея</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-success" id="btn-open-container">Добавить</button>
            </div>
        </div>
        <div class="creator-container row">
                <form method="post" class="col">
                    <div class="form-group">
                        <label for="photo" class="col-form-label">Фотография</label>
                        <input type="file" id="photo" class="form-control col-4">
                    </div>
                    <button class="btn btn-success" type="button" id="btn-send">Отправить</button>
                </form>
        </div>
        <div class="row">');
                require_once '../Classes/Photo.php';
                $photo = new Photo();
                $photos = $photo->Show();
                if ($photos) {
                    $photosCount = count($photos);
                    require_once '../Wideimage/lib/WideImage.php';
                    for ($i=0; $i < $photosCount; $i++) {
                        $img = base64_decode($photos[$i]->Photo);
                        $img = WideImage::load($img);
                        $img = $img->resize(250, 180);
                        $img = base64_encode($img);
                        echo("<div class='card offset-sm-4'>
                        <div class='d-none'>{$photos[$i]->id}</div>
                        <img class='card-img-top' src='data:image/jpg;base64,{$img}' alt='Card image cap'>
                        <div class='card-body'>
                            <button type='button' class='btn btn-danger' type='button'>Удалить</button>
                        </div>
                    </div>");
                    }
                } else {
                    echo('Вы не загрузили ни одной фотографии!');
                }
    echo('</div>
        </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../Scripts/Admin/gallery_scripts.js"></script>
</body>
</html>');
}

elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if ($_FILES['photo'] ?? '') {
        $inputPhoto = $_FILES['photo'];
        require_once '../Classes/Photo.php';
        $photo = new Photo();
        if ($photo->Validate($inputPhoto)) {
            $photo = $photo->Set($inputPhoto);
            $photo->Create($photo);
        }
    }  elseif ($_POST['id'] ?? '') {
        $id = $_POST['id'];
        require_once '../Classes/Photo.php';
        $photo = new Photo();
        $photo->Delete($id);
    }
}

?>