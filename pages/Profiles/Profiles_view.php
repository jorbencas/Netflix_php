<div class="user">
<?php if (isset($v['profile'])):?>
    <div class="information">
        <div class="ed-information">
            <div class="serie-header">
                <img  src=<?= $v['avatar'] ?> alt="<?= $v['usuario'] ?>">
                <div class="serie-header-data">
                    <h1 class="serie-header__title"><?=$v['usuario']?></h1>
                </div>
            </div>
        </div>
    </div>

    <ul class="tab">
        <button class="tablinks personage" onclick="setab(event, 'personage')">
            <?= $v["web"]->translate("Profiles",'auth_animes')?>
        </button>
        <button class="tablinks favorites" onclick="setab(event, 'favorites')">
            <?= $v["web"]->translate("Profiles",'auth_favorites')?>
        </button>
        <button class="tablinks" onclick="setab(event, 'config')">
            <?= $v["web"]->translate("Profiles",'auth_config')?>
        </button>
        <button class="tablinks" onclick="setab(event, 'history')">
            <?= $v["web"]->translate("Profiles",'auth_history')?>
        </button>
        <button class="tablinks comments" onclick="setab(event, 'comments')">
            <?= $v["web"]->translate("Profiles",'auth_comments')?>
        </button>
        <button class="tablinks news" onclick="setab(event, 'news')">
            <?= $v["web"]->translate("Profiles",'auth_news')?>
        </button>
    </ul>
    
    <div class="dropzone">
        <?= $v["web"]->render("Upload",$v['media']); ?>
    </div> 

    <div id='personage' class="tabcontent" style='display:none;'>
        <?= $v["web"]->render('Collections')?>
    </div>
    <div id='favorites' class="tabcontent" style='display:none;'>
        <?php if (isset($v['favorites'])) : ?>
            <?= $v["web"]->render('Grid', $v['params'])?>
        <?php else : ?>
            <?= $v["web"]->msg($v['error_favorites_msg'])?>
        <?php endif;?>
    </div>
    <div id='config' class="tabcontent" style='display:none;'>
        <?=$v["web"]->render('Config', $v["web"]->getUserConfig())?>
    </div>
    <div id="history" class="tabcontent" style='display:none;'>
        <?= $v["web"]->render("History") ?>
    </div>
    <div id='comments' class="tabcontent" style='display:none;'>
        <?= $v["web"]->render('Comments')?>
    </div>
    <div id='newa' class="tabcontent" style='display:none;'>
        <?= $v["web"]->render('Blog')?>
    </div>
    <div id='notifications' class="tabcontent" style='display:none;'>
        <?= $v["web"]->render('Notifications')?>
    </div>
<?php elseif (isset($v['profiles'])): ?>
    <div class="list">
        <?php foreach ($v['profiles'] as $value) : ?>
            <div class="admin_element" id="<?= $value['id'] ?>" onclick="setprofile(<?= $value['id'] ?>)">
                <div class="img">
                    <div class="letter"><?= $value['capital_letter'] ?></div>
                </div>
                <p class="text"><?= $value['nombre'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <form class="addform concret" id="acces" action="<?= $v["web"]->hrefMake('Profiles')?>" method="post">
        <input type="hidden" name="action" value="setprofile">
        <input type="hidden" name="id_profile" value="">  
        <button class="link" type='submit'>
            <?=$v["web"]->translate("Profiles","acces")?>
            <i class="far fa-arrow-alt-circle-right"></i>
        </button>
    </form>
<?php else: ?>
    <div class='wrapper'>
        <?= $v["web"]->msg($v['error_msg'],"error") ?>
    </div>
<?php endif;?>
    <h3><?= $v["web"]->translate("Profiles","addprofile")?></h3>
    <form class="addform concret" action="<?= $v["web"]->hrefMake('Profiles') ?>" method="post">
        <input type="text" class="text" name="profile" value="" placeholder="<?= $v["web"]->translate("Profiles","placeholder")?>">
        <input type="hidden" name="action" value="inserteditprofile">
        <input type="hidden" name="username" value="<?= $v['username'] ?>">  
        <button class='add_icon' type='submit'> 
            <i class='fa fa-plus'></i>
        </button>
    </form>
</div>