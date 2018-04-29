<?php
class Fish implements IFish
{
    protected $id;
    protected $title;
    protected $photo;
    protected $minPrice;
    protected $maxPrice;

    function __construct($fish, $photo) {
        if ($fish->id ?? '') {
            $this->id = $fish->id;
        } else {
            $this->id = uniqid();
        }
        $this->title = $fish->title;
        $this->minPrice = $fish->minPrice;
        $this->maxPrice = $fish->maxPrice;
        if ($photo ?? '') {
            $this->photo = base64_encode(file_get_contents($photo['tmp_name']));
        } else {
            $this->photo = null;
        }
    }

    function Create($fish)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicate($db, $fish, 'create')) {
            $insertFishQuery = $db->prepare('CALL spCreateFish (?,?,?,?,?)');
            $insertFishQuery->execute(array($fish->id, $fish->title, $fish->minPrice, $fish->maxPrice, $fish->photo));   
        }
    }

    function Update($fish)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicate($db, $fish, 'update')) {
            if ($fish->photo ?? '') {
                $updateFishQuery = $db->prepare('CALL spUpdateFishWithPhoto (?,?,?,?,?)');
                $updateFishQuery->execute(array($fish->title, $fish->minPrice, $fish->maxPrice, $fish->photo, $fish->id));
            } else {
                $updateFishQuery = $db->prepare('CALL spUpdateFish (?,?,?,?)');
                $updateFishQuery->execute(array($fish->title, $fish->minPrice, $fish->maxPrice, $fish->id));
            }   
        }
    }

    protected function CheckDublicate($db, $fish, $switch) {
        if ($switch === 'create') {
            $checkFishQuery = $db->prepare('SELECT * FROM vfishes WHERE Title = ?');
            $checkFishQuery->execute(array($fish->title));
            $fish = $checkFishQuery->fetchAll();
            if (count($fish) == 0) {
                return true;
            } else {
                echo('Такая рыба уже существует!');
            }
        } elseif ($switch === 'update') {
            $checkFishQuery = $db->prepare('SELECT * FROM vfishes WHERE Title = ?');
            $checkFishQuery->execute(array($fish->title));
            $fish = $checkFishQuery->fetchAll();
            if (count($fish) == 0 || count($fish) == 1) {
                return true;
            } else {
                echo('Такая рыба уже существует!');
            }
        }
        
    }

    static function Delete($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deleteFishQuery = $db->prepare('CALL spDeleteFish (?)');
        $deleteFishQuery->execute(array($id));
    }

    static function Show()
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectFishsQuery = $db->prepare('SELECT * FROM vFishes');
        $selectFishsQuery->execute();
        $fishes = $selectFishsQuery->fetchAll(PDO::FETCH_OBJ);
        if ($fishes) {
            return $fishes;
        } else {
            return false;
        }
    }

    static function Get($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectFishQuery = $db->prepare('SELECT * FROM vFishes WHERE id = ?');
        $selectFishQuery->execute(array($id));
        $fish = $selectFishQuery->fetchAll(PDO::FETCH_OBJ);
        if ($fish) {
            return $fish;
        } else {
            return false;
        }
    }

    static function Find($title)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findFishQuery = $db->prepare('SELECT * FROM vFishes WHERE title = ?');
        $findFishQuery->execute(array($title));
        $findlessFish = $findFishQuery->fetchAll(PDO::FETCH_OBJ);
        if ($findlessFish) {
            return $findlessFish;
        } else {
            return false;
        }
        
    }

    static function Validate($fish, $photo)
    {
        function ValidateTitle($title)
        {
            try {
                if ($title ?? '') {
                    $titleLen = mb_strlen($title);
                    if ($titleLen >= 2 && $titleLen <= 20) {
                        if (trim($title) === $title) {
                            if (htmlspecialchars($title) === $title) {
                                preg_match('/[А-ЯЁ][а-яё ]+/u', $title, $regTitle);
                                if ($regTitle ?? '') {
                                    if ($regTitle[0] === $title) {
                                         return true;
                                    } else {
                                        throw new Exception('Uncorrect Title Error', 1);
                                    }
                                    
                                } else {
                                    throw new Exception('Uncorrect Title Error', 1);
                                }
                                
                            } else {
                                throw new Exception('Uncorrect Title Error', 1);
                            }
                            
                        } else {
                            throw new Exception('Uncorrect Title Error', 1);
                        }
                    } else {
                        throw new Exception('Length Title Error', 1);
                    }
                } else {
                    throw new Exception('Empty Title Error', 1);
                }
                
            } catch (Exception $error) {
                if ($error->getMessage() === 'Empty Title Error') {
                    echo('Вы не ввели название рыбы!');
                }
                if ($error->getMessage() === 'Length Title Error') {
                    echo('Длина названия рыбы должна быть от 2 до 20 символов');
                }
                if ($error->getMessage() === 'Uncorrect Title Error') {
                    echo('Название рыбы должно состоять из букв русского алфавита');
                }
            }
        }

        function ValidatePrice($minPrice, $maxPrice)
        {
            try {
                if ($minPrice ?? '') {
                    if ($minPrice >= 5000) {
                        if (is_numeric($minPrice)) {
                               if ($maxPrice ?? '') {
                                    if ($maxPrice <= 80000) {
                                        if (is_numeric($maxPrice)) {
                                            if ($minPrice < $maxPrice) {
                                                return true;
                                            } else {
                                                throw new Exception("Wrong Prices Error", 1);
                                            }
                                        } else {
                                            throw new Exception("Uncorrect Max Price Error", 1);
                                        }
                                    } else {
                                        throw new Exception("Length Max Price Error", 1);
                                    }
                        
                                } else {
                                    throw new Exception("Empty Max Price Error", 1);
                                }
                                        
                        } else {
                            throw new Exception("Uncorrect Min Price Error", 1);
                        }
                    } else {
                        throw new Exception("Length Min Price Error", 1);
                    }
                } else {
                    throw new Exception("Empty Min Price Error", 1);
                }
                
            } catch(Exception $error) {
                if ($error->getMessage() === 'Empty Min Price Error') {
                    echo('Вы не ввели минимальную цену!');
                }
                if ($error->getMessage() === 'Length Min Price Error') {
                    echo('Размер минимальной цены не может быть ниже 5 тыс. рублей!');
                }
                if ($error->getMessage() === 'Uncorrect Min Price Error') {
                    echo('Минимальная цена должна состоять из цифр!');
                }
                if ($error->getMessage() === 'Empty Min Price Error') {
                    echo('Вы не ввели максимальную цену!');
                }
                if ($error->getMessage() === 'Length Max Price Error') {
                    echo('Размер максимальной цены не может превышать 80 тыс. рублей!');
                }
                if ($error->getMessage() === 'Uncorrect Max Price Error') {
                    echo('Максимальная цена должна состоять из цифр!');
                }
                if ($error->getMessage() === 'Wrong Prices Error') {
                    echo('Минимальная цена не может превышать максимальную!');
                }
            }
        }

        function ValidatePhoto($photo) {
            try {
                if (substr($_SERVER['HTTP_REFERER'], -31, 8) === 'fishinfo') {
                    return true;
                }
                if ($photo ?? '') {
                    if ($photo['size'] <= 2*1024*1024) {
                        $arrExt = ['jpg', 'png'];
                        if (in_array(strtolower(substr($photo['name'], -3, 3)), $arrExt)) {
                            return true;
                        } else {
                            throw new Exception("Extension Photo Error", 1);
                        }
                    } else {
                        throw new Exception("Size Photo Error", 1);
                    }
                } else {
                    throw new Exception("Empty Photo Error", 1);
                    
                }
                
            } catch (Exception $error){
                if ($error->getMessage() === 'Empty Photo Error') {
                    echo('Вы не загрузили фотографию!');
                }
                if ($error->getMessage() === 'Size Photo Error') {
                    echo('Размер фотографии не должен превышать 2 Мбайт!');
                }
                if ($error->getMessage() === 'Extension Photo Error') {
                    echo('Фотография должна быть в расширении .jpg или .png!');
                }
            }
        }

        if (ValidateTitle($fish->title) && ValidatePrice($fish->minPrice, $fish->maxPrice) && ValidatePhoto($photo)) {
            return true;
        }
    }


}
interface IFish {
    function Create($fish);

    function Update($fish);

    static function Delete($id);

    static function Show();

    static function Get($id);

    static function Find($title);

    static function Validate($fish, $photo);
    
}


?>