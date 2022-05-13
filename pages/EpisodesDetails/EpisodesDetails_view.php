<section class="episode-page">
    <?= $v["web"]->render('BreadCrumb', $v['episode'])?>
    <div class="element video">
        <div class="element_title">
            <h1><?= $v["page_tittle"]?> <?= $v['episode']['num']  . "-" . $v['titulo'] ?></h1>
        </div>
        <?= $v['web']->render('Video',$v['video']); ?>
        <div class="options">
            <ul class="options">
                <?php if (isset($v['prev'])) echo $v['prev']; ?>
                <?php if (isset($v['next'])) echo $v['next']; ?>
            </ul>
        </div>
    </div>
    <?php if ($v["web"]->getIsLogged() && in_array($v['currentPage'], array('aleatory','EpisodesDetails'))) : ?>
    <div class="element">
        <div class="options">
            <!-- <div class="option">
                <p>Audio: </p>
                <select name="episode_languaje" id="selector episode" onchange='setlang()'>
                    <option value="es">Español</option>
                    <option value="en">Ingles</option>
                    <option value="va">Valencia</option>
                </select>
            </div>
            <div class="option">
                <p>Subtitulos: </p>
                <select name="episode_sub" id="selector episode">
                    <option value="si">Si</option>
                    <option value="no">No</option>
                </select>
            </div> -->
            <div class="option actions">
                <button class='submit' id='remove'><i class="fas fa-minus"></i></button>
                <input type="number" name="cant" id="cant" min='0' max='100' value='0'>
                <button class='submit' id='add'><i class="fas fa-plus"></i></button>
            </div>
            <div class="option actions">
                <button class='submit' type="submit"><i class="fas fa-shopping-cart"></i> Añadir al carrito</button>
            </div>
        </div>
    </div>
    <?= $v["web"]->render('Collections');?>
    <?php endif; ?>
    <?= $v["web"]->render('Comments')?>
</section>
<?php if (isset($v['animes'])): ?>
    <section class="animes_intereseted">
        <h3>Animes que pueden interesarte</h3>
        <?= $v["web"]->render("Grid", $v['params'])?>
    </section>
<?php else: ?>
    <?= $v["web"]->msg($v['error_animes_msg'],"error")?>
<?php endif;?>