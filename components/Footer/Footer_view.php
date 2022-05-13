<div class="footer-list">
    <?php if (isset($v['animes'])): ?>
        <?php foreach ($v['animes'] as $anime): ?>
            <a class='element_container' href='<?= $v["web"]->hrefMake("Anime&id={$anime['id']}{$anime['urlTemporada']}")?>'>
                <div class="element_text"><p><?=$anime["titulo"]?></p></div>
            </a>
        <?php endforeach;?>
    <?php else : ?>
        <?= $v["web"]->msg($v['error_anime_msg'],"error") ?>
    <?php endif;?>

    <!-- <iframe width="300px" height="360px" scrolling="yes" frameborder="0" src="http://www.dailymotion.com/badge/user/kirito-kirigaya3?type=carousel"></iframe> -->
    <ul class='contador'>
        <?php if (isset($v['views'])): ?>
            <?= $v['views'] ?>
        <?php elseif (!isset($v['views'])) : ?>
            <p><?= $v['error_msg']?></p>
        <?php endif;?>
    </ul>
</div>
<div class='footer-logo'>
    <a class='logo' href="<?= $v["web"]->hrefMake('Home')?>"><?= $v["web"]->translate('Footer','titulo')?> 
        2017 - <?= date("Y"); ?>
        <!-- <script>document.write(new Date().getFullYear())</script> -->
    </a>
    <?php if($v["web"]->getDevelop()):?>
        <p><?= $v['fecha_sitio'] ?></p>
    <?php else:?>
        <div class="sidenav">
            <a class='element' href='https://twitter.com/kirito123kazut2'>   
                <span><i class='fab fa-twitter'></i></span>
                <p>Twitter</p>     
            </a>
            <a class='element' href='https://www.facebook.com/profile.php?id=100004654665874&fref=ts'>
                <span><i class='fab fa-facebook'></i></span>
                <p>Facebook</p>        
            </a>
            <a class='element'  href='https://plus.google.com/u/0/'> 
                <span><i class='fab fa-google-plus'></i></span>
                <p>Google Plus</p>       
            </a>
            <a class='element' href='https://www.youtube.com/channel/UCRyM2yRz4eOKi3c66MOfx-Q'>  
                <span><i class='fab fa-youtube'></i></span>
                <p>Youtube</p>      
            </a>
        </div>
    <?php endif; ?>
</div>