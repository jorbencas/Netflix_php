<div class="history">
    <div class="list">
    <?php if (isset($v['history'])) : ?>
    <?php foreach ($v['history'] as $episode): ?>
        <li class="lista" id="<?=$episode['id']?>">
            <a class="texto_line" href='<?= $v["web"]->hrefMake("EpisodesDetails&id={$episode['episode_id']}")?>'>
                <div class="img"
                    style='background: url("<?=$episode['src']?>"), var(--main-grey); background-repeat: no-repeat; background-position: right; background-size: cover;'>
                </div>
                <p class="texto">
                    <?=$episode["anime_titulo"]?> -
                    <?=$episode["titulo"]?>
                    <i class="fa fa-play"></i>
                </p>
            </a>
            <div class="info_avatar" onclick="removeone(<?=$episode['id']?>)">
                <i class='fa fa-trash' style='font-size:20px;'></i>
            </div>
        </li>
    <?php endforeach;?>
    <?php else : ?>
        <?= $v["web"]->msg($v['error_history_msg'])?>
    <?php endif;?>
    </div>
</div>