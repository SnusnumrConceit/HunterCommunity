<?php
    require_once '../../Classes/Animal.php';
    $animals = Animal::Show();

    if ($animals) {
        $animalsLen = count($animals);
        for ($i=0; $i < $animalsLen; $i++) { 
            echo("<div class='card' style='width:400px'>
                    <img class='card-img-top' src='data:image/jpg;base64,{$animals[$i]->Photo}' alt='Card image'>
                    <div class='card-body'>
                        <h4 class='card-title'>{$animals[$i]->Title}</h4>
                        <p class='card-text'><span>От {$animals[$i]->MinPrice}</span><br><span>До {$animals[$i]->MaxPrice}</span></p>
                    </div>
                </div>");
        }
    } else {
        echo('<div>Раздел заполняется.... Зайдите позже.</div>');
    }
?>