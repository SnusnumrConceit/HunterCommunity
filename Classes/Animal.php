<?php
class Animal implements IAnimal
{
    protected $id;
    protected $title;
    protected $photo;
    protected $minPrice;
    protected $maxPrice;

    function __construct($animal, $photo) {
        if ($animal->id ?? '') {
            $this->id = $animal->id;
        } else {
            $this->id = uniqid();
        }
        $this->title = $animal->title;
        $this->minPrice = $animal->minPrice;
        $this->maxPrice = $animal->maxPrice;
        if ($photo ?? '') {
            $this->photo = base64_encode(file_get_contents($photo['tmp_name']));
        } else {
            $this->photo = null;
        }
    }

    function Create($animal)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicate($db, $animal, 'create')) {
            $insertAnimalQuery = $db->prepare('CALL spCreateAnimal (?,?,?,?,?)');
            $insertAnimalQuery->execute(array($animal->id, $animal->title, $animal->minPrice, $animal->maxPrice, $animal->photo));   
        }
    }

    function Update($animal)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicate($db, $animal, 'update')) {
            if ($animal->photo ?? '') {
                $updateAnimalQuery = $db->prepare('CALL spUpdateAnimalWithPhoto (?,?,?,?,?)');
                $updateAnimalQuery->execute(array($animal->title, $animal->minPrice, $animal->maxPrice, $animal->photo, $animal->id));
            } else {
                $updateAnimalQuery = $db->prepare('CALL spUpdateAnimal (?,?,?,?)');
                $updateAnimalQuery->execute(array($animal->title, $animal->minPrice, $animal->maxPrice, $animal->id));
            }   
        }
    }

    protected function CheckDublicate($db, $animal, $switch) {
        if ($switch === 'create') {
            $checkAnimalQuery = $db->prepare('SELECT * FROM vanimals WHERE Title = ?');
            $checkAnimalQuery->execute(array($animal->title));
            $animal = $checkAnimalQuery->fetchAll();
            if (count($animal) == 0) {
                return true;
            } else {
                echo('Такое животное уже существует!');
            }
        } elseif ($switch === 'update') {
            $checkAnimalQuery = $db->prepare('SELECT * FROM vanimals WHERE Title = ?');
            $checkAnimalQuery->execute(array($animal->title));
            $animal = $checkAnimalQuery->fetchAll();
            if (count($animal) == 0 || count($animal) == 1) {
                return true;
            } else {
                echo('Такое животное уже существует!');
            }
        }
        
    }

    static function Delete($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deleteAnimalQuery = $db->prepare('CALL spDeleteAnimal (?)');
        $deleteAnimalQuery->execute(array($id));
    }

    static function Show()
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectAnimalsQuery = $db->prepare('SELECT * FROM vAnimals');
        $selectAnimalsQuery->execute();
        $animals = $selectAnimalsQuery->fetchAll(PDO::FETCH_OBJ);
        if ($animals) {
            return $animals;
        } else {
            return false;
        }
    }

    static function Get($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectAnimalQuery = $db->prepare('SELECT * FROM vAnimals WHERE id = ?');
        $selectAnimalQuery->execute(array($id));
        $animal = $selectAnimalQuery->fetchAll(PDO::FETCH_OBJ);
        if ($animal) {
            return $animal;
        } else {
            return false;
        }
    }

    static function Find($title)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findAnimalQuery = $db->prepare('SELECT * FROM vAnimals WHERE title = ?');
        $findAnimalQuery->execute(array($title));
        $findlessAnimal = $findAnimalQuery->fetchAll(PDO::FETCH_OBJ);
        if ($findlessAnimal) {
            return $findlessAnimal;
        } else {
            return false;
        }
        
    }

    static function Validate($animal, $photo)
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
                    echo('Вы не ввели название животного!');
                }
                if ($error->getMessage() === 'Length Title Error') {
                    echo('Длина названия животного должна быть от 2 до 20 символов');
                }
                if ($error->getMessage() === 'Uncorrect Title Error') {
                    echo('Название животного должно состоять из букв русского алфавита');
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
                if (substr($_SERVER['HTTP_REFERER'], -35, 10) === 'animalinfo') {
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

        if (ValidateTitle($animal->title) && ValidatePrice($animal->minPrice, $animal->maxPrice) && ValidatePhoto($photo)) {
            return true;
        }
    }


}
interface IAnimal {
    function Create($animal);

    function Update($animal);

    static function Delete($id);

    static function Show();

    static function Get($id);

    static function Find($title);

    static function Validate($animal, $photo);
    
}


?>