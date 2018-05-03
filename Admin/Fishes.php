<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_GET['title'] ?? '') {
        $title = $_GET['title'];
        require_once '../Classes/Fish.php';
        $fish = Fish::Find($title);
########______ПОИСКОВАЯ____VIEW______######
print <<<ANIMAL
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Рыбы</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class='container'>
            <div class="functional-container row">
                <button class="btn btn-success col-1" id="btn-open-container">Добавить</button>
                <a class="btn btn-secondary offset-sm-1" href='admin.php'>На главную</a>
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
        if ($fish) {
            echo('<table class="table">
                        <thead class="thead-dark">
                            <th class="d-none"></th>
                            <th>Название</th>
                            <th>Фотография</th>
                            <th>Цена</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>');
            for ($i=0; $i < count($fish); $i++) { 
                echo("<tr>
                        <td class='d-none'>{$fish[0]->id}</td>
                        <td>{$fish[0]->Title}</td>
                        <td><img src='data:image/jpg;base64,{$fish[0]->Photo}'></td>
                        <td>{$fish[0]->MinPrice} руб. - {$fish[0]->MaxPrice} руб.</td>
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
        <script src='../Scripts/Admin/fishes_scripts.js'></script>
    </body>
</html>");        

    } else {
########______ОСНОВНАЯ___VIEW______######
print <<<ANIMAL
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Рыбы</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class='container'>
            <div class="functional-container row">
                <button class="btn btn-success col-1" id="btn-open-container">Добавить</button>
                <a class="btn btn-secondary offset-sm-1" href='admin.php'>На главную</a>
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
        require_once '../Classes/Fish.php';
        $fishes = Fish::Show();
        if ($fishes) {
            echo('<table class="table">
                        <thead class="thead-dark">
                            <th class="d-none"></th>
                            <th>Название</th>
                            <th>Фотография</th>
                            <th>Цена</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>');
            $fishesLen = count($fishes);
            for ($i=0; $i < $fishesLen; $i++) { 
                echo("<tr>
                        <td class='d-none'>{$fishes[$i]->id}</td>
                        <td>{$fishes[$i]->Title}</td>
                        <td><img src='data:image/jpg;base64,{$fishes[$i]->Photo}'></td>
                        <td>{$fishes[$i]->MinPrice} руб. - {$fishes[$i]->MaxPrice} руб.</td>
                        <td>
                            <button class='btn btn-warning'>Изменить</button>
                            <button class='btn btn-danger'>Удалить</button>
                        </td>
                    </tr>");
            }
            print'</tbody></table>';
        } else {
            echo('<div>Вы не создали ни одной рыбы!</div>');
        }
        


echo("</div>
        </div>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='../Scripts/Admin/fishes_scripts.js'></script>
    </body>
</html>");
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if ($_POST['fish'] ?? '') {
        $fish = json_decode($_POST['fish']);
        if ($_FILES['photo'] ?? '') {
            $photo = $_FILES['photo'];
            require_once '../Classes/Fish.php';
            if (Fish::Validate($fish, $photo)) {
                $fish = new Fish($fish, $photo);
                $fish->Create($fish);
            }
        } else {
            echo('Вы не загрузили фотографию!');
        }
    }
    elseif ($_POST['id'] ?? '') {
        require_once '../Classes/Fish.php';
        $id = $_POST['id'];
        Fish::Delete($id);
    }
    
    
}
            