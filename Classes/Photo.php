<?php
class Photo implements IPhoto{
    protected $id;
    protected $photo;

    public function Create($photo)
    {   
        require_once 'DbConnect.php';
        $db = DbConnect();
        $createPhotoQuery = $db->prepare('CALL spCreatePhoto (?, ?)');
        $createPhotoQuery->execute(array($photo->id, $photo->photo));
    }

    
    public function Delete($id)
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $deletePhotoQuery = $db->prepare('CALL spDeletePhoto (?)');
        $deletePhotoQuery->execute(array($id));
    }

    function Show()
    {
        require_once 'DbConnect.php';
        $db = DbConnect();
        $selectPhotosQuery = $db->prepare('SELECT * FROM vgallery');
        $selectPhotosQuery->execute();
        $photos = $selectPhotosQuery->fetchAll(PDO::FETCH_OBJ);
        if ($photos) {
            return $photos;
        } else {
            return false;
        }
    }

    public function Validate($photo)
    {
        try {
            if (is_uploaded_file($photo['tmp_name'])) {
                if($photo['size'] < 2*1024*1024) {
                    $ext = substr($photo['name'], -3, 3);
                    $arrExts = ['jpg', 'png', 'JPG', 'PNG'];
                    if (in_array($ext, $arrExts)) {
                        return true;
                    } else {
                        throw new Exception('Extension Photo Error', 1);
                    }
                } else {
                    throw new Exception("Size Photo Error", 1);
                    
                }
            } else {
                throw new Exception('Uploaded Photo Error', 1);
            }
            
        } catch (Exception $error) {
            if ($error->getMessage() === 'Uploaded Photo Error') {
                echo("Вы не загрузили фотографию!");
            }
            if ($error->getMessage() === 'Size Photo Error') {
                echo("Размер фотографии не может превышать 2 МБайт!");
            }
            if ($error->getMessage() === 'Extension Photo Error') {
                echo("Фотография должна быть в формате .jpg или .png!");
            }
        }
    }

    public function Set($photo)
    {
        $this->id = uniqid();
        $this->photo = base64_encode(file_get_contents($photo['tmp_name']));
        return $this;
    }
}

interface IPhoto {
    function Show();
    function Create($photo);
    function Delete($id);
    function Validate($photo);
    function Set($photo);
}

?>