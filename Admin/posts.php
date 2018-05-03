<?php
 if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['login'] ?? '') {
            require_once '../Classes/Post.php';
            $login = $_GET['login'];
            $post = new Post();
            $posts = $post->Find($login);
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
                <a class="btn btn-secondary offset-sm-1" href='admin.php'>На главную</a>
                <form method="GET" class="form-inline col" id="find-input">
                    <div class="form group offset-sm-4">
                        <input type="text" class="form-control" placeholder="Введите логин">
                        <button class="btn btn-primary">Найти</button>
                    </div>
                </form>
            </div>
            <div class="creator-container row">
                <form method="POST" class="col">
                    <div class="form-group col-4">
                        <label for="col-form-label">Логин</label>
                        <input type="text" class="form-control" id="login">
                    </div>
                    <div class="form-group col-6">
                        <label for="col-from-label">Комментарий</label>
                        <textarea rows="20" cols="20" class="form-control" id="comment"></textarea>
                    </div>
                    <button class="btn btn-primary row" id="btn-send" type="button">Отправить</button>
                </form>
            </div>
            <div class="row">
POST;
            if ($posts) {
                echo("<table class='table table-hover'>
                        <thead class='thead-dark'>
                            <th class='d-none'></th>
                            <th>Логин</th>
                            <th>Отзыв</th>
                            <th>Дата и время</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>");
                        $postsLength = count($posts);
                        for ($i=0; $i < $postsLength; $i++) { 
                            echo("<tr>
                                <td class='d-none'>{$posts[$i]->id}</td>
                                <td>{$posts[$i]->Login}</td>
                                <td>{$posts[$i]->Message}</td>
                                <td>{$posts[$i]->Date}</td>
                                <td>
                                    <button class='btn btn-warning'>Изменить</button>
                                    <button class='btn btn-danger'>Удалить</button>
                                </td>
                              </tr>");
                        }
                        echo("</tbody>
                    </table>");
            } else {
                echo("<div>По запросу <i>{$login}</i> ничего не найдено");
            }
            echo("</div>
        </div>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='../Scripts/Admin/posts_scripts.js'></script>
        <script src='../Scripts/Index/posts_scripts.js'></script>
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
                <a class="btn btn-secondary offset-sm-1" href='admin.php'>На главную</a>
                <form method="GET" class="form-inline col">
                    <div class="form group offset-sm-4">
                        <input type="text" class="form-control" placeholder="Введите логин" id="find-input">
                        <button class="btn btn-primary" id="btn-find" type="button">Найти</button>
                    </div>
                </form>
            </div>
            <div class="creator-container row">
                <form method="POST" class="col">
                    <div class="form-group col-4">
                        <label for="col-form-label">Логин</label>
                        <input type="text" class="form-control" id="login">
                    </div>
                    <div class="form-group col-6">
                        <label for="col-from-label">Комментарий</label>
                        <textarea rows="20" cols="20" class="form-control" id="comment"></textarea>
                    </div>
                    <button class="btn btn-primary row" id="btn-send" type="button">Отправить</button>
                </form>
            </div>
            <div class="row">
POST;
            require_once '../Classes/Post.php';
            $post = new Post();
            $posts = $post->Show();

            if ($posts) {
                echo("<table class='table table-hover'>
                        <thead class='thead-dark'>
                            <th class='d-none'></th>
                            <th>Логин</th>
                            <th>Отзыв</th>
                            <th>Дата и время</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>");
                $postsLength = count($posts);
                for ($i=0; $i < $postsLength; $i++) { 
                    echo("<tr>
                            <td class='d-none'>{$posts[$i]->id}</td>
                            <td>{$posts[$i]->Login}</td>
                            <td>{$posts[$i]->Message}</td>
                            <td>{$posts[$i]->Date}</td>
                            <td>
                                <button class='btn btn-warning'>Изменить</button>
                                <button class='btn btn-danger'>Удалить</button>
                            </td>
                        </tr>");
                }
                echo("</tbody>
                    </table>");
            }
            echo("</div>
        </div>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='../Scripts/Admin/posts_scripts.js'></script>
        <script src='../Scripts/Index/posts_scripts.js'></script>
    </body>
</html>");
        }
 }  elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['post'] ?? '') {
            $inputData = json_decode($_POST['post']);
            require_once '../Classes/Post.php';
            $post = new Post();
            if ($post->Validate($inputData)) {
                $post = $post->Set($inputData);
                $post->Create($post);
            }
        } elseif ($_POST['id'] ?? '') {
            require_once '../Classes/Post.php';
            $post = new Post();
            $id = $_POST['id'];
            $post->Delete($id);
        }
    } else {
        header('location: index.php');
    }
    