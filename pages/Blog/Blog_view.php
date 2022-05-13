<section class="principal contendor">
    <div class="headerland">
    <?php if (isset($v['entradashead'])): ?>
        <?php foreach ($v['entradashead'] as $key => $value) : ?>
            <article class='<?= $value['option'] == 'first' ? 'first' : 'articles' ?>' onclick="hrefedit(this)" data-href="<?=$v["web"]->hrefMake("Entradas&id={$value['id']}") ?>">
                <img src="<?= $value['src']?>" >
                <h2 class='titulo'><?=$value['titulo']?></h2>
            </article>
        <?php endforeach;?>
    <?php elseif (!isset($v['entradashead'])) : ?>
        <p> <?= $v["web"]->msg($v['error_entradashead_msg'],"error")?></p>
    <?php endif;?>
    </div>

    <div class="body_blog">
        <div class="entradas">
        <?php if (isset($v['entradas'])): ?>
            <?php foreach($v['entradas'] as $entrada): ?>
            <article class='elemento_entrada' onclick="hrefedit(this)" data-href="<?=$v["web"]->hrefMake("Entradas&id={$entrada['id']}") ?>">
                <div class="entrada_header" style='background:url("<?= $entrada['src'] ?>");background-repeat: no-repeat; background-size: cover;'></div>
                <div class='elemento_texto'>
                    <h2 class='titulo'><?= $entrada['titulo']?></h2>
                    <p class='fecha'><?= $entrada['fecha']?></p>
                    <p><?= $entrada['descripcion']?></p>
                </div>
            </article>
            <?php endforeach;?>
        <?php elseif (!isset($v['entradas'])) : ?>
            <p> <?= $v["web"]->msg($v['error_entradas_msg'],"error")?></p>
        <?php endif;?>
        </div>

        <div class="informacion">
            <div class="entredas_importantes">
                <h3>Entradas Recientes</h3>
            </div>
            <div class="comentarios_recientes">
                <h3>Comentarios Recientes</h3>
            </div>
            <div class="archivo_blog">
                <h3>Archivos de Blog </h3>
            </div>
        </div>
    </div>
</section>
