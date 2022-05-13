


<?php foreach($v['elements'] as $comment ) : ?>
    <?php if ($v['mod'] == 'Anime' || $v['mod'] == 'Edit'): ?>
        <a href='<?=$v["web"]->hrefMake("{$v['mod']}&id={$comment['id']}{$comment['urlTemporada']}")?>' class="grid-anime" id='<?= $comment['id'] ?>'>
            <div class="element_img lazy"
                style='background:url("<?=$comment['src']?>");background-repeat: no-repeat; background-size: cover;'></div>
            <div class="element_kind <?= $comment['kind_class']?>"><?= $comment['kind'] ?></div>
            <div class="<?= $comment['nuevo'] ?>">Nuevo</div>
            <div class="element_text">
                <p class='titulo'><?= $comment["titulo"]?></p>
                <?php if (isset($comment["sinopsis"]))  : ?>
                    <p> <?=$comment["sinopsis"]?></p>
                <?php endif;?>
                <?php if ($v["web"]->getIsLogged() && $v['modulo'] == "Anime")  : ?>
                <div class="serie-header_rating">
                    <div class="star-rating">
                        <?php foreach ($comment['star_valorations'] as $k => $value): ?>
                        <span id="star-<?=$k?>-<?=$comment['id']?>" onclick="setvalorations(<?= $k ?>, <?= $comment['id'] ?>)">
                            <i class="<?=$value?>"></i>
                        </span>
                        <?php endforeach;?>
                    </div>
                    <input type="hidden" class="rating-value" value="<?=$comment['valorations']?>">
                </div>
                <?php endif;?>
                <?php if(isset($comment['temporada'])):?>
                    <ul>
                    <?php if(is_array($comment['temporada'])):?>
                        <?php foreach ($comment['temporada'] as $value):?>
                            <li> <?= $value ?> </li>
                        <?php endforeach;?>
                    <?php else: ?>
                        <li><?=$comment['temporada']?> </li>
                    <?php endif;?>
                    </ul>
                <?php endif;?>
            </div>
        </a>
    <?php else:?>
        <article class="grid-item">
            <img class="image lazy" src="<?=$comment['src'] ?>" alt="<?= $comment[$v['field']] ?>">
            <a href='<?=$v["web"]->hrefMake("{$v['mod']}&id={$comment['id']}{$v['urlTemporada']}") ?>'
                class="overplay">
                <i class="fa fa-play-circle"></i>
            </a>
            <p class="data"><?= $comment['num'] . ' ' . $comment[$v['field']] ?></p>
        </article>
    <?php endif;?>
<?php endforeach; ?>
