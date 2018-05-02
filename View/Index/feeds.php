<div class="row">
    <div class="col">
    <?php
        require_once '../../Classes/Post.php';
        $posts = Post::Show();
        if ($posts) {
            $postsLen = count($posts);
            for ($i=0; $i < $postsLen; $i++) { 
                echo("<article class='col-12'>
                        <h4>{$posts[$i]->Login}</h4>
                        <span>{$posts[$i]->Date}</span>
                        <p>{$posts[$i]->Message}</p>
                    </article>");
            }
        }
    ?>
    </div>
</div>
<div class="row">
    <form method="POST" class="col">
        <div class="form-group col-4">
            <label for="col-form-label">Логин</label>
            <input type="text" class="form-control" id="login">
        </div>
        <div class="form-group col-6">
            <label for="col-from-label">Комментарий</label>
            <textarea rows="20" cols="20" class="form-control" id="comment"></textarea>
        </div>
        <button class="btn btn-primary" id="btn-send" type="button">Отправить</button>
    </form>
</div>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
<script src="Scripts/Index/posts_scripts.js"></script>