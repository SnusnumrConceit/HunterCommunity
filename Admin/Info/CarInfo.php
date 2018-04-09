<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['car'] ?? '') {
            $id = $_GET['car'];
            require_once '../../Classes/Car.php';
            $car = new Car();
            $car = $car->Get($id);
            if ($car) {
                echo('<!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <title>Page Title</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
                </head>
                <body>
                <div class="container">');
                for ($i=0; $i < count($car); $i++) { 
                    echo("<div class='creator-container row'>
                    <form method='POST' class='col'>
                        <div class='form-group col-4'>
                            <label for='col-form-label'>Название</label>
                            <input type='text' class='form-control' id='title' value='{$car[0]->Title}'>
                        </div>
                        <div class='form-group col-4'>
                            <label for='col-form-label'>Фотография</label>
                            <input type='file' class='form-control' id='photo'>
                        </div>
                        <div class='form-group col-4'>
                            <label for='col-form-label'>Количество автомобилей</label>
                            <input type='text' class='form-control' id='count' value='{$car[0]->Count}'>
                        </div>
                        <div class='form-group col-4'>
                            <label for='col-form-label'>Цена</label>
                            <input type='text' class='form-control' id='price' value='{$car[0]->Price}'>
                        </div>
                        <button class='btn btn-primary row' id='btn-send' type='button'>Отправить</button>
                    </form>
                </div>");
                }
                echo('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                        <script src="../../Scripts/Admin/Infos/car_info_scripts.js"></script>
                        </div>
                    </body>
                </html>');
            } else {
                header('location: ../cars.php');    
            }

        } else {
            header('location: ../cars.php');
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['car'] ?? '') {
            $new_car = json_decode($_POST['car']);
            require_once '../../Classes/Car.php';
            $car = new Car('');
            if ($_FILES['photo'] ?? '') {
                $photo = $_FILES['photo'];
                if ($car->Validate($new_car, $photo)) {
                    $car = $car->Set($new_car, $photo);
                    $car->Update($car);
                }
            } else {
                $photo = '';
                if ($car->Validate($new_car, $photo)) {
                    $car = $car->Set($new_car);
                    $car = $car->Update($new_car);
                }
                
            }
            
        }
    } else {
        http_response_code(502);
    }
?>