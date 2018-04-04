<?php
class Post implements IPost{
    protected $id;
    protected $login;
    protected $date;
    protected $message;

    public function Create($post)
    {
        # code...
    }

    public function Update($post)
    {
        # code...
    }

    public function Delete($id)
    {
        # code...
    }

    public function Get($id)
    {
        # code...
    }

    public function Find($login)
    {
        # code...
    }

    public function Validate($post)
    {
        function ValidateLogin($login)
        {
            try {
                if ($login ?? '') {
                    $loginLength = strlen($login);
                    if ($loginLength >= 6 && $loginLength <= 24) {
                        if (trim($login) === $login && htmlspecialchars($login) === $login) {
                            if (preg_match('/[A-Za-z]{1,}[a-zA-Z0-9_.]{5,}/', $login, $regLogin)) {
                                if ($regLogin ?? '') {
                                    if ($regLogin[0] == $login) {
                                        return true;
                                    }
                                } else {
                                    throw new Exception('Wrong Login Error', 1);
                                }
                            } else {
                                throw new Exception("Wrong Login Error", 1);
                            }
                        } else {
                            throw new Exception('Wrong Login Error', 1);
                        }
                    } else {
                        throw new Exception("Length Login Error", 1);
                    }
                } else {
                    throw new Exception("Empty Login Error", 1);
                }
            } catch (Exception $error) {
                $errors = [];
                if ($error->getMessage() === 'Empty Login Error') {
                    $errors['login'] = 'Вы не ввели логин!';
                    $errors = (object)$errors;
                    }
                if ($error->getMessage() === 'Length Login Error') {
                    $errors['login'] = 'Длина логина должна быть от 6 до 24 символов!';
                    $errors = (object)$errors;
                }
                if ($error->getMessage() === 'Wrong Login Error') {
                    $errors['login'] = 'Логин должен состоять из латинских букв, цифр, точки и нижнего подчёркивания!';
                    $errors = (object)$errors;
                }
                $errors = json_encode($errors);
                echo($errors);
            }
        }

        function ValidateComment($comment)
        {
            var_dump($comment);
            try {
                if ($comment ?? '') {
                    $commentLength = mb_strlen($comment);
                    if ($commentLength > 0 && $commentLength <= 1000) {
                        if (trim($comment) === $comment && htmlspecialchars($comment) === $comment) {
                            if (preg_match('/([а-яА-Я0-9,.!?:\- ])+/u', $comment, $regComment)) {
                                if ($regComment ?? '') {
                                    if ($regComment[0] == $comment) {
                                        return true;
                                    }
                                } else {
                                    throw new Exception('Wrong Login Error', 1);
                                }
                            } else {
                                throw new Exception("Wrong Comment Error", 1);
                            }
                        } else {
                            throw new Exception('Wrong Comment Error', 1);
                        }
                    } else {
                        throw new Exception("Length Comment Error", 1);
                    }
                } else {
                    throw new Exception("Empty Comment Error", 1);
                }
            } catch (Exception $error) {
                $errors = [];
                if ($error->getMessage() === 'Empty Comment Error') {
                    $errors['comment'] = 'Вы не ввели комментарий!';
                    $errors = (object)$errors;
                    }
                if ($error->getMessage() === 'Length Comment Error') {
                    $errors['comment'] = 'Длина комментария должна быть не более 1000 символов!';
                    $errors = (object)$errors;
                }
                if ($error->getMessage() === 'Wrong Comment Error') {
                    $errors['comment'] = 'Ваш комментарий содержит латинские символы и запрещённые знаки! Пожалуйста, используйте в своём комментарии буквы кириллицы и следующие знаки препинания: <ul><li>точка</li><li>тире</li><li>восклицательный знак</li><li>вопросительный знак</li><li>запятая</li>.</div>';
                    $errors = (object)$errors;
                } else {
                    $errors['error'] = $error->getMessage();
                    $errors = (object)$errors;
                }
                $errors = json_encode($errors);
                echo($errors);
            }
        }

        if (ValidateLogin($post->login) && ValidateComment($post->comment)) {
            return true;
        }
    }

    public function Set($post)
    {
        $this->id = uniqid();
        $this->login = $post->login;
        $this->message = $post->comment;
        $this->date = date('j-F-y H:i');
        return $this;
    }

    protected function CheckDublicates($post, $db, $switch)
    {
        # code...
    }

    function Show()
    {
        # code...
    }
}

interface IPost {
    function Show();
    function Create($post);
    function Update($post);
    function Delete($id);
    function Get($id);
    function Find($login);
    function Validate($post);
    function Set($inputData);
}

?>