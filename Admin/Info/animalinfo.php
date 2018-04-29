<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['animal'] ?? '') {
            $id = $_GET['animal'];
            require_once '../../Classes/Animal.php';
            $animal = Animal::Get($id);
            if ($animal) {
                echo("<!DOCTYPE html>
                <html>
                <head>
                    <meta charset='utf-8' />
                    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                    <title>{$animal[0]->Title}</title>
                    <meta name='viewport' content='width=device-width, initial-scale=1'>
                    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css'>
                </head>
                <body>
                <div class='container'>");

                for ($i=0; $i < count($animal); $i++) { 
                    echo("<div class='creator-container row'>
                            <form method='POST' class='col'>
                                <div class='form-group col-4'>
                                    <label for='col-form-label'>Название</label>
                                    <input type='text' class='form-control' id='title' value='{$animal[0]->Title}'>
                                </div>
                                <div class='form-group col-4'>
                                    <label for='col-form-label'>Фотография</label>
                                    <input type='file' class='form-control' id='photo'>
                                </div>
                                <div class='form-group col-4'>
                                    <div class='row'>
                                        <div class='col'>
                                            <label for='col-form-label'>Минимальная цена</label>
                                            <input type='text' class='form-control' id='min-price' value='{$animal[0]->MinPrice}'>
                                        </div>
                                        <div class='col'>
                                            <label for='col-form-label'>Максимальная цена</label>
                                            <input type='text' class='form-control' id='max-price' value='{$animal[0]->MaxPrice}'>
                                        </div>
                                    </div>
                                </div>
                                <button class='btn btn-primary row' id='btn-send' type='button'>Отправить</button>
                            </form>
                        </div>");
                }
                 echo('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                        <script src="../../Scripts/Admin/Infos/animal_info_scripts.js"></script>
                        </div>
                    </body>
                </html>');

            } else {
                header('location: ../animals.php');    
            }

        } else {
            header('location: ../animals.php');
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['animal'] ?? '') {
            $new_animal = json_decode($_POST['animal']);
            require_once '../../Classes/Animal.php';
            if ($_FILES['photo'] ?? '') {
                $photo = $_FILES['photo'];
                if (Animal::Validate($new_animal, $photo)) {
                    $animal = new Animal($new_animal, $photo);
                    $animal->Update($animal);
                }
            } else {
                $photo = '';
                if (Animal::Validate($new_animal, $photo)) {
                    $animal = new Animal($new_animal, $photo);
                    $animal = $animal->Update($animal);
                }
            }
        }
    } else {
        http_response_code(502);
    }
?>