<?php
 if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['title'] ?? '') {
            require_once '../Classes/New.php';
            $title = $_GET['title'];
            $new = new News();
            $news = $new->Find($title);

#####______ПОИСКОВАЯ_____VIEW______######
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
                        <input type="text" class="form-control" placeholder="Введите заголовок">
                        <button class="btn btn-primary">Найти</button>
                    </div>
                </form>
            </div>
            <div class="creator-container row">
                <form method="POST" class="col">
                    <div class="form-group col-4">
                        <label for="col-form-label">Заголовок</label>
                        <input type="text" class="form-control" id="title">
                    </div>
                    <div class="form-group col-6">
                        <label for="col-from-label">Новость</label>
                        <textarea rows="20" cols="20" class="form-control" id="news"></textarea>
                    </div>
                    <button class="btn btn-primary row" id="btn-send" type="button">Отправить</button>
                </form>
            </div>
            <div class="row">
POST;
            if ($news) {
                echo("<table class='table table-hover'>
                        <thead class='thead-dark'>
                            <th class='d-none'></th>
                            <th>Заголовок</th>
                            <th>Отзыв</th>
                            <th>Дата и время</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>");
                        $newsLength = count($news);
                        for ($i=0; $i < $newsLength; $i++) { 
                            echo("<tr>
                                <td class='d-none'>{$news[$i]->id}</td>
                                <td>{$news[$i]->Title}</td>
                                <td>{$news[$i]->Description}</td>
                                <td>{$news[$i]->Date}</td>
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
        <script src='../Scripts/Admin/news_scripts.js'></script>
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
                        <input type="text" class="form-control" placeholder="Введите заголовок" id="find-input">
                        <button class="btn btn-primary" id="btn-find" type="button">Найти</button>
                    </div>
                </form>
            </div>
            <div class="creator-container row">
                <form method="POST" class="col">
                    <div class="form-group col-4">
                        <label for="col-form-label">Заголовок</label>
                        <input type="text" class="form-control" id="title">
                    </div>
                    <div class="form-group col-6">
                        <label for="col-from-label">Новость</label>
                        <textarea rows="20" cols="20" class="form-control" id="news"></textarea>
                    </div>
                    <button class="btn btn-primary row" id="btn-send" type="button">Отправить</button>
                </form>
            </div>
            <div class="row">
POST;
            require_once '../Classes/New.php';
            $new = new News();
            $news = $new->Show();

            if ($news) {
                echo("<table class='table table-hover'>
                        <thead class='thead-dark'>
                            <th class='d-none'></th>
                            <th>Заголовок</th>
                            <th>Отзыв</th>
                            <th>Дата и время</th>
                            <th>Операции</th>
                        </thead>
                        <tbody>");
                $newsLength = count($news);
                for ($i=0; $i < $newsLength; $i++) { 
                    echo("<tr>
                            <td class='d-none'>{$news[$i]->id}</td>
                            <td>{$news[$i]->Title}</td>
                            <td>{$news[$i]->Description}</td>
                            <td>{$news[$i]->Date}</td>
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
        <script src='../Scripts/Admin/news_scripts.js'></script>
    </body>
</html>");
        }
 }  elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['news'] ?? '') {
            $inputData = json_decode($_POST['news']);
            require_once '../Classes/New.php';
            $new = new News();
            if ($new->Validate($inputData)) {
                $new = $new->Set($inputData);
                $new->Create($new);
            } else {
                echo('WHERE IS VALIDATION?');
            }
        } elseif ($_POST['id'] ?? '') {
            require_once '../Classes/New.php';
            $new = new News();
            $id = $_POST['id'];
            $new->Delete($id);
        }
    } else {
        header('location: index.php');
    }
    