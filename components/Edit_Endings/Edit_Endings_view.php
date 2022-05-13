<div tittle='<?=$v['num']?>' id="<?=$v['id']?>"  class='wrap'>
    <div class="contenedor-formulario">
        <form class="formulario">
            <input type="text"  onchange="inputchanges(event.target)" name="id" style='display:none;' placeholder='ID' value='<?=$v['id']?>'>
            <input type="text"  onchange="inputchanges(event.target)" name="nombre" placeholder='Nombre' value='<?=$v['nombre']?>'>
            <input type="text"  onchange="inputchanges(event.target)" name="descripcion" placeholder='Descripción' value='<?=$v['descripcion']?>'>
            <input type="text"  onchange="inputchanges(event.target)" name="anime" value='<?=$v['anime']?>' placeholder='anime' style='display:none;'>
            <input type="text"  onchange="inputchanges(event.target)" style='display:none;' name="num" placeholder='num' value='<?=$v['num']?>'>
            <div class="dropzone">
                <?= $v["web"]->render("Upload", $v['media']);?>
            </div> 
            <div class="input-group">
                <input type='hidden' name='action' value='inserteditOneending'>
                <?php if (isset($params['anime'])) : ?> <input type='button' class='submit' onclick="remove(<?=$v['id']?>)" value='Eliminar' ><?php endif; ?>
                <?php if (isset($params['anime'])) : ?> <input type='button' class='submit' onclick='addform()' value='Añadir mas endings'> 
                <?php else : ?> <input type='button' class='submit' onclick='addform()' style='display:none;' value='Añadir mas endings'> <?php endif; ?>
                <?php if (isset($params['id'])) : ?> <input type='button' class="submit" onclick="openmodal(event, <?= $v['web']->snedDataModal('Video', $v['media']['media'])?>)" value="Ver"><?php endif; ?>
                <input type="button" class='submit' onclick="handledata()" value="<?= $v['text'] ?> ">
                <?php if (!isset($params['anime'])) : ?> <input type='button'  class='submit' onclick="setstep()" value='Next Step' ><?php endif; ?>
            </div>
        </form>
    </div>
</div>