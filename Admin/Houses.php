<?php
 if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['title'] ?? '') {
            require_once '../Classes/House.php';
            $title = $_GET['title'];
            $house = new House();
            $houses = $house->Find($title);

######_____________ПОИСКОВАЯ_____VIEW___________########
print <<<POST
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Жилища</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="functional-container row">
                <button class="btn btn-success col-1" id="btn-open-container">Добавить</button>
                <button class="btn btn-secondary offset-sm-1" id="btn-open-container">На главную</button>
                <form method="GET" class="form-inline col" id="find-input">
                    <div class="form group offset-sm-4">
                        <input type="text" class="form-control" placeholder="Введите название" value="{$title}">
                        <button class="btn btn-primary">Найти</button>
                    </div>
                </form>
            </div>
            <div class="creator-container row">
                <form method="POST" class="col">
                    <div class="form-group col-4">
                        <label for="col-form-label">Название</label>
                        <input type="text" class="form-control" id="title">
                    </div>
                    <div class="form-group col-4">
                        <label for="col-form-label">Фотография</label>
                        <input type="file" class="form-control" id="photo">
                    </div>
                    <div class="form-group col-4">
                        <label for="col-form-label">Количество домов</label>
                        <input type="text" class="form-control" id="count">
                    </div>
                    <div class="form-group col-4">
                        <label for="col-form-label">Количество мест</label>
                        <input type="text" class="form-control" id="places">
                    </div>
                    <div class="form-group col-4">
                        <label for="col-form-label">Цена</label>
                        <input type="text" class="form-control" id="price">
                    </div>
                    <button class="btn btn-primary row" id="btn-send" type="button">Отправить</button>
                </form>
            </div>
            <div class="row">
POST;
            if ($houses) {
                echo("<table class='table table-hover'>
                        <thead class='thead-dark'>
                            <th class='d-none'></th>
                            <th>Название</th>
                            <th>Фото</th>
                            <th>Кол-во домов</th>
                            <th>Кол-во мест</th>
                            <th>Цена</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>");
                        $housesLength = count($houses);
                        require_once '../WideImage/lib/wideimage.php';
                        for ($i=0; $i < $housesLength; $i++) { 
                            $img = WideImage::load(base64_decode($houses[$i]->Photo));
                            $img = $img->resize(250,180);
                            $img = base64_encode($img);
                            echo("<tr>
                            <td class='d-none'>{$houses[$i]->id}</td>
                            <td>{$houses[$i]->Title}</td>
                            <td><img src='data:image/jpg;base64,{$img}'></td>
                            <td>{$houses[$i]->Count}</td>
                            <td>{$houses[$i]->Places}</td>
                            <td>{$houses[$i]->Price}</td>
                            <td>
                                <button class='btn btn-warning'>Изменить</button>
                                <button class='btn btn-danger'>Удалить</button>
                            </td>
                        </tr>");
                            
                        }
                        echo("</tbody>
                    </table>");
            } else {
                echo("<div>По запросу <i>{$title}</i> ничего не найдено");
            }
            echo("</div>
        </div>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='../Scripts/Admin/houses_scripts.js'></script>
    </body>
</html>");

        }
        else {


########______ОСНОВНАЯ___VIEW______######
print <<<POST
<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="functional-container row">
                <button class="btn btn-success col-1" id="btn-open-container">Добавить</button>
                <button class="btn btn-secondary offset-sm-1" id="btn-open-container">На главную</button>
                <form method="GET" class="form-inline col">
                    <div class="form group offset-sm-4">
                        <input type="text" class="form-control" placeholder="Введите название" id="find-input">
                        <button class="btn btn-primary" id="btn-find" type="button">Найти</button>
                    </div>
                </form>
            </div>
            <div class="creator-container row">
                <form method="POST" class="col">
                    <div class="form-group col-4">
                        <label for="col-form-label">Название</label>
                        <input type="text" class="form-control" id="title">
                    </div>
                    <div class="form-group col-4">
                        <label for="col-form-label">Фотография</label>
                        <input type="file" class="form-control" id="photo">
                    </div>
                    <div class="form-group col-4">
                        <label for="col-form-label">Количество домов</label>
                        <input type="text" class="form-control" id="count">
                    </div>
                    <div class="form-group col-4">
                        <label for="col-form-label">Количество мест</label>
                        <input type="text" class="form-control" id="places">
                    </div>
                    <div class="form-group col-4">
                        <label for="col-form-label">Цена</label>
                        <input type="text" class="form-control" id="price">
                    </div>
                    <button class="btn btn-primary row" id="btn-send" type="button">Отправить</button>
                </form>
            </div>
            <div class="row">
POST;
            require_once '../Classes/House.php';
            $house = new House();
            $houses = $house->Show();

            if ($houses) {
                echo("<table class='table table-hover'>
                        <thead class='thead-dark'>
                            <th class='d-none'></th>
                            <th>Название</th>
                            <th>Фото</th>
                            <th>Количество</th>
                            <th>Места</th>
                            <th>Цена</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>");
                $housesLength = count($houses);
                require_once '../Wideimage/lib/wideimage.php';
                for ($i=0; $i < $housesLength; $i++) {
                    $img = WideImage::load(base64_decode($houses[$i]->Photo));
                    $img = $img->resize(250, 180);
                    $img = base64_encode($img);
                    echo("<tr>
                            <td class='d-none'>{$houses[$i]->id}</td>
                            <td>{$houses[$i]->Title}</td>
                            <td><img src='data:image/jpg;base64,{$img}'></td>
                            <td>{$houses[$i]->Count}</td>
                            <td>{$houses[$i]->Places}</td>
                            <td>{$houses[$i]->Price}</td>
                            <td>
                                <button class='btn btn-warning'>Изменить</button>
                                <button class='btn btn-danger'>Удалить</button>
                            </td>
                        </tr>");
                }
                echo("</tbody>
                    </table>");
            } else {
                echo("<div>Вы не создали ни одного жилища!");
            }
            echo("</div>
        </div>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='../Scripts/Admin/houses_scripts.js'></script>
    </body>
</html>");
        }
 }  elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['house'] ?? '') {
            if ($_FILES['photo'] ?? '') {
                $photo = $_FILES['photo'];
                $inputData = json_decode($_POST['house']);
                require_once '../Classes/House.php';
                $house = new House();
                if ($house->Validate($inputData, $photo)) {
                    $house = $house->Set($inputData, $photo);
                    $house->Create($house);
                }
            } else {
                echo('Вы не загрузили фотографию!');
            }
            
        } elseif ($_POST['id'] ?? '') {
            require_once '../Classes/House.php';
            $house = new House();
            $id = $_POST['id'];
            $house->Delete($id);
        }
    } else {
        header('location: index.php');
    }
    