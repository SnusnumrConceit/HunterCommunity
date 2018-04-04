<?php
class Security implements ISecurity{
    protected $id;
    protected $title;
    protected $count;

    public function Create($security)
    {   
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($security, $db, 'create')) {
            $createSecurityQuery = $db->prepare('INSERT INTO securities VALUES (?, ?, ?)');
            $createSecurityQuery->execute(array($security->id, $security->title, $security->count));
        }
    }

    public function Update($security)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($security, $db, 'UPDATE')) {
            $createSecurityQuery = $db->prepare('UPDATE securities SET Title = ? Count = ? WHERE id = ?)');
            $createSecurityQuery->execute(array($security->title, $security->count, $security->id));
        }
    }

    public function Delete($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deleteSecurityQuery = $db->prepare('DELETE FROM securities WHERE id = ?');
        $deleteSecurityQuery->execute(array($id));
    }

    public function Get($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $getSecurityQuery = $db->prepare('SELECT * FROM vsecurities WHERE id = ?');
        $getSecurityQuery->execute(array($id));
        $security = $getSecurityQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($security) == 1) {
            return $security;
        } else {
            echo('Отзыв не найден');
        }
        
    }

    public function Find($title)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findSecuritiesQuery = $db->prepare('SELECT * FROM vsecurities WHERE Title = ?');
        $findSecuritiesQuery->execute(array($title));
        $findSecurities = $findSecuritiesQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($findSecurities) != 0) {
            return $findSecurities;
        } else {
            return false;
        }
    }

    protected function CheckDublicates($security, $db, $switch)
    {
        if ($switch === "create") {
            $dubclicateQuery = $db->prepare('SELECT * FROM vsecurities WHERE Title = ?');
            $dubclicateQuery->execute(array($security->title));
            $currentSecurity = $dubclicateQuery->fetchAll(PDO::FETCH_OBJ);
            if (!$currentSecurity) {
                return true;
            } else {
                echo('Такая информация уже существует!');
            }
            
        } else if ($switch === "UPDATE"){
            $dubclicateQuery = $db->prepare('SELECT * FROM vsecurities WHERE Title = ?');
            $dubclicateQuery->execute(array($security->title, $security->message));
            $currentSecurity = $dubclicateQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentSecurity) == 0 || count($currentSecurity) == 1) {
                return true;
            } else {
                echo('Такая информация уже существует!');
            }
        }
        
    }

    function Show()
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectSecuritiesQuery = $db->prepare('SELECT * FROM vsecurities');
        $selectSecuritiesQuery->execute();
        $securities = $selectSecuritiesQuery->fetchAll(PDO::FETCH_OBJ);
        if ($securities) {
            return $securities;
        } else {
            echo('Вы не создали ни одной записи об охране хозяйства!');
        }
    }

    public function Validate($security)
    {
        function ValidateTitle($title)
        {
            try {
                if ($title ?? '') {
                    $titleLength = mb_strlen($title);
                    if ($titleLength >= 6 && $titleLength <= 30) {
                        if (trim($title) === $title && htmlspecialchars($title) === $title) {
                            if (preg_match('/([а-яёА-ЯЁ-])+/u', $title, $regTitle)) {
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
                    $errors['title'] = 'Вы не ввели логин!';
                    $errors = (object)$errors;
                    }
                if ($error->getMessage() === 'Length Title Error') {
                    $errors['title'] = 'Длина логина должна быть от 6 до 24 символов!';
                    $errors = (object)$errors;
                }
                if ($error->getMessage() === 'Wrong Title Error') {
                    $errors['title'] = 'Логин должен состоять из латинских букв, цифр, точки и нижнего подчёркивания!';
                    $errors = (object)$errors;
                }
                $errors = json_encode($errors);
                echo($errors);
            }
        }

        function ValidateCount($count)
        {
            try {
                if ($count ?? '') {
                    $countLength = mb_strlen($count);
                    if (is_numeric($count)) {
                        
                        if (trim($count) === $count && htmlspecialchars($count) === $count) {
                            
                            if ($count > 0 && $count <= 30) {
                                
                                return true;
                            } else {
                                    throw new Exception("Length Count Error", 1);
                              }
                        } else {
                            throw new Exception('Wrong Count Error', 1);
                        }
                    } else {
                        throw new Exception('Wrong Count Error', 1);
                    }
                } else {
                    throw new Exception("Empty Count Error", 1);
                }
                
            } catch (Exception $error) {
                $errors = [];
                if ($error->getMessage() === 'Empty Count Error') {
                    $errors['count'] = 'Вы не ввели комментарий!';
                    $errors = (object)$errors;
                    }
                if ($error->getMessage() === 'Length Count Error') {
                    $errors['count'] = 'Длина комментария должна быть не более 1000 символов!';
                    $errors = (object)$errors;
                }
                if ($error->getMessage() === 'Wrong Count Error') {
                    $errors['count'] = 'Ваш комментарий содержит латинские символы и запрещённые знаки! Пожалуйста, используйте в своём комментарии буквы кириллицы и следующие знаки препинания: <ul><li>точка</li><li>тире</li><li>восклицательный знак</li><li>вопросительный знак</li><li>запятая</li>.</div>';
                    $errors = (object)$errors;
                } else {
                    $errors['error'] = $error->getMessage();
                    $errors = (object)$errors;
                }
                $errors = json_encode($errors);
                echo($errors);
            }
        }

        if (ValidateTitle($security->title) && ValidateCount($security->count)) {
            return true;
        }
    }

    public function Set($security)
    {
        $this->id = uniqid();
        $this->title = $security->title;
        $this->count = $security->count;
        return $this;
    }
}

interface ISecurity {
    function Show();
    function Create($security);
    function Update($security);
    function Delete($id);
    function Get($id);
    function Find($title);
    function Validate($security);
    function Set($security);
}

?>