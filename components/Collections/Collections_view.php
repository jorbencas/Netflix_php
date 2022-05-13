<div class="collections">
<?php if (isset($v['collections']) || isset($v['inputs'])) : ?>
    <?php if ($v['currentPage'] == 'User') : ?>
        <?= $v["web"]->render('Grid', $v['params'])?>
    <?php else : ?>
        <?= $v['inputs']?>
    <?php endif;?>
<?php else : ?>
    <?= $v["web"]->msg($v['error_collections_msg'])?>
<?php endif;?>

<?php if ($v['currentPage'] !== 'User') : ?>
    <form class='estado'>
        <input type="text" class="input_enviar" placeholder="Añade una nueva colección" />   
        <input style='display:none;' type="text" class="input-episode" value="<?= $v['episode']?>" />
        <div class='btn_enviar' onclick="addelement()">
            <i class="fas fa-paper-plane"></i>
        </div>
    </form>
<?php endif;?>
</div>