<!DOCTYPE html>
<html theme="<?= $v["web"]->getTheme();?>" lang="<?= $v["web"]->getLang()?>">
  <head>
    <base href="<?= "http://{$this->getDomain()}" ?>"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="<?= $v["web"]->handleMedia("img","no","jpg") ?>" />
    <title><?= $v['pageTitle'] ?></title>
    <?php if ($v['currentPage'] !== 'Errors'): ?>
      <link href="<?= $v["web"]->handleMedia("fontawesome","all.min","css") ?>" rel="stylesheet">
      <script src='<?= $v["web"]->handleMedia("libs","jquery","js") ?>'></script>
      <link rel="stylesheet" href="<?= $v["web"]->handleMedia("assets","fonts","css") ?>">
    <?php endif; ?>
    <?php if (in_array($v['currentPage'],array( 'Anime', "Buscador", 'Collections', 'Collection', 
        'aleatory', 'EpisodesDetails', "Home", "Blog", 'OpeningsDetails', 'EndingsDetails', "Entradas"))) : ?>
      <meta property="og:title" content="<?= $v["web"]->translate('Web','titulo') ?> | <?= $v['currentPage'] ?>" />
      <meta name="robots" content="noindextk">
      <link rel="autor" href="humans.txt">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <meta name="author" content="Jorge Beneyto Catelló">
      <meta name=twitter:creator content="@jorbencas" />
      <meta property="og:type" content="website" />
      <meta name="description" content="<?= $v['web']->getKeywords(); ?>">
      <meta name="keywords" content="<?= $v['web']->getMetadescription(); ?>">
      <meta name="theme-color" media="(prefers-color-scheme: dark)" content="black">
      <!-- <meta name="theme-color" media="(prefers-color-scheme: light)" content="white">
      <meta name="theme-color" media="(prefers-color-scheme: dark)" content="black"> -->
      <!-- <meta name="theme-color" content="#ffffff"> -->
      <!-- <meta http-equiv="refresh" content="30; url=<?= $v["web"]->hrefMake("Errors") ?>"> -->
      <?php endif; ?>
    <?= $v['css'] ?>
    <?= $v['js'] ?>
    <?php if (isset($v['open_alert'])): ?>
      <script> <?= $v['open_alert'] ?></script>
    <?php endif; ?>
    <?php if (isset($v['notification'])): ?>
      <script> var notification = "<?= $v['notification']; ?>"; </script>
      <script>soundNotification();</script>
    <?php endif; ?>
  </head>
  <?php if ($v['currentPage'] == 'Errors'): ?>
    <body style= "background:url('<?= $v['web']->handleMedia('img','bg'.mt_rand( 1, 4),'jpg'); ?>'); background-repeat: no-repeat; background-size: cover;">
  <?php else: ?>
    <body>
  <?php endif; ?>
    <noscript>You need to enable JavaScript to run this app.</noscript>
    
    <?php if ($v['currentPage'] == 'Entradas'): ?>
      <div class='progress-container'>
          <div class='progress-bar' id='myBar'></div>
      </div>
    <?php endif; ?>
    <?php if (isset($v['header'])): ?>
      <header> <?= $v['header'] ?> </header>
    <?php endif; ?>
    <?php if (!in_array($v['currentPage'],array('Errors', 'Edit', 'Profiles'))): ?>
      <section class='content-loader'>
        <div class='container-loading'>
          <div class='folding'>
            <div class='sk-cube1 sk-cube'></div>
            <div class='sk-cube2 sk-cube'></div>
            <div class='sk-cube4 sk-cube'></div>
            <div class='sk-cube3 sk-cube'></div>
          </div>
          <div class='texto'><?= $v["web"]->translate('Web','loading') ?> ...</div>
        </div>
      </section>
    <?php endif; ?>
    <main><?=$v['main'] ?></main>
    <?php if (isset($v['footer'])): ?>
      <footer> <?= $v['footer'] ?> </footer>
    <?php endif; ?>
    <?php if (isset($v['buttons'])) { ?>
      <div class="child"> <?= $v['buttons']; ?> </div>
    <?php } ?>
    <?php if ($v['islogged'] && !in_array($v['currentPage'],array('Errors', 'Edit', 'Profiles'))) :?>
      <section class='chat_section' style='display:none;'>
        <?=$v['web']->render('Chat') ?>
      </section>
    <?php endif; ?>
    <?php if ($v['web']->getDevelop() && !in_array($v['currentPage'],array('Errors', 'Edit', 'Profiles'))): ?>
      <pre>
        <div class='pre'>
          <div class='state'>
            <h2>Metodo: GET </h2>
            <?php
              $GET = $v['web']->getGET();
              if (isset($GET['r'])) echo "<p> Ruta: {$GET['r']} </p>";
              if (isset($GET['id'])) echo "<p> Id: {$GET['id']} </p>";
              if (isset($GET['p'])) echo "<p> Pagina: {$GET['p']} </p>";
              if (isset($GET['c'])) echo "<p> Code del alert: {$GET['c']} </p>";
              if (isset($GET['f'])) echo "<p> Filtro: {$GET['f']} </p>";
              if (isset($GET['od'])) echo "<p> Orden Descendente: {$GET['od']} </p>";
              if (isset($GET['oa'])) echo "<p> Orden Ascendente: {$GET['oa']} </p>";
              if (isset($GET['fp'])) echo "<p>  file path para que el admin filesystem lo escane </p>";
              if (isset($GET['profile'])) echo "<p> El profile: {$GET['profile']} </p>";
              if (isset($GET['seasion'])) echo "<p>  La temporada del anime: {$GET['seasion']} </p>";
              if (isset($GET['kind'])) echo "<p> El tipo de anime es: {$GET['kind']} </p>";
            ?>
            <h2>Debuger: </h2>
            <?= stripslashes(json_encode($v['web']->getDebug())) ?>
            <h2>Metodo: POST </h2>
            <?= stripslashes(json_encode($v['web']->getPOST()))?>
            <h2>SESSIONS: </h2>
            <?= stripslashes(json_encode($_SESSION)) ?>
          </div>
        </div>
      </pre>
    <?php endif; ?>
    <?php if (isset($v['modals'])): ?>
      <?= $v['modals'] ?> 
    <?php endif; ?>
    <?php if (!in_array($v['currentPage'],array('Errors', 'Edit', 'Profiles'))): ?>
      <!-- <a href="http://www.contadorvisitasgratis.com" target="_Blank"></a><br />
      <script type="text/javascript" src="http://counter2.fcs.ovh/private/countertab.js?c=928439c3c54d65c5411de22048a97cbe"></script> -->
    <?php endif; ?>
  </body>
</html>