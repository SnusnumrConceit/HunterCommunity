  <style>
  /* Make the image fully responsive */
  .carousel-inner img {
      width: 100%;
      height: 100%;
  }
  #demo {
      width:100%;
      margin:auto;
  }
  </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>


<?php 
    require_once '../../Classes/Photo.php';
    $pictures = Photo::Show();
    if ($pictures) {
        $picturesLen = count($pictures);
        $carouselIndicators = '';
        $carouselItems = '';
        for ($i=0; $i < $picturesLen; $i++) { 
            $carouselIndicators .= "<li data-target='#demo' data-slide-to='{$i}'></li>";
            if ($i != 0) {
                $carouselItems .= "<div class='carousel-item'><img src='data:image/jpg;base64,{$pictures[$i]->Photo}' width='800' height='500'></div>";
            } else {
                $carouselItems .= "<div class='carousel-item active'><img src='data:image/jpg;base64,{$pictures[$i]->Photo}' width='800' height='500'></div>";
            }
        }
        $carousel = '<div id="demo" class="carousel slide col-8" data-ride="carousel">' . '
                        <ul class="carousel-indicators">'. $carouselIndicators .'</ul>'. 
                        '<div class="carousel-inner">' . $carouselItems . '</div>' . 
                        '<a class="carousel-control-prev" href="#demo" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next" href="#demo" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>';
    }
    echo($carousel);

?>  
  
    
