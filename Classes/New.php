<?php
class News implements INew {
    protected $id;
    protected $title;
    protected $description;
    protected $date;

    public function Create($new)
    {   
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($new, $db, 'create')) {
            $createNewQuery = $db->prepare('INSERT INTO news VALUES (?, ?, ?, ?)');
            $createNewQuery->execute(array($new->id, $new->title, $new->description, $new->date));
        }
    }

    public function Update($new)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($new, $db, 'UPDATE')) {
            $createNewQuery = $db->prepare('UPDATE news SET Title = ? Description = ?, Date = ? WHERE id = ?)');
            $createNewQuery->execute(array($new->title, $new->description, $new->date, $new->id));
        }
    }

    public function Delete($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deleteNewQuery = $db->prepare('DELETE FROM news WHERE id = ?');
        $deleteNewQuery->execute(array($id));
    }

    public function Get($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $getNewQuery = $db->prepare('SELECT * FROM vnews WHERE id = ?');
        $getNewQuery->execute(array($id));
        $new = $getNewQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($new) == 1) {
            return $new;
        } else {
            echo('Отзыв не найден');
        }
        
    }

    public function Find($title)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findNewsQuery = $db->prepare('SELECT * FROM vnews WHERE Title = ?');
        $findNewsQuery->execute(array($title));
        $findNews = $findNewsQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($findNews) != 0) {
            return $findNews;
        } else {
            return false;
        }
    }

    protected function CheckDublicates($new, $db, $switch)
    {
        if ($switch === "create") {
            $dubclicateQuery = $db->prepare('SELECT * FROM vnews WHERE Title = ? AND Description = ?');
            $dubclicateQuery->execute(array($new->title, $new->description));
            $currentNew = $dubclicateQuery->fetchAll(PDO::FETCH_OBJ);
            if (!$currentNew) {
                return true;
            } else {
                echo('Такая новость уже существует!');
            }
            
        } else if ($switch === "UPDATE"){
            $dubclicateQuery = $db->prepare('SELECT * FROM vnews WHERE Title = ? AND Description = ?');
            $dubclicateQuery->execute(array($new->title, $new->description));
            $currentNew = $dubclicateQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentNew) == 0 || count($currentNew) == 1) {
                return true;
            } else {
                echo('Так новость уже существует!');
            }
        }
        
    }

    function Show()
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectNewsQuery = $db->prepare('SELECT * FROM vnews');
        $selectNewsQuery->execute();
        $news = $selectNewsQuery->fetchAll(PDO::FETCH_OBJ);
        if ($news) {
            return $news;
        } else {
            echo('Вы не создали ни одной новости!');
        }
    }

    public function Set($new)
    {
        $this->id = uniqid();
        $this->title = $new->title;
        $this->description = $new->news;
        $this->date = date('j-F-y H:i');
        return $this;
    }

    public function Validate($new)
    {
       function ValidateTitle($title)
            {
                try {
                    if ($title ?? '') {
                        $titleLength = mb_strlen($title);
                        if ($titleLength >= 6 && $titleLength <= 24) {
                            if (trim($title) === $title && htmlspecialchars($title) === $title) {
                                if (preg_match('/([а-ёяА-ЯЁ0-9,.!?:\- ])+/u', $title, $regTitle)) {
                                    if ($regTitle ?? '') {
                                        if ($regTitle[0] == $title) {
                                            return true;
                                        }
                                    } else {
                                        throw new Exception('Wrong Title Error', 1);
                                    }
                                } else {
                                    throw new Exception("Wrong Title Error", 1);
                                }
                            } else {
                                throw new Exception('Wrong Title Error', 1);
                            }
                        } else {
                            throw new Exception("Length Title Error", 1);
                        }
                    } else {
                        throw new Exception("Empty Title Error", 1);
                    }
                } catch (Exception $error) {
                    $errors = [];
                    if ($error->getMessage() === 'Empty Title Error') {
                        $errors['title'] = 'Вы не ввели текст новости!';
                        $errors = (object)$errors;
                        }
                    if ($error->getMessage() === 'Length Title Error') {
                        $errors['title'] = 'Текст новости не может содержать более 500 символов!';
                        $errors = (object)$errors;
                    }
                    if ($error->getMessage() === 'Wrong Title Error') {
                        $errors['title'] = 'Ваш текст новости содержит латинские символы и запрещённые знаки! Пожалуйста, используйте в своём комментарии буквы кириллицы и следующие знаки препинания: <ul><li>точка</li><li>тире</li><li>восклицательный знак</li><li>вопросительный знак</li><li>запятая</li>';
                        $errors = (object)$errors;
                    }
                    $errors = json_encode($errors);
                    echo($errors);
                }
            }

            function ValidateDescription($description)
            {
                try {
                    if ($description ?? '') {
                        $descriptionLength = mb_strlen($description);
                        if ($descriptionLength > 0 && $descriptionLength <= 1000) {
                            if (trim($description) === $description && htmlspecialchars($description) === $description) {
                                if (preg_match('/([а-ёяА-ЯЁ0-9,.!?:\- ])+/u', $description, $regDescription)) {
                                    if ($regDescription ?? '') {
                                        if ($regDescription[0] == $description) {
                                            return true;
                                        }
                                    } else {
                                        throw new Exception('Wrong Description Error', 1);
                                    }
                                } else {
                                    throw new Exception("Wrong Description Error", 1);
                                }
                            } else {
                                throw new Exception('Wrong Description Error', 1);
                            }
                        } else {
                            throw new Exception("Length Description Error", 1);
                        }
                    } else {
                        throw new Exception("Empty Description Error", 1);
                    }
                } catch (Exception $error) {
                    $errors = [];
                    if ($error->getMessage() === 'Empty Description Error') {
                        $errors['news'] = 'Вы не ввели комментарий!';
                        $errors = (object)$errors;
                        }
                    if ($error->getMessage() === 'Length Description Error') {
                        $errors['news'] = 'Длина комментария должна быть не более 1000 символов!';
                        $errors = (object)$errors;
                    }
                    if ($error->getMessage() === 'Wrong Description Error') {
                        $errors['news'] = 'Ваш комментарий содержит латинские символы и запрещённые знаки! Пожалуйста, используйте в своём комментарии буквы кириллицы и следующие знаки препинания: <ul><li>точка</li><li>тире</li><li>восклицательный знак</li><li>вопросительный знак</li><li>запятая</li>.</div>';
                        $errors = (object)$errors;
                    } else {
                        $errors['error'] = $error->getMessage();
                        $errors = (object)$errors;
                    }
                    $errors = json_encode($errors);
                    echo($errors);
                }
            }

            if (ValidateTitle($new->title) && ValidateDescription($new->news)) {
                return true;
            }
        
    }

}

interface INew {
    function Create($new);
    function Update($new);
    function Delete($id);
    function Show();
    function Get($id);
    function Find($title);
    function Set($new);
    function Validate($new);
}
?>