<?php foreach ($v['elements'] as $key => $anime):?>
    <?php if($v['mod'] == 'Anime'): ?>
        <a href='<?=$v["web"]->hrefMake("{$v['mod']}&id={$anime['id']}{$anime['urlTemporada']}")?>' class="animes_element" id="<?=$anime['id']?>">
    <?php endif; ?>
        <div class="img">
            <img class="lazy" src="<?= $anime['src']?>" alt="" srcset="">
        </div>
        <div class="info">
        <?php if($v['mod'] == 'Anime'): ?>
            <h3><?= $anime["titulo"] ?></h3>
            <p><?= $anime["sinopsis"] ?></p>
            <ul>
            <?php foreach ($anime['generes'] as $key => $value) : ?>
                <?php if ($key <= 5): ?>
                    <li> 
                        <a href="<?=$v["web"]->hrefMake("Anime&f=generes_{$value['filter']}") ?>">
                            <?=$value['title']?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach;?>
            </ul>
        <?php endif; ?>
        </div>
    <?php if($v['mod'] == 'Anime'): ?>
        </a>
    <?php endif; ?>
<?php endforeach ;?>