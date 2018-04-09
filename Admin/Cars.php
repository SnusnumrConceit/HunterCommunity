<?php
 if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['title'] ?? '') {
            require_once '../Classes/Car.php';
            $title = $_GET['title'];
            $car = new Car();
            $cars = $car->Find($title);

######_____________ПОИСКОВАЯ_____VIEW___________########
print <<<POST
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Автомобили</title>
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
                        <label for="col-form-label">Количество автомобилей</label>
                        <input type="text" class="form-control" id="count">
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
            if ($cars) {
                echo("<table class='table table-hover'>
                        <thead class='thead-dark'>
                            <th class='d-none'></th>
                            <th>Название</th>
                            <th>Фото</th>
                            <th>Кол-во автомобилей</th>
                            <th>Цена</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>");
                        $carsLength = count($cars);
                        require_once '../WideImage/lib/wideimage.php';
                        for ($i=0; $i < $carsLength; $i++) { 
                            $img = WideImage::load(base64_decode($cars[$i]->Photo));
                            $img = $img->resize(250,180);
                            $img = base64_encode($img);
                            echo("<tr>
                            <td class='d-none'>{$cars[$i]->id}</td>
                            <td>{$cars[$i]->Title}</td>
                            <td><img src='data:image/jpg;base64,{$img}'></td>
                            <td>{$cars[$i]->Count}</td>
                            <td>{$cars[$i]->Price}</td>
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
        <script src='../Scripts/Admin/cars_scripts.js'></script>
    </body>
</html>");

        }
        else {


########______ОСНОВНАЯ___VIEW______######
print <<<POST
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Автомобили</title>
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
                        <label for="col-form-label">Количество автомобилей</label>
                        <input type="text" class="form-control" id="count">
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
            require_once '../Classes/Car.php';
            $car = new Car();
            $cars = $car->Show();

            if ($cars) {
                echo("<table class='table table-hover'>
                        <thead class='thead-dark'>
                            <th class='d-none'></th>
                            <th>Название</th>
                            <th>Фото</th>
                            <th>Количество</th>
                            <th>Цена</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>");
                $carsLength = count($cars);
                require_once '../Wideimage/lib/wideimage.php';
                for ($i=0; $i < $carsLength; $i++) {
                    $img = WideImage::load(base64_decode($cars[$i]->Photo));
                    $img = $img->resize(250, 180);
                    $img = base64_encode($img);
                    echo("<tr>
                            <td class='d-none'>{$cars[$i]->id}</td>
                            <td>{$cars[$i]->Title}</td>
                            <td><img src='data:image/jpg;base64,{$img}'></td>
                            <td>{$cars[$i]->Count}</td>
                            <td>{$cars[$i]->Price}</td>
                            <td>
                                <button class='btn btn-warning'>Изменить</button>
                                <button class='btn btn-danger'>Удалить</button>
                            </td>
                        </tr>");
                }
                echo("</tbody>
                    </table>");
            } else {
                echo("<div>Вы не создали ни одного автомобиля!");
            }
            echo("</div>
        </div>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='../Scripts/Admin/cars_scripts.js'></script>
    </body>
</html>");
        }
 }  elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['car'] ?? '') {
            if ($_FILES['photo'] ?? '') {
                $photo = $_FILES['photo'];
                $inputData = json_decode($_POST['car']);
                require_once '../Classes/Car.php';
                $car = new Car();
                if ($car->Validate($inputData, $photo)) {
                    $car = $car->Set($inputData, $photo);
                    $car->Create($car);
                }
            } else {
                echo('Вы не загрузили фотографию!');
            }
            
        } elseif ($_POST['id'] ?? '') {
            require_once '../Classes/Car.php';
            $car = new Car();
            $id = $_POST['id'];
            $car->Delete($id);
        }
    } else {
        header('location: index.php');
    }
    