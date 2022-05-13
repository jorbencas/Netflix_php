<?php if (isset($v['langs'])): ?>
    <section class="header">
        <ul class="langs">
            <?= $v['langs']?>
        </ul>
    </section>
<?php endif; ?>
<div class="menu_bar">
    <div class="bt-menu"><i class="fas fa-bars"></i></div>
    <?php if ($v["web"]->getisSelectedProfile()) : ?>
        <a class='link user_item' href='<?=$v["web"]->hrefMake("User") ?>'>
            <img src="<?= $v['avatar'] ?>" alt='<?= $v['profile'] ?>'>
            <span class='texto'><?= $v['profile'] ?></span>
        </a>
        <a class='link cart' href='<?=$v["web"]->hrefMake("Cart")?>'>
            <span class="badge"><?= $v['number_products']?></span>
            <i class="fas fa-shopping-cart"></i>
        </a>
        <form class='link logout' method='POST' action='<?=$v["web"]->hrefMake($_SERVER["REQUEST_URI"], false)?>' >
            <input type='hidden' name='username' value='<?=$v['usuario']?>'>
            <input type='hidden' name='action' value='logout'>
            <button type='submit' class='button'> 
                <i class='fas fa-sign-out-alt'></i> <span class='texto'><?= $v["web"]->translate('Header', 'salir')?></span>
            </button>
        </form>
    <?php else : ?>
        <a class='link' href='<?=$v["web"]->hrefMake("Login") ?>'>
            <i class="far fa-user-circle"></i>
            <span class='texto'><?= $v["web"]->translate('Header','iniciar_sesion') ?></span>
        </a>
        <a class='link' href='<?=$v["web"]->hrefMake("Register") ?>'>
            <i class="far fa-user"></i>
            <span class='texto'><?= $v["web"]->translate('Header','registro') ?></span>
        </a>    
    <?php endif; ?>
    <?php if (isset($v['langs'])): ?>
    <section class="header">
        <ul class="langs">
            <?= $v['langs']?>
        </ul>
    </section>
<?php endif; ?>
</div>
<nav id='navbar'>
    <?= $v['menu']?>
</nav>
<?php if (!in_array($v['currentPage'],array("Edit", 'signup', 'singin', "ComingSoon", "Apidoc", "Filesystem", 
"Events", "Blog", "Cart", "Entradas", 'Profiles', "User", 'Showerrors', 'Backup', 'Buscador'))) : ?> 
    <?php if (isset($v['filters'])): ?>
        <div class="filters">
            <ul class='menu' role="menu">
                <?php foreach ($v['letters'] as $filter): ?>
                <li>
                    <a class="link" role="menuitem" href="<?=$v["web"]->hrefMake("Anime&f=letters_{$filter['filter']}") ?>">
                        <?=$filter['title']?>
                    </a>
                </li>
                <?php endforeach;?>
                <?php foreach ($v['filters'] as $filter): ?>
                <li class="letra-link collapsed" id='<?=$filter['id']?>' onclick="tooglefilter(`<?=$filter['id']?>`)"
                    title="<?= $v["web"]->translate("Header",$filter['title'])?>">
                    <div class='link' role="button">
                        <?= $v["web"]->translate("Header",$filter['title'])?> 
                        <!-- ↓ -->
                    </div>
                </li>
                <?php endforeach;?>
            </ul>
            <ul class="list" id="g-list">
                <?php if (!empty($v['generes'])): ?>
                <?php foreach ($v['generes'] as $genere): ?>
                <li>
                    <a class='link' href="<?=$v["web"]->hrefMake("Anime&f=generes_{$genere['filter']}") ?>">
                        <?=$genere['title']?>
                    </a>
                </li>
                <?php endforeach;?>
                <?php endif;?>
            </ul>
            <ul class="list" id="y-list">
                <?php if (!empty($v['years'])): ?>
                <?php foreach ($v['years'] as $years): ?>
                <li>
                    <a class='link' href="<?=$v["web"]->hrefMake("Anime&f=years_{$years['filter']}") ?>">
                        <?=$years['title']?>
                    </a>
                </li>
                <?php endforeach;?>
                <?php endif;?>
            </ul>
            <ul class="list" id="k-list">
                <?php foreach ($v['kinds'] as $kinds): ?>
                <li>
                    <a class='link' href="<?=$v["web"]->hrefMake("Anime&f=kinds_{$kinds['filter']}") ?>">
                        <?=$kinds['title']?>
                    </a>
                </li>
                <?php endforeach;?>
            </ul>
            <ul class="list" id="i-list">
                <?php if (!empty($v['languajes'])): ?>
                <?php foreach ($v['languajes'] as $languaje): ?>
                <li>
                    <a class='link' href="<?=$v["web"]->hrefMake("Anime&f=languajes_{$languaje['filter']}") ?>">
                        <?=$languaje['title']?>
                    </a>
                </li>
                <?php endforeach;?>
                <?php endif;?>
            </ul>
            <ul class="list" id="t-list">
                <?php if (!empty($v['temporadas'])): ?>
                <?php foreach ($v['temporadas'] as $temporada): ?>
                <li>
                    <a class='link' href="<?=$v["web"]->hrefMake("Anime&f=temporadas_{$temporada['filter']}") ?>">
                        <?=$temporada['title']?>
                    </a>
                </li>
                <?php endforeach;?>
                <?php endif;?>
            </ul>
        </div>
    <?php endif;?>
<?php endif; ?>
<?php if ($v['isMaster'] && $v['currentPage'] !== "Edit") : ?>
    <div class="lateralbar">
        <div class="icons">
            <div class="element">
                <i class="fas fa-plus"></i>
            </div>
            <div class="element">
                <i class="fas fa-users"></i>
            </div>
            <div class="element">
                <i class="fas fa-database"></i>
            </div>
            <div class="element">
                <i class="fas fa-folder"></i>
            </div>
            <div class="element">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="element">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="element">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="element">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
        <div class="sidenav">
            <a class='element' href='<?=$v["web"]->hrefMake("Edit")?>'>
                <span><?=$v["web"]->translate('Header','lateral_editar') ?></span>
            </a>
            <a class='element' href='<?=$v["web"]->hrefMake("User") ?>'>
                <span><?=$v["web"]->translate('Header','users') ?></span>
            </a>
            <a class='element' href='<?=$v["web"]->hrefMake("Backup") ?>'>
                <span><?=$v["web"]->translate("Header", "backup")?></span>
            </a>
            <a class='element' href='<?=$v["web"]->hrefMake("Filesystem") ?>'>
                <span><?=$v["web"]->translate("Header", "files")?></span>
            </a>
            <a class='element' href='<?=$v["web"]->hrefMake("Showerrors") ?>'>
                <span><?=$v["web"]->translate("Header", "errors")?></span>
            </a>
            <a class='element' href='<?=$v["web"]->hrefMake("Apidoc") ?>'>
                <span><?=$v["web"]->translate("Header", "apidoc")?></span>
            </a>
            <a class='element' href='<?=$v["web"]->hrefMake("Apidoc") ?>'>
                <span><?=$v["web"]->translate("Header", "apidoc")?></span>
            </a>
            <a class='element' href='<?=$v["web"]->hrefMake("Apidoc") ?>'>
                <span><?=$v["web"]->translate("Header", "apidoc")?></span>
            </a>
        </div>
    </div>
<?php elseif($v["web"]->getDevelop() && $v["web"]->getCurrentMod() !== 'EpisodesDetails') : ?>
    <div class="lateralbar">
        <div class="icons">
            <div class="element">
                <i class="fas fa-file"></i>
            </div>
        </div>
        <div class="sidenav">
            <button class='element' onclick='openmodal(event, <?= $v["web"]->snedDataModal("Video", $v["testModals"]) ?>)'>
                <span><?=$v["web"]->translate('Header','lateral_editar') ?></span>
            </button>
        </div>
    </div>
<?php endif; ?>