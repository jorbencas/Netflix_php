<?php if ($v["web"]->getIsLogged()) : ?>
    <?php if ($v['modulo'] !== 'User') : ?>
        <div class="info_avatar">
            <div class="avatar">
                <img src="<?= $v['avatar'] ?>" alt="" srcset="">
            </div>
            <div class="info_usuario">
                <p class='nombre'><?= $v['usuario']?></p>
                <form class='estado'>
                    <input type="text" class="input_enviar" placeholder="Añadir comentario" />   
                    <input style='display:none;' type="text" class="input-episode" value="<?= $v['episode']?>" />
                    <input style='display:none;' type="text" class="input-anime" value="<?= $v['anime']?>" />   
                    <div class='btn_enviar' onclick="add()">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                </form>
            </div>
        </div>
    <?php endif;?>

    <?php if (isset($v['comments'])) : ?>
        <ul class="commentarios">
        <?php foreach ($v['comments'] as $key => $comment) : ?>
            <li class="comentario">
                <div class="info_avatar">
                    <div class="avatar">
                        <img src="<?= $comment['avatar'] ?>" alt="<?= $comment['user']?>">
                    </div>
                </div>
                <div class="line">
                    <p class="line_comment"><?= $comment['comment']?></p>
                    <div class="date">
                        <p class=""><?= $comment['fecha']?></p>
                        <p class=""><?= $comment['hora']?></p>
                    </div>
                </div>
                <?php if ($v['modulo'] == 'User') : ?>
                    <div class="info_avatar" onclick="remove(<?=$comment['id']?>)">
                        <i class='fa fa-trash' style='font-size:20px;'></i>
                    </div>
                <?php endif;?>
            </li>
        <?php endforeach; ?>   
        </ul>
    <?php else : ?>
        <div class='wrapper'>
            <?= $v["web"]->msg($v['error_msg'],"info") ?>
        </div>
    <?php endif;?>
<?php else : ?>
    <div class='wrapper'>
        <?= $v["web"]->msg($v['error_msg'],"info") ?>
    </div>
<?php endif;?>