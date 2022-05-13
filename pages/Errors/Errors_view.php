<div class="error">
    <div class="container">
        <div class="first">
            <h1><?=$v['code']?></h1>
            <img src="<?= $v["web"]->handleMedia('img', 'no', 'jpg')?>" alt="No se ha podido cargar la imagen" srcset="">
        </div>
        <h4 class='text'> <?=$v['text']?> </h4>
        <a class="link" href="<?=$v["web"]->hrefMake('Home')?>"><?= $v["web"]->translate("Errors",'go') ?></a>
    </div>
</div>