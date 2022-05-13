<?php if (isset($v['anime'])) : ?>
    <div class="toolbar">
        <ul class="tab">
            <button class="tablinks active anime" onclick="setab(event, 'anime')">
                <?= $v["web"]->translate("Edit",'list_anime')?>
            </button>
            <button class="tablinks episodes" onclick="setab(event, 'episodes')">
                <?= $v["web"]->translate("Edit",'anime_detail_list_episode')?>
            </button>
            <button class="tablinks openings" onclick="setab(event, 'openings')">
                <?= $v["web"]->translate("Edit",'anime_detail_list_openings')?>
            </button>
            <button class="tablinks endings" onclick="setab(event, 'endings')">
                <?= $v["web"]->translate("Edit",'anime_detail_list_endings')?>
            </button>
        </ul>
    </div>
<?php else: ?>
<section class="steps">
<?php foreach ($v['steps'] as $key => $step): ?>
    <div class="content <?= $step['class']?> <?php if ($key == 0) echo 'active'; ?>">
        <article class='step'>
            <div class=""></div>
            <span class=""><?= $step['name']?></span>
            <div class=""></div>
        </article>
        <p><?= $v["web"]->translate("Edit",$step['translate'])?></p>
    </div>
<?php endforeach; ?>
</section>
<?php endif; ?>

<div id='anime' class="tabcontent" style='display:block;'>
    <?= $v["web"]->render('Edit_Anime', $v['anime']); ?>
</div>
<div id='episodes' class="tabcontent" style='display:none;'>
    <div class="form_oculto"><?= $v["web"]->render('Edit_Episodes'); ?></div>
    <div class="list"><div class="child"></div></div>
    <div class="movil_list"><div class="movil_child"></div></div>
    <div class="forms"></div>
    <?php if (isset($v['episode'])) : ?>
        <?php foreach ($v['episode'] as $key => $episode) : ?>
            <div class="list_element" elem='<?=$episode['id'] ?>' onclick="expand(event.currentTarget, 901)">
                <div class="img" style='background: url("<?= $episode['src']?>"); background-size: cover;' ></div>
                <div class="info"><?= $episode['num'] ?></div>
            </div>
            <?= $v["web"]->render('Edit_Episodes', $episode); ?>
        <?php endforeach; ?>
    <?php else : ?>
        <?= $v["web"]->render('Edit_Episodes'); ?>
    <?php endif; ?> 
</div>
<div id='openings' class="tabcontent" style='display:none;'>
    <div class="form_oculto"><?= $v["web"]->render('Edit_Openings'); ?> </div>
    <div class="list"><div class="child"></div></div>
    <div class="movil_list"><div class="movil_child"></div></div>
    <div class="forms"></div>
    <?php if (isset($v['opening'])) : ?>
        <?php foreach ($v['opening'] as $key => $opening) : ?>
            <div class="list_element" elem='<?=$opening['id'] ?>' onclick="expand(event.currentTarget, 701)">
                <div class="img" style='background: url("<?= $opening['src']?>"); background-size: cover;' ></div>
                <div class="info"><?= $opening['num'] ?></div>
            </div>
            <?= $v["web"]->render('Edit_Openings', $opening); ?>
        <?php endforeach; ?>
    <?php else : ?>
        <?= $v["web"]->render('Edit_Openings'); ?>
    <?php endif; ?> 
</div>
<div id='endings' class="tabcontent" style='display:none;'>
    <div class="form_oculto"><?= $v["web"]->render('Edit_Endings'); ?></div>
    <div class="list"><div class="child"></div></div>
    <div class="movil_list"><div class="movil_child"></div></div>
    <div class="forms"></div>
    <?php if (isset($v['ending'])) : ?>
        <?php foreach ($v['ending'] as $key => $ending) : ?>
            <div class="list_element" elem='<?=$ending['id'] ?>' onclick="expand(event.currentTarget, 701)">
                <div class="img" style='background: url("<?= $ending['src']?>"); background-size: cover;' ></div>
                <div class="info"><?= $ending['num'] ?></div>
            </div>
            <?= $v["web"]->render('Edit_Endings', $ending); ?>
        <?php endforeach; ?>
    <?php else : ?>
        <?= $v["web"]->render('Edit_Endings'); ?>
    <?php endif; ?> 
</div>
<div id="all" class='tabcontent all' style='display:none;'>
    <a class="link detail"> <?= $v["web"]->translate("Edit",'see')?></a>
    <a class="link edit"> <?= $v["web"]->translate("Edit",'edit')?></a>
</div>