<div class="chat">
    <?php if (isset($v['list_users'])): ?>
        <div class="list_users">
            <div class="toolkit" onclick='setcontact()'>
                <div class="back">
                    <i class='fa fa-caret-left'></i>
                </div>
            </div>
            <?php foreach ($v['list_users'] as $key => $value) : ?>
                <div class="user">
                    <div class="info_avatar" onclick='selectcontact(this)'>
                        <div class="avatar">
                            <img src="<?= $value['avatar'] ?>" alt="" srcset="">
                        </div>
                        <div class="info_usuario">
                            <p class='nombre'><?= $value['user']?></p>
                            <p class='estado'><?= $value['nombre']?> - <?= $value['apellidos']?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="toolbar">
        <div class="info_avatar" onclick='setcontact()'>
            <div class="avatar">
                <img src="<?= $v['avatar'] ?>" alt="" srcset="">
            </div>
            <div class="info_usuario">
                <p class='nombre'><?= $v['usuario']?></p>
                <p class='estado'>Prueba de chat</p>
            </div>
        </div>
        <div class="btn_close" onclick='closechat()'><i class="fas fa-times"></i></div>
    </div>

    <div class="lista_mensagues">
    <?php if (isset($v['chat'])) : ?>
        <?php foreach ($v['chat'] as $key => $value) : ?>
        <div class="item">
            <div class="<?= $value['emiitter'] === $v['user'] && $value['receptor'] !== $v['user']   ? 'mymessague': 'message'?>">
                <?= $value['message'] ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay mensages, seleciona otro contacto</p>
    <?php endif;?>
    </div>
    <div class="box_text">
        <input type="text" class='input_enviar' name="mensage" id="mensage" placeholder='Escribe aqui tu mensage'>
        <button class='btn_enviar' onclick='sendmessage()'>
            <i class="fas fa-paper-plane"></i>
        </buttonc>
    </div>
</div>