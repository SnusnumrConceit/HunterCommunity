<?php
    require_once '../../Classes/Fish.php';
    $fishes = Fish::Show();

    if ($fishes) {
        $fishesLen = count($fishes);
        for ($i=0; $i < $fishesLen; $i++) { 
            echo("<div class='card' style='width:400px'>
                    <img class='card-img-top' src='data:image/jpg;base64,{$fishes[$i]->Photo}' alt='Card image'>
                    <div class='card-body'>
                        <h4 class='card-title'>{$fishes[$i]->Title}</h4>
                        <p class='card-text'><span>От {$fishes[$i]->MinPrice}</span><br><span>До {$fishes[$i]->MaxPrice}</span></p>
                    </div>
                </div>");
        }
    } else {
        echo('<div>Раздел заполняется.... Зайдите позже.</div>');
    }
?>