<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['house'] ?? '') {
            $id = $_GET['house'];
            require_once '../../Classes/House.php';
            $house = new House();
            $house = $house->Get($id);
            if ($house) {
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
                for ($i=0; $i < count($house); $i++) { 
                    echo("<div class='creator-container row'>
                    <form method='POST' class='col'>
                        <div class='form-group col-4'>
                            <label for='col-form-label'>Название</label>
                            <input type='text' class='form-control' id='title' value='{$house[0]->Title}'>
                        </div>
                        <div class='form-group col-4'>
                            <label for='col-form-label'>Фотография</label>
                            <input type='file' class='form-control' id='photo'>
                        </div>
                        <div class='form-group col-4'>
                            <label for='col-form-label'>Количество домов</label>
                            <input type='text' class='form-control' id='count' value='{$house[0]->Count}'>
                        </div>
                        <div class='form-group col-4'>
                            <label for='col-form-label'>Количество мест</label>
                            <input type='text' class='form-control' id='places' value='{$house[0]->Places}'>
                        </div>
                        <div class='form-group col-4'>
                            <label for='col-form-label'>Цена</label>
                            <input type='text' class='form-control' id='price' value='{$house[0]->Price}'>
                        </div>
                        <button class='btn btn-primary row' id='btn-send' type='button'>Отправить</button>
                    </form>
                </div>");
                }
                echo('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                        <script src="../../Scripts/Admin/Infos/house_info_scripts.js"></script>
                        </div>
                    </body>
                </html>');
            } else {
                header('location: ../houses.php');    
            }

        } else {
            header('location: ../houses.php');
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['house'] ?? '') {
            $new_house = json_decode($_POST['house']);
            require_once '../../Classes/House.php';
            $house = new House('');
            if ($_FILES['photo'] ?? '') {
                $photo = $_FILES['photo'];
                if ($house->Validate($new_house, $photo)) {
                    $house = $house->Set($new_house, $photo);
                    $house->Update($house);
                }
            } else {
                $photo = '';
                if ($house->Validate($new_house, $photo)) {
                    $photo = '';
                    $house = $house->Set($new_house, $photo);
                    $house = $house->Update($house);
                }
            }
        }
    } else {
        http_response_code(502);
    }
?>