<div class="collection">
    <div class="info">
        <div class="img" style='background: url("<?= $v['collection'][count($v['collection']) - 1 ]['src']?>"), var(--main-grey); background-repeat: no-repeat; background-position: right; background-size: cover;'></div>
        <h2 class="name"><?= $v['collection'][0]['titulo']?></h2>
        <div class="num"><?= count($v['collection']) ?> - <?= $v["web"]->translate("Collection","anime_detail_list_episode") ?></div>
        <div class="info_avatar" onclick="removeall(<?=$v['collection'][0]['id']?>)">
            <i class='fa fa-trash' style='font-size:20px;'></i>
        </div>
        <div class="line"><hr></div>
        <div class="info_avatar">
            <div class="avatar">
                <img src="<?= $v['avatar'] ?>" alt="" srcset="">
            </div>
            <div class="info_usuario">
                <p class='nombre'><?= $v['usuario']?></p>
            </div>
        </div>
    </div>
    <div class="list">
        <ul class="toolbar">
            <a href='<?=$v["web"]->hrefMake("Anime&od=id") ?>' class="tablinks"> <i class="fas fa-sort-down"></i> </a>
            <a href='<?=$v["web"]->hrefMake("Anime&oa=id") ?>' class="tablinks"> <i class="fas fa-sort-up"></i> </a>
            <button class="tablinks active" onclick="setab(event, 'lista', true)"> <i class="fas fa-th"></i></button>
            <button class="tablinks" onclick="setab(event, 'grid', true)"> <i class="fas fa-bars"></i></button>
        </ul>
        <div class="input-episode" style='display:none;'><?= $v['collection'][0]['id'] ?></div>
        <?php foreach ($v['collection'] as $episode): ?>
        <li class="lista" id="<?=$episode['episode_id']?>">
            <a class="texto_line" href="<?=$v["web"]->hrefMake("EpisodesDetails&id={$episode['episode_id']}")?>">
                <div class="img" style='background: url("<?=$episode['src']?>"), var(--main-grey); background-repeat: no-repeat; background-position: right; background-size: cover;'></div>
                <p class="texto">
                <?=$episode["anime_titulo"]?> - 
                <?=$episode["titulo"]?>
                <i class="fa fa-play"></i>
                </p>
            </a>
            <div class="info_avatar" onclick="removeone(<?=$episode['episode_id']?>)">
                    <i class='fa fa-trash' style='font-size:20px;'></i>
                </div>
        </li>
        <?php endforeach;?>
    </div>
</div>