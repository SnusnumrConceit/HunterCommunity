  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<div class="row">
    <div id="demo" class="carousel slide" data-ride="carousel">

        <!-- Indicators -->
        <ul class="carousel-indicators">
            <li data-target="#demo" data-slide-to="0" class="active"></li>
            <li data-target="#demo" data-slide-to="1"></li>
            <li data-target="#demo" data-slide-to="2"></li>
            <li data-target="#demo" data-slide-to="3"></li>
            <li data-target="#demo" data-slide-to="4"></li>
        </ul>

        <!-- The slideshow -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="Media/Images/hunting.jpg" alt="Охота" width="1000" height="500">
                <div class="carousel-caption">
                    <h3>Охота</h3>
                    <p>Текст про охоту!</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="Media/Images/fishing.jpg" alt="Рыбалка" width="1000" height="500">
                <div class="carousel-caption">
                    <h3>Рыбалка</h3>
                    <p>Текст про рыбалку!</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="Media/Images/recreation.jpg" alt="Отдых" width="1000" height="500">
                <div class="carousel-caption">
                    <h3>Отдых</h3>
                    <p>Текст про комфортабельные домики!</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="Media/Images/gallery.jpg" alt="Галлерея" width="1000" height="500">
                <div class="carousel-caption">
                    <h3>Галерея</h3>
                    <p>Насладитесь прекрасными пейзажами нашего охотничьего хозяйства!</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="Media/Images/feed.jpg" alt="Отзывы" width="1000" height="500">
                <div class="carousel-caption">
                    <h3>Отзывы</h3>
                    <p>Оставляйте свои пожелания и впечатление о сервисе! Ваше мнение важно для нас!</p>
                </div>
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#demo" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>

    </div>
</div>
<div class="row">
    <h2 class="col-12">Новости</h2>
    <?php
        require_once 'Classes/New.php';
        $news = News::Show();
        if ($news) {
            $newsLen = count($news);
            for ($i=0; $i < $newsLen; $i++) { 
                echo("
                    <div class='col-6'>
                        <h4>{$news[$i]->Title}</h4>
                        <span class=''>{$news[$i]->Date}</span>
                        <p>{$news[$i]->Description}</p> 
                    </div>
                ");
            }
        } else {
            # code...
        }
        
        
    ?>
</div>