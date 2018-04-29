<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_GET['title'] ?? '') {
        $title = $_GET['title'];
        require_once '../Classes/Animal.php';
        $animal = Animal::Find($title);
########______ПОИСКОВАЯ____VIEW______######
print <<<ANIMAL
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Животные</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class='container'>
            <div class="functional-container row">
                <button class="btn btn-success col-1" id="btn-open-container">Добавить</button>
                <button class="btn btn-secondary offset-sm-1" id="btn-open-container">На главную</button>
                <form method="GET" class="form-inline col">
                    <div class="form group offset-sm-4">
                        <input type="text" class="form-control" placeholder="Введите название" id="find-input" value="{$title}">
                        <button class="btn btn-primary" type="button" id="btn-find">Найти</button>
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
                        <div class='row'>
                            <div class='col'>
                                <label for="col-form-label">Минимальная цена</label>
                                <input type="text" class="form-control" id="min-price">
                            </div>
                            <div class='col'>
                                <label for="col-form-label">Максимальная цена</label>
                                <input type="text" class="form-control" id="max-price">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary row" id="btn-send" type="button">Отправить</button>
                </form>
            </div>
            <div class="row">
ANIMAL;
        if ($animal) {
            echo('<table class="table">
                        <thead class="thead-dark">
                            <th class="d-none"></th>
                            <th>Название</th>
                            <th>Фотография</th>
                            <th>Цена</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>');
            for ($i=0; $i < count($animal); $i++) { 
                echo("<tr>
                        <td class='d-none'>{$animal[0]->id}</td>
                        <td>{$animal[0]->Title}</td>
                        <td><img src='data:image/jpg;base64,{$animal[0]->Photo}'></td>
                        <td>{$animal[0]->MinPrice} руб. - {$animal[0]->MaxPrice} руб.</td>
                        <td>
                            <button class='btn btn-warning'>Изменить</button>
                            <button class='btn btn-danger'>Удалить</button>
                        </td>
                    </tr>");
            }
            print'</tbody></table>';
        } else {
            echo("<div>По запросу <i>{$title}</i> ничего не найдено!");
        }
echo("</div>
        </div>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='../Scripts/Admin/animals_scripts.js'></script>
    </body>
</html>");        

    } else {
########______ОСНОВНАЯ___VIEW______######
print <<<ANIMAL
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Животные</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class='container'>
            <div class="functional-container row">
                <button class="btn btn-success col-1" id="btn-open-container">Добавить</button>
                <button class="btn btn-secondary offset-sm-1" id="btn-open-container">На главную</button>
                <form method="GET" class="form-inline col">
                    <div class="form group offset-sm-4">
                        <input type="text" class="form-control" placeholder="Введите название" id="find-input">
                        <button class="btn btn-primary" type="button" id="btn-find">Найти</button>
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
                        <div class='row'>
                            <div class='col'>
                                <label for="col-form-label">Минимальная цена</label>
                                <input type="text" class="form-control" id="min-price">
                            </div>
                            <div class='col'>
                                <label for="col-form-label">Максимальная цена</label>
                                <input type="text" class="form-control" id="max-price">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary row" id="btn-send" type="button">Отправить</button>
                </form>
            </div>
            <div class="row">
ANIMAL;
        require_once '../Classes/Animal.php';
        $animals = Animal::Show();
        if ($animals) {
            echo('<table class="table">
                        <thead class="thead-dark">
                            <th class="d-none"></th>
                            <th>Название</th>
                            <th>Фотография</th>
                            <th>Цена</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>');
            $animalsLen = count($animals);
            for ($i=0; $i < $animalsLen; $i++) { 
                echo("<tr>
                        <td class='d-none'>{$animals[$i]->id}</td>
                        <td>{$animals[$i]->Title}</td>
                        <td><img src='data:image/jpg;base64,{$animals[$i]->Photo}'></td>
                        <td>{$animals[$i]->MinPrice} руб. - {$animals[$i]->MaxPrice} руб.</td>
                        <td>
                            <button class='btn btn-warning'>Изменить</button>
                            <button class='btn btn-danger'>Удалить</button>
                        </td>
                    </tr>");
            }
            print'</tbody></table>';
        } else {
            echo('<div>Вы не создали ни одного животного!</div>');
        }
        


echo("</div>
        </div>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='../Scripts/Admin/animals_scripts.js'></script>
    </body>
</html>");
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if ($_POST['animal'] ?? '') {
        $animal = json_decode($_POST['animal']);
        if ($_FILES['photo'] ?? '') {
            $photo = $_FILES['photo'];
            require_once '../Classes/Animal.php';
            if (Animal::Validate($animal, $photo)) {
                $animal = new Animal($animal, $photo);
                $animal->Create($animal);
            }
        } else {
            echo('Вы не загрузили фотографию!');
        }
    }
    elseif ($_POST['id'] ?? '') {
        require_once '../Classes/Animal.php';
        $id = $_POST['id'];
        Animal::Delete($id);
    }
    
    
}
            