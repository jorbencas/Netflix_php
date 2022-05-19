<?php if ($v['currentPage'] == 'singin'):?>
    <div class="wrap">
        <div class="contenedor-formulario">
            <?= $v['login'] ?>
        </div>
    </div>
<?php elseif ($v['currentPage'] == 'signup'): ?>
    <div class="wrap">
        <div class="contenedor-formulario">
            <?= $v['singup']?>
        </div>
    </div>
<?php else:?>
    <div class="user">
        <div id='datos' class="tabcontent" style='display:block;'>
            <div class="wrap">
                <div class="contenedor-formulario">
                    <form class="formulario" id='form' action="<?= $v["web"]->hrefMake('User') ?>" method="post" name='formulario_registro' >
                        
                            <input type="text" name="nombre" id="nombre" placeholder='Nombre' value='<?= $v['nombre'] ?>'>

                            <input type="text" name="apellidos" id="apellidos" placeholder='Apellidos' value='<?= $v['apellidos'] ?>'>
                        
                            <input type="email" name="email" id="email" placeholder='Correo Electronico' value='<?= $v['email'] ?>'>
                        
                            <input type="date" name="date_birthday" id="date_birthday" placeholder='Fecha de Cupleaños' value='<?= $v['date_birthday'] ?>'>
                        
                            <input type="text" name="dni" id="dni" placeholder='Dni' value='<?= $v['dni'] ?>'>

                            <input type="text" name="user" id="usuario" placeholder='Usuario' value='<?= $v['usuario'] ?>' readonly style='display:none'>
                            
                            <div class='concret'>
                                <input type='password' name='passwd' id='passwd' value='' placeholder='Contraseña'
                                    autocomplate required>
                                <i class='far fa-eye' onclick='togglepassword()' style='display:none;'></i>
                                <i class='fas fa-eye-slash' onclick='togglepassword()'></i>
                            </div>
                        <div class="input-group  radio">
                            <input type="radio" name="genere" id="hombre" <?=$v['genere'] == 'Hombre' ? 'checked' : ''?>  value="Hombre">
                            <label for="hombre" class='label'>Hombre</label>
                            <input type="radio" name="genere" id="mujer" <?=$v['genere'] == 'Mujer' ? 'checked' : ''?> value="Mujer">
                            <label for="mujer" class='label'>Mujer</label>
                        </div>
                        <div class="input-group">
                            <input type='hidden' name='action' value='updateUser'>
                            <input type="button" class='submit' value="Actualizar" onclick='updateuser()' id='btn-submit'>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>