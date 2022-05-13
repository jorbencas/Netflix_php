<div class="contenedor" id="contenedor">
  <div class="tarjeta">
    <div class="slider_banner">
      <div class="banner active" id='banner'>
        <?php foreach ($v['slides'] as $value) : ?>
          <a class="anime" href='<?= $v["web"]->hrefMake("Anime&id={$value['id']}{$value['urlTemporada']}")?>'>
            <article class='first'>
              <img src="<?= $value['src']?>">
              <div class="titulo">
                <h2><?=$value['titulo']?></h2>
              </div>
            </article>
          </a>
        <?php endforeach;?>
      </div>
      <div id="banner-prev" class="flecha-banner anterior" ><span class="fa fa-chevron-left"></span></div>
      <div id="banner-next" class="flecha-banner siguiente"><span class="fa fa-chevron-right"></span></div>
    </div>
  </div>
</div>