<?php if (isset($v['modules'])): ?>
<div class='breadcrumb'>
    <ul id="breadcrumbs-one">
        <?php foreach ($v['modules'] as $key => $module) : ?>
        <?php if (count($v['modules']) - 1 == $key):?>
            <li class='b_link'>
                <div class="elem current" >
                    <?php if ($v['currentPage'] == 'Admin'): ?> 
                        <i class="fas fa-folder"></i> 
                    <?php endif;?>
                    <?= $module->name?>
                </div>
            </li>
        <?php else: ?>
            <li class='b_link'>
                <a class="elem" href="<?=$module->href?>" >
                    <?php if ($v['currentPage'] == 'Admin'): ?> 
                        <i class="fas fa-folder-open"></i> 
                    <?php else: ?> 
                        <i class='fas fa-list-ul'></i> 
                    <?php endif;?>
                    <?= $module->name?>
                </a>
            </li>
        <?php endif;?>
        <?php endforeach ; ?>
    </ul>
</div>
<?php endif;?>