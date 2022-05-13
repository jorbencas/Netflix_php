<div class="admin">
    <div class='list'>
        <?php if ($v['currentPage'] == 'Filesystem'): ?>
            <div class="elem">
                <?= $v["web"]->render('BreadCrumb', $v['file_list'][0]->path)?>
            </div>
            <div class="manage" >
            <?php if (isset($v['file_list'])): ?>
                <?php foreach ($v['file_list'] as $key => $value) : ?>
                    <?php if ($value->file_kind == 'dir') :?>
                    <a class="admin_element" href="<?= $v["web"]->hrefMake("Filesystem&fp=$value->path") ?>" >
                    <?php else: ?>    
                    <div class="admin_element">
                    <?php endif; ?>
                        <?php if ($value->file_kind == 'dir' ): ?>
                        <i class="fas fa-folder"></i>
                        <?php elseif ($value->file_kind == 'video' ) : ?>
                        <i class="fas fa-file-video"></i>
                        <?php elseif ($value->file_kind == 'img' ) : ?>
                        <i class="fas fa-file-image"></i>
                        <?php else : ?>
                        <i class="fas fa-file-archive"></i>
                        <?php endif; ?>
                        <p><?= $value->file ?></p>
                    <?php if ($value->file_kind == 'dir') :?>
                        </a>
                    <?php else: ?>    
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class='wrapper'>
                    <?= $v["web"]->msg($v['file_error_list'],"error") ?>
                </div>
            <?php endif; ?>
            </div>
        <?php elseif ($v['currentPage'] == 'Showerrors'): ?>
        
        <?php elseif ($v['currentPage'] == 'Backup'): ?>
            <div class="elem">
                <p><?= $v["web"]->translate("Admin",'import');?></p>
                <label class="switch">
                    <input type="checkbox" onchange="inputchanges(this)" name='theme' >
                    <span class="slider round"></span>
                </label>
                <p><?= $v["web"]->translate("Admin",'export');?></p>
            </div>
            <button class='btn' onclick="click_admin()">
                <p><?= $v["web"]->translate("Admin",'exec');?></p>
            </button>
            <div class="manage">
            <?php foreach ($v['data'] as $key => $value) : ?>
                <div class="admin_element <?= $value['class']?>" id="<?= $value['id']?>">
                    <i class="fas fa-database"></i>
                    <p><?= $value['siglas'] ?></p>
                    <!-- <div class='bar'></div>
                    <div class="percent"> 0% </div> -->
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif;?>
    </div>
</div>