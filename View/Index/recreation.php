<div class="row">
    <p>Расположение:  Уютные деревянные дома расположены в деревне Маханы, вдали от шумных магистралей, в окружении соснового и смешанного леса, на  берегу  реки Медведицы – самой чистой реки в Европейской части России, в 80 км от Твери и 260 км от МКАД.</p>
    <p>Размещение: База состоит из деревянных, экологичных строений.</p>
</div>
    <div class="row">
        <?php
            require_once '../../Classes/House.php';
            $houses = House::Show();
            if ($houses) {
                $housesLen = count($houses);
                for ($i=0; $i < $housesLen; $i++) { 
                    echo("<div class='card' style='width:400px'>
                    <img class='card-img-top' src='data:image/jpg;base64,{$houses[$i]->Photo}' alt='Card image'>
                    <div class='card-body'>
                        <h4 class='card-title'>{$houses[$i]->Title}</h4>
                        <p class='card-text'><span>Кол-во: {$houses[$i]->Count}</span></p>
                        <p class='card-text'><span>Места: {$houses[$i]->Places}</span></p>
                        <p class='card-text'><span>Стоимость: {$houses[$i]->Price} руб.</span></p>
                        
                    </div>
                </div>");
                }
            } else {
                echo('Данный раздел редактируется!');
            }
        ?>
    </div>  
    <h3>Наш автопарк</h3>
    <div clas="row">
        <?php
            require_once '../../Classes/Car.php';
            $cars = Car::Show();
            if ($cars) {
                $carsLen = count($cars);
                for ($i=0; $i < $carsLen; $i++) { 
                    echo("<div class='card' style='width:400px'>
                    <img class='card-img-top' src='data:image/jpg;base64,{$cars[$i]->Photo}' alt='Card image'>
                    <div class='card-body'>
                        <h4 class='card-title'>{$cars[$i]->Title}</h4>
                        <p class='card-text'><span>Кол-во: {$cars[$i]->Count}</span></p>
                        <p class='card-text'><span>Стоимость: {$cars[$i]->Price} руб.</span></p>
                        
                    </div>
                </div>");
                }
            } else {
                echo('Данный раздел редактируется!');
            }
            
        ?>        
    </div>
    <div class="row">
        <p>Питание: Мы рады предложить Вам блюда, приготовленные из экологически чистых продуктов, полученных в собственном подсобном хозяйстве.  Мы рады учесть Ваши пожелания  при составлении меню.
По вашей просьбе наши повара приготовят и подадут к столу добытую вами дичь или свежепойманную рыбу.
Для любителей готовить самостоятельно у нас имеется коптильня, мангал, печь для барбекю, русская печь.</p>

<p>Развлечения:
На территории охотбазы расположены оборудованные места отдыха: беседки, летняя кухня, мангал
Русская баня с купанием в пруду или проруби.
После бани мы можем Вам предложить самовар душистого травяного чая с медом из собственной пасеки.</p>
    </div>
    <h3>Оснащение базы</h3>
    <div class="row">
        <?php
            require_once '../../Classes/Security.php';
            $security = Security::Show();
            if ($security) {
                $securityLen = count($security);
                echo('<table class="table table-bordered col-md-6">
                        <thead>
                            <th>Местность</th>
                            <th>Численность охранников</th>
                        </thead>
                        <tbody>');
                for ($i=0; $i < $securityLen; $i++) { 
                    echo("<tr>
                            <td>{$security[$i]->Title}</td>
                            <td>{$security[$i]->Count}</td>
                        </tr>");
                }
                echo('</tbody');
            } else {
                echo('Данный раздел редактируется!');
            }
            
        ?>
    </div>
