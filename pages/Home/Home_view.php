<div id="home">
  <div class="slider">
    <?php if (isset($v['entradashead'])): ?>
      <?= $v["web"]->render('Slider', $v['entradashead']);?>
    <?php else : ?>
      <?= $v["web"]->msg($v['error_entradashead_msg'],"error");?>
    <?php endif;?>
  </div>
  <div class="lista_proximas">
    <h3> <?= $v["web"]->translate('Home','nextlist') ?></h3>
    <ul>
      <?php if (isset($v['episodes'])): ?>
        <?php foreach ($v['episodes'] as $episode): ?>
          <li class="lista">
            <a class="texto_line" href='<?= $v["web"]->hrefMake("EpisodesDetails&id={$episode['id']}{$episode['urlTemporada']}")?>'>
              <div class="img" style='background: url("<?=$episode['src']?>"), var(--main-grey); background-repeat: no-repeat; background-position: right; background-size: cover;'></div>
              <p class="texto">
                  <?= $episode["anime_titulo"]?> - 
                  <?= $episode["epititulo"]?>
                  <i class="fa fa-play"></i>
                </p>
            </a>
          </li>
        <?php endforeach;?>
      <?php else: ?>
        <?= $v["web"]->msg($v['error_episodes_msg'],"error");?>
      <?php endif; ?>
    </ul>
  </div>
</div>
<div class="home_body">
  <div class="home_body_content">
    <?php if ( $v["web"]->getIsLogged() && isset($v['animes'])) : ?>
      <h3><?= $v["web"]->translate('Home','myanime') ?></h3>
    <?php elseif (isset($v['animes'])) : ?>
      <h3><?= $v["web"]->translate('Home','animetemporada') ?></h3>
    <?php endif;?>
    <?php if (isset($v['animes'])) :?>
      <?= $v["web"]->render("Grid", $v['params']); ?>
    <?php else : ?>
      <?= $v["web"]->msg($v['error_animes_msg'],"error"); ?>
    <?php endif; ?>
  </div>
  <div class="home_body_content generes">
    <?= $v['generes']; ?>
  </div>
</div>
<div class="div" style='display:none;'><?= $v['filters']; ?></div>