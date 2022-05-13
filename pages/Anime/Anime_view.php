<?php if (isset($v["web"]->getGET()['id'])):?>
    <div class="detail-page">
        <div class="banner">
            <img class="banner_img" src="<?=$v['animes']['banner']?>" alt="">
        </div>
        <div class="serie-header">
            <img src=<?=$v['animes']['src']?> alt="<?=$v['animes']["titulo"]?>">
            <section class="serie-header-data">
                <h1 class="serie-header__title"><?=$v['animes']["titulo"]?></h1>
                <section class="serie-description">
                    <article id='sinopsis' text='<?=$v['animes']["sinopsis"]?>'><?=$v['animes']["sinopsis"]?> </article>
                    <label class='mas' onclick="more_less(this)">
                        <?= $v["web"]->translate("Anime",'anime_detail_read') ?>
                        <i class="fas fa-plus"></i>
                    </label>
                </section>
                <div class="serie-header-metadata">
                    <ul class="serie-header_genero">
                        <h3><?= $v["web"]->translate("Anime",'anime_detail_genere')?>: </h3>
                        <ul>
                        <?php foreach ($v['animes']['generes'] as $key => $value) : ?>
                            <li> 
                                <a href="<?=$v["web"]->hrefMake("Anime&f=generes_{$value['filter']}") ?>">
                                    <?=$value['title']?>
                                </a>
                            </li>
                        <?php endforeach;?>
                        </ul>
                    </ul>
                    <ul class="serie-header_genero">
                        <h3><?= $v["web"]->translate("Anime",'anime_detail_state')?></h3>
                        <li class="<?=  $v['animes']['state_class'] ?>">
                            <?=$v['animes']['state']?>
                        </li>
                    </ul>
                    <div class="serie-header_fecha"><i class="fa fa-calendar"> </i><span>
                        <?=$v['animes']['date_publication']?></span></div>
                    <div class="serie-header_fecha"><span>- <?=$v['animes']['date_finalization']?></span></div>
                    <div class="serie-header_genero">
                        <h3><?= $v["web"]->translate("Anime",'anime_detail_kind')?> </h3>
                        <li class="<?= $v['animes']['kind_class']?>"><?=$v['animes']['kind']?></li>
                    </div>
                    <?php if ( $v["web"]->getIsLogged()): ?>
                    <div class="serie-header_rating">
                        <div class="star-rating">
                            <?php foreach ($v['animes']['star_valorations'] as $key_valoration => $value): ?>
                            <span id="star-<?=$key_valoration?>-<?=$key?>">
                                <i class="<?=$value?>"></i>
                            </span>
                            <?php endforeach;?>
                        </div>
                        <input type="hidden" class="rating-value" value="<?=$v['animes']['valorations']?>">
                    </div>
                    <div class='favorite' onclick="setfavorite('<?= $v['animes']['head_favorite']?>', <?= $v['animes']['id']?>, this)">
                        <i class="<?=$v['animes']['head_favorite']?> "></i>
                    </div>
                    <?php endif; ?>
                    <ul class="serie-header_genero">
                        <h3><?= $v["web"]->translate("Anime",'anime_detail_temporada')?>: </h3>
                        <ul>
                        <?php if(is_array($v['animes']['temporada'])):?>
                            <?php foreach ($v['animes']['temporada'] as $value):?>
                                <li> <?= $value ?> </li>
                            <?php endforeach;?>
                        <?php else: ?>
                            <li><?=$v['animes']['temporada']?> </li>
                        <?php endif;?>
                        </ul>
                    </ul>
                </div>
            </section>
        </div>
        <div class="toolbar">
            <ul class="tab">
                <button id='episodes' class="tablinks active" onclick="setabdetail(event, 'episode', false)">
                    <p><?= $v["web"]->translate("Anime",'anime_detail_list_episode')?> ( <?= $v['animes']['num_epis']; ?> )</p>
                </button>
                <button id='openings' class="tablinks" onclick="setabdetail(event, 'opening', false)">
                    <p><?= $v["web"]->translate("Anime",'anime_detail_list_openings')?> ( <?= $v['animes']['num_opes']; ?> )</p>
                </button>
                <button id='endings' class="tablinks" onclick="setabdetail(event, 'ending', false)">
                    <p><?= $v["web"]->translate("Anime",'anime_detail_list_endings')?> ( <?= $v['animes']['num_ends']; ?> )</p>
                </button>
            </ul>
        </div>
    </div>
    <div id='episode' class="tabcontent">
        <?php if (isset($v['episodes'])) : ?>
            <?php if ($v["web"]->getIsMaster()) : ?>
                <?= $v["web"]->render('Tabla', $v['episodesparams'])?>
            <?php else : ?>
                <?= $v["web"]->render('Grid', $v['episodesparams'])?>
            <?php endif; ?>
        <?php else: ?>
            <?= $v["web"]->msg($v['episodes_error_msg'],"warning")?>
        <?php endif; ?>
    </div>
    <div id='opening' class="tabcontent" style='display:none;'>
        <?php if (isset($v['openings'])) : ?>
            <?php if ($v["web"]->getIsMaster()) : ?>
                <?= $v["web"]->render('Tabla', $v['openingsparams'])?>
            <?php else : ?>
                <?= $v["web"]->render('Grid', $v['openingsparams'])?>
            <?php endif; ?>
        <?php else: ?>
            <?= $v["web"]->msg($v['openings_error_msg'],"warning")?>
        <?php endif; ?>
    </div>
    <div id='ending' class="tabcontent" style='display:none;'>
        <?php if (isset($v['endings'])) : ?>
            <?php if ($v["web"]->getIsMaster()) : ?>
                <?= $v["web"]->render('Tabla', $v['endingparams'])?>
            <?php else : ?>
                <?= $v["web"]->render('Grid', $v['endingparams'])?>
            <?php endif; ?>
        <?php else: ?>
            <?= $v["web"]->msg($v['endings_error_msg'],"warning")?>
        <?php endif; ?>
    </div>

    <?php if (isset($v['params'])):?>
        <div class="seasions">
            <h3 class="title"><?= $v["web"]->translate("Anime","anime_detail_seasion"); ?></h3>
            <div class="tabcontent" style='display:block;'>
                <?= $v["web"]->render('Grid', $v['params'])?>
            </div>
        </div>
    <?php endif; ?>
    <?= $v["web"]->render('Comments')?>
<?php else: ?>
    <div class='listanime-page' id="listado_anime">
        <?php if (isset($v['animes'])) : ?>
            <div class="toolbar">
                <?php if (isset($v['num_animes']) && $v['option_paginator'] == 'new') : ?>
                    <ul class="paginator">
                        <p><?= $v['first']?> - <?= $v['last']?> / <?= $v['num_animes']?></p>
                        <li class='page'><a href='<?=$v["web"]->hrefMake("Anime{$v['mod']}&p={$v['prev_page']}") ?>'><i class="fa fa-angle-left"></i></a></li>
                        <li class='page'><a href='<?=$v["web"]->hrefMake("Anime{$v['mod']}&p={$v['next_page']}") ?>'><i class="fa fa-angle-right"></i></a></li>
                    </ul>
                <?php endif; ?>
                <?php if (!$v["web"]->getIsMaster()) : ?>
                    <ul class="tab">
                        <div class="tablinks active" onclick="setab(event, 'lista')"> <i class="fas fa-th"></i></div>
                        <div class="tablinks" onclick="setab(event, 'grid')"> <i class="fas fa-bars"></i></div>
                    </ul>
                <?php endif; ?>
            </div>
            <?php if ($v["web"]->getIsMaster()) : ?>
                <div id='tabla' class="tabcontent" style='display:block;'>
                    <?= $v["web"]->render("Tabla",$v['params']);?>
                </div>
            <?php else : ?>
                <div id='grid' class="tabcontent" style='display:block;'>
                    <?= $v["web"]->render('Grid', $v['params'])?>
                </div>
                <div id='lista' class="tabcontent" >
                    <?= $v["web"]->render("List",$v['params'])?>
                </div>
            <?php endif; ?>
            <?php if ($v['option_paginator'] == 'classic') {
                $GET = $v["web"]->getGET();
                require_once __DIR__ . '/../../classes/paginator.php';
                $pages = new Paginator();
                $pages->items_total = $v['num_animes'];
                $pages->mid_range = 10;
                $pages->items_per_page = $v['maxlimit'];
                $pages->default_ipp = $v['maxlimit'];
                $pages->paginate();
                echo "<div class='fondo_gris_mas_claro' id='div_paginador'>";
                echo "<table id='contenedor_paginador'><tr><td>";
                echo $pages->display_pages();
                echo "<span style='margin-left:5px'>" . $pages->display_jump_menu() . $pages->display_items_per_page() . " </span>";
                echo "</tr></td></table>";
                echo "</div>"; 
            }?>
        <?php else: ?>
        <div class='wrapper'>
            <?= $v["web"]->msg($v['error_msg'],"error") ?>
        </div>
        <?php endif;?>
    </div>
<?php endif;?>