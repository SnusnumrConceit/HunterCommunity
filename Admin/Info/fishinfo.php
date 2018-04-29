<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['fish'] ?? '') {
            $id = $_GET['fish'];
            require_once '../../Classes/Fish.php';
            $fish = Fish::Get($id);
            if ($fish) {
                echo("<!DOCTYPE html>
                <html>
                <head>
                    <meta charset='utf-8' />
                    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                    <title>{$fish[0]->Title}</title>
                    <meta name='viewport' content='width=device-width, initial-scale=1'>
                    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css'>
                </head>
                <body>
                <div class='container'>");

                for ($i=0; $i < count($fish); $i++) { 
                    echo("<div class='creator-container row'>
                            <form method='POST' class='col'>
                                <div class='form-group col-4'>
                                    <label for='col-form-label'>Название</label>
                                    <input type='text' class='form-control' id='title' value='{$fish[0]->Title}'>
                                </div>
                                <div class='form-group col-4'>
                                    <label for='col-form-label'>Фотография</label>
                                    <input type='file' class='form-control' id='photo'>
                                </div>
                                <div class='form-group col-4'>
                                    <div class='row'>
                                        <div class='col'>
                                            <label for='col-form-label'>Минимальная цена</label>
                                            <input type='text' class='form-control' id='min-price' value='{$fish[0]->MinPrice}'>
                                        </div>
                                        <div class='col'>
                                            <label for='col-form-label'>Максимальная цена</label>
                                            <input type='text' class='form-control' id='max-price' value='{$fish[0]->MaxPrice}'>
                                        </div>
                                    </div>
                                </div>
                                <button class='btn btn-primary row' id='btn-send' type='button'>Отправить</button>
                            </form>
                        </div>");
                }
                 echo('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                        <script src="../../Scripts/Admin/Infos/fish_info_scripts.js"></script>
                        </div>
                    </body>
                </html>');

            } else {
                header('location: ../fishes.php');    
            }

        } else {
            header('location: ../fishes.php');
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['fish'] ?? '') {
            $new_fish = json_decode($_POST['fish']);
            require_once '../../Classes/Fish.php';
            if ($_FILES['photo'] ?? '') {
                $photo = $_FILES['photo'];
                if (Fish::Validate($new_fish, $photo)) {
                    $fish = new Fish($new_fish, $photo);
                    $fish->Update($fish);
                }
            } else {
                $photo = '';
                if (Fish::Validate($new_fish, $photo)) {
                    $fish = new Fish($new_fish, $photo);
                    $fish = $fish->Update($fish);
                }
            }
        }
    } else {
        http_response_code(502);
    }
?>