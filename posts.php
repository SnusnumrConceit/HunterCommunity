<?php
 if ($_SERVER['REQUEST_METHOD'] == 'GET') {
     
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
            <div class="row">
                <form method="POST" class="col">
                    <div class="form-group col-4">
                        <label for="col-form-label">Логин</label>
                        <input type="text" class="form-control" id="login">
                    </div>
                    <div class="form-group col-6">
                        <label for="col-from-label">Комментарий</label>
                        <textarea rows="20" cols="20" class="form-control" id="comment"></textarea>
                    </div>
                    <button class="btn btn-primary" id="btn-send" type="button">Отправить</button>
                </form>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="Scripts/Index/posts_scripts.js"></script>
    </body>
</html>
POST;
 }  elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['post'] ?? '') {
            $inputData = json_decode($_POST['post']);
            require_once 'Classes/Post.php';
            $post = new Post();
            if ($post->Validate($inputData)) {
                $post = $post->Set($inputData);
                var_dump($post);
            }
        } else {
            echo('Возникла ошибка! Повторите позднее!');
        }
    } else {
        header('location: index.php');
    }
    