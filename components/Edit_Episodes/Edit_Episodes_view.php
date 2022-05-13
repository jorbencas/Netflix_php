<div tittle='<?= $v['num'] ?>' id="<?= $v['id'] ?>" class='wrap'>
    <div class="contenedor-formulario">
        <div class="formulario">
            <input type="text" onchange="inputchanges(event.target)" name="id" style='display:none;' placeholder='ID' value='<?= $v['id'] ?>'>
            <div id="titulo" class="concret">
                <ul class="tab">
                    <button class="tablink active" onclick="setabform(event,'titulo_es')">ES</button>
                    <button class="tablink" onclick="setabform(event,'titulo_en')">EN</button>
                    <button class="tablink" onclick="setabform(event,'titulo_va')">VA</button>
                    <button class="tablink" onclick="setabform(event,'titulo_ca')">Ca</button>
                </ul>
                <input type="text" onchange="inputchanges(event.target)" style='display:block;' name="titulo_es" placeholder='Titulo Español' value='<?= $v['titulo_es'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="titulo_en" placeholder='Titulo Ingles' value='<?= $v['titulo_en'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="titulo_va" placeholder='Titulo Valenciano' value='<?= $v['titulo_va'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="titulo_ca" placeholder='Titulo Catalan' value='<?= $v['titulo_ca'] ?>'>
            </div>
            <div id='sinopsis' class="concret">
                <ul class="tab">
                    <button class="tablink active" onclick="setabform(event, 'sinopsis_es')">ES</button>
                    <button class="tablink" onclick="setabform(event, 'sinopsis_en')">EN</button>
                    <button class="tablink" onclick="setabform(event, 'sinopsis_va')">VA</button>
                    <button class="tablink" onclick="setabform(event, 'sinopsis_ca')">CA</button>
                </ul>
                <input type="text" onchange="inputchanges(event.target)" style='display:block;' name="sinopsis_es" placeholder='Sinopsis Español' value='<?= $v['sinopsis_es'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="sinopsis_en" placeholder='Sinopsis Ingles' value='<?= $v['sinopsis_en'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="sinopsis_va" placeholder='Sinopsis Valenciano' value='<?= $v['sinopsis_va'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="sinopsis_ca" placeholder='Sinopsis Catalan' value='<?= $v['sinopsis_ca'] ?>'>
            </div>
            <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="views" placeholder='views' value='<?= $v['views'] ?>'>
            <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="downloads" placeholder='downloads' value='<?= $v['downloads'] ?>'>
            <input type="text" onchange="inputchanges(event.target)" name="anime" value='<?= $v['anime'] ?>' placeholder='anime' style='display:none;'>
            <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="num" placeholder='num' value='<?= $v['num'] ?>'>
            <div class="dropzone">
                <?= $v["web"]->render("Upload", $v['media']); ?>
            </div>
            <div class="input-group">
                <input type='hidden' name='action' value='inserteditOneepisode'>
                <?php if (isset($params['anime'])) : ?> <input type='button' class='submit' onclick="remove(<?= $v['id'] ?>)" value='Eliminar'><?php endif; ?>
                <?php if (isset($params['anime'])) : ?> <input type='button' class='submit' onclick='addform()' value='Añadir mas episodios'>
                <?php else : ?> <input type='button' class='submit' onclick='addform()' style='display:none;' value='Añadir mas episodios'> <?php endif; ?>
                <?php if (isset($params['id'])) : ?> <input type='button' class="submit" onclick="openmodal(event, <?= $v['web']->snedDataModal('Video', $v['media']['media'])?>)" value="Ver"><?php endif; ?>
                <input type="button" class='submit' onclick="handledata()" value="<?= $v['text'] ?> ">
                <?php if (!isset($params['anime'])) : ?> <input type='button' class='submit' onclick="setstep()" value='Next Step'><?php endif; ?>
            </div>
        </div>
    </div>
</div>