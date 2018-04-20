<?php
class House implements IHouse{
    protected $id;
    protected $title;
    protected $photo;
    protected $count;
    protected $places;
    protected $price;

    public function Create($house)
    {   
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($house, $db, 'create')) {
            $createHouseQuery = $db->prepare('CALL spCreateHouse (?, ?, ?, ?, ?, ?)');
            $createHouseQuery->execute(array($house->id, $house->title, $house->photo, $house->count, $house->places, $house->price));
        }
    }

    public function Update($house)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        if ($this->CheckDublicates($house, $db, 'UPDATE')) {
            if ($house->photo ?? '') {
                $updateHouseQuery = $db->prepare('CALL spUpdateHouse(?, ?, ?, ?, ?, ?)');
                $updateHouseQuery->execute(array($house->title, $house->photo, $house->count, $house->places, $house->price, $house->id));
            } else {
                $updateHouseQuery = $db->prepare('CALL spUpdateHouseMinPhoto (?, ?, ?, ?, ?)');
                $updateHouseQuery->execute(array($house->title, $house->count, $house->places, $house->price, $house->id));
            }
        }
    }

    public function Delete($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deleteHouseQuery = $db->prepare('CALL spDeleteHouse(?)');
        $deleteHouseQuery->execute(array($id));
    }

    public function Get($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $getHouseQuery = $db->prepare('SELECT * FROM vhouses WHERE id = ?');
        $getHouseQuery->execute(array($id));
        $house = $getHouseQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($house) == 1) {
            return $house;
        } else {
            echo('Отзыв не найден');
        }
        
    }

    public function Find($title)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $findHousesQuery = $db->prepare('CALL spGetHouseTitle (?)');
        $findHousesQuery->execute(array($title));
        $findHouses = $findHousesQuery->fetchAll(PDO::FETCH_OBJ);
        if (count($findHouses) != 0) {
            return $findHouses;
        } else {
            return false;
        }
    }

    protected function CheckDublicates($house, $db, $switch)
    {
        if ($switch === "create") {
            $dubclicateQuery = $db->prepare('CALL spGetHouseTitle (?)');
            $dubclicateQuery->execute(array($house->title));
            $currentHouse = $dubclicateQuery->fetchAll(PDO::FETCH_OBJ);
            if (!$currentHouse) {
                return true;
            } else {
                echo('Такой отзыв уже существует!');
            }
            
        } else if ($switch === "UPDATE"){
            $dubclicateQuery = $db->prepare('CALL spGetHouseTitle (?)');
            $dubclicateQuery->execute(array($house->title));
            $currentHouse = $dubclicateQuery->fetchAll(PDO::FETCH_OBJ);
            if (count($currentHouse) == 0 || count($currentHouse) == 1) {
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
        $selectHousesQuery = $db->prepare('SELECT * FROM vhouses');
        $selectHousesQuery->execute();
        $houses = $selectHousesQuery->fetchAll(PDO::FETCH_OBJ);
        if ($houses) {
            return $houses;
        } else {
            return false;
        }
    }

    public function Validate($house, $photo)
    {
        if ($this->ValidateTitle($house->title) && $this->ValidatePhoto($photo) && $this->ValidateCount($house->count) &&
            $this->ValidatePlaces($house->places) && $this->ValidatePrice($house->price)) {
            return true;
        }
    }

    public function Set($house, $photo)
    {
        if ($house->id ?? '') {
            $this->id = $house->id;
        } else {
            $this->id = uniqid();
        }
        $this->title = $house->title;
        if ($photo ?? '') {
            $this->photo = base64_encode(file_get_contents($photo['tmp_name']));
        } else {
            $this->photo = $photo;
        }
        $this->count = $house->count;
        $this->places = $house->places;
        $this->price = $house->price;
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
            
            if (substr($_SERVER['HTTP_REFERER'], -33, 9) === 'houseinfo') {
                if (!($post ?? '')) {
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

    protected function ValidatePlaces($places)
    {
        try {
            if ($places ?? '') {
                if (is_numeric($places)) {
                    if ($places > 0 && $places <= 30) {
                        return true;
                    } else {
                        throw new Exception("Length Places Error", 1);
                    }
                } else {
                    throw new Exception("Uncorrect Places Error", 1);
                }
            } else {
                throw new Exception("Empty Places Error", 1);
                
            }
            
        } catch (Exception $error) {
            if ($error->getMessage() == 'Empty Places Error') {
                echo('Вы не ввели количество мест!');
            }

            if ($error->getMessage() == 'Uncorrect Places Error') {
                echo('Количество мест должно состоять из цифр!');
            }

            if ($error->getMessage() == 'Length Places Error') {
                echo('Количество мест должно не должно превышать 30!');
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

interface IHouse {
    function Show();
    function Create($house);
    function Update($house);
    function Delete($id);
    function Get($id);
    function Find($title);
    function Validate($house, $photo);
    function Set($house, $photo);
}

?>