<?php
class Car implements ICar{
    protected $id;
    protected $title;
    protected $photo;
    protected $count;
    protected $price;

    public function Create($car)
    {   
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($car, $db, 'create')) {
            $createCarQuery = $db->prepare('CALL spCreateCar (?, ?, ?, ?, ?)');
            $createCarQuery->execute(array($car->id, $car->title, $car->photo, $car->count, $car->price));
        }
    }

    public function Update($car)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($car, $db, 'UPDATE')) {
            if ($car->photo ?? '') {
                $updateCarQuery = $db->prepare('CALL spUpdateCarWithPhoto (?, ?, ?, ?, ?)');
                $updateCarQuery->execute(array($car->title, $car->photo, $car->count, $car->price, $car->id));
            } else {
                $updateCarQuery = $db->prepare('CALL spUpdateCar (?, ?, ?, ?)');
                $updateCarQuery->execute(array($car->title, $car->count, $car->price, $car->id));
            }
            
            
        }
    }

    public function Delete($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deleteCarQuery = $db->prepare('CALL spDeleteCar (?)');
        $deleteCarQuery->execute(array($id));
    }

    public function Get($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $getCarQuery = $db->prepare('SELECT * FROM vcars WHERE id = ?');
        $getCarQuery->execute(array($id));
        $car = $getCarQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($car) == 1) {
            return $car;
        } else {
            echo('Отзыв не найден');
        }
        
    }

    public function Find($title)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findCarsQuery = $db->prepare('CALL spGetCarTitle (?)');
        $findCarsQuery->execute(array($title));
        $findCars = $findCarsQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($findCars) != 0) {
            return $findCars;
        } else {
            return false;
        }
    }

    protected function CheckDublicates($car, $db, $switch)
    {
        if ($switch === "create") {
            $dubclicateQuery = $db->prepare('CALL spGetCarTitle (?)');
            $dubclicateQuery->execute(array($car->title));
            $currentCar = $dubclicateQuery->fetchAll(PDO::FETCH_OBJ);
            if (!$currentCar) {
                return true;
            } else {
                echo('Такой отзыв уже существует!');
            }
            
        } else if ($switch === "UPDATE"){
            $dubclicateQuery = $db->prepare('CALL spGetCarTitle (?)');
            $dubclicateQuery->execute(array($car->title));
            $currentCar = $dubclicateQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentCar) == 0 || count($currentCar) == 1) {
                return true;
            } else {
                echo('Такой отзыв уже существует!');
            }
        }
        
    }

    function Show()
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectCarsQuery = $db->prepare('SELECT * FROM vcars');
        $selectCarsQuery->execute();
        $cars = $selectCarsQuery->fetchAll(PDO::FETCH_OBJ);
        if ($cars) {
            return $cars;
        } else {
            return false;
        }
    }

    public function Validate($car, $photo)
    {
        if ($this->ValidateTitle($car->title) && $this->ValidatePhoto($photo) && $this->ValidateCount($car->count)
            && $this->ValidatePrice($car->price)) {
            return true;
        }
    }

    public function Set($car, $photo = null)
    {
        if ($car->id ?? '') {
            $this->id = $car->id;
        } else {
            $this->id = uniqid();
        }
        $this->title = $car->title;
        if ($photo ?? '') {
            $this->photo = base64_encode(file_get_contents($photo['tmp_name']));
        } else {
            $this->photo = $photo;
        }
        $this->count = $car->count;
        $this->price = $car->price;
        return $this;
    }

    protected function ValidateTitle($title) {
        try {
            if ($title ?? '') {
                $titleLen = mb_strlen($title);
                if ($titleLen >=4 && $titleLen <=50) {
                    preg_match('/([А-Я][а-я ]+)/u', $title, $regTitle);
                    if ($regTitle ?? '') {
                        if ($regTitle[0] == $title) {
                            return true;
                        } else {
                            throw new Execption("Uncorrect Title Error", 1);
                        }
                    } else {
                        throw new Execption("Uncorrect Title Error", 1);
                    }                    
                } else {
                    throw new Exception("Length Title Error", 1);
                    
                }
            } else {
                throw new Exception("Empty Title Error", 1);
                
            }
            
        } catch (Exception $error){
            if ($error->getMessage() === 'Empty Title Error') {
                echo("Вы не ввели название!");
            }
            
            if ($error->getMessage() === 'Length Title Error') {
                echo("Длина названия должна быть от 6 до 50 символов!");
            }

            if ($error->getMessage() === 'Uncorrect Title Error') {
                echo("Название должно состоять из букв русского алфавита!");
            }
        }
    }

    protected function ValidatePhoto($photo)
    {
        try {
            if (substr($_SERVER['HTTP_REFERER'], -29, 7) === 'carinfo') {
                if (!($photo ?? '')) {
                    return true;
                }
            }
            if (is_uploaded_file($photo['tmp_name'])) {
                if ($photo['size'] <= 2*1024*1024) {
                    $ext = substr($photo['name'], -3, 3);
                    $arrExt = ['jpg', 'png', 'JPG', 'PNG'];
                    if (in_array($ext, $arrExt)) {
                        return true;
                    } else {
                        throw new Exception("Extension Photo Error", 1);
                    }
                } else {
                    throw new Exception("Size Photo Error", 1);
                }
            } else {
                throw new Exception("Download Photo Error", 1);
            }
            
        } catch (Exception $error) {
            if ($error->getMessage() === 'Download Photo Error') {
                echo('Вы не загрузили фотографию!');
            }
            
            if ($error->getMessage() === 'Download Photo Error') {
                echo('Размер фотографии не должен превышать более 2 Мбайт!');
            }

            if ($error->getMessage() === 'Download Photo Error') {
                echo('Фотография должна быть с расширением jpg или png!');
            }
        }
    }

    protected function ValidateCount($count)
    {
        try {
            if ($count ?? '') {
                if (is_numeric($count)) {
                    if ($count > 0 && $count <= 10) {
                        return true;
                    } else {
                        throw new Exception("Length Count Error", 1);
                    }
                } else {
                    throw new Exception("Uncorrect Count Error", 1);
                }
            } else {
                throw new Exception("Empty Count Error", 1);
                
            }
            
        } catch (Exception $error) {
            if ($error->getMessage() == 'Empty Count Error') {
                echo('Вы не ввели количество домов!');
            }

            if ($error->getMessage() == 'Uncorrect Count Error') {
                echo('Количество домов должно состоять из цифр!');
            }

            if ($error->getMessage() == 'Length Count Error') {
                echo('Количество домов должно не должно превышать 10!');
            }
        }
    }

    protected function ValidatePrice($price)
    {
        try {
            if ($price ?? '') {
                if (is_numeric($price)) {
                    $priceLen = strlen($price);
                    if ($priceLen >= 3 && $priceLen <= 4) {
                        if ($price >= 600 && $price <= 2000) {
                            return true;
                        } else {
                            throw new Exception('Range Price Error');
                        }
                        
                    } else {
                        throw new Exception("Length Price Error", 1);
                    }
                } else {
                    throw new Exception("Uncorrect Price Error", 1);
                }
            } else {
                throw new Exception("Empty Price Error", 1);
                
            }
            
        } catch (Exception $error) {
            if ($error->getMessage() == 'Empty Price Error') {
                echo('Вы не ввели цену!');
            }

            if ($error->getMessage() == 'Uncorrect Price Error') {
                echo('Цена должна состоять из цифр!');
            }

            if ($error->getMessage() == 'Length Price Error') {
                echo('Цена должна быть от 3 до 4 символов!');
            }

            if ($error->getMessage() == 'Length Price Error') {
                echo('Цена должна быть от 600 до 2000 рублей!');
            }
        }
    }
}

interface ICar {
    function Show();
    function Create($car);
    function Update($car);
    function Delete($id);
    function Get($id);
    function Find($title);
    function Validate($car, $photo);
    function Set($car, $photo);
}

?>