<div class='wrap'>
    <div class="contenedor-formulario">
        <div class="formulario" action="return false">
            <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="id" value='<?= $v['id'] ?>' placeholder='ID:'>
            <input type="text" onchange="inputchanges(event.target)" <?= $v['input-hiden'] ?> class='siglas' name="siglas" value='<?= $v['siglas'] ?>' placeholder='Siglas' required>
            <div id="titulo" class="concret">
                <ul class="tab">
                    <button class="tablink active" onclick="setabform(event,'titulo_es')">ES</button>
                    <button class="tablink" onclick="setabform(event,'titulo_en')">EN</button>
                    <button class="tablink" onclick="setabform(event,'titulo_va')">VA</button>
                    <button class="tablink" onclick="setabform(event,'titulo_ca')">CA</button>
                </ul>
                <input type="text" onchange="inputchanges(event.target)" style='display:block;' name="titulo_es" placeholder='Titulo Español' value='<?= $v['titulo_es'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="titulo_en" placeholder='Titulo Ingles' value='<?= $v['titulo_en'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="titulo_va" placeholder='Titulo Valenciano' value='<?= $v['titulo_va'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="titulo_ca" placeholder='Titulo Catalan' value='<?= $v['titulo_ca'] ?>'>
            </div>
            <div id='sinopsis' class="concret">
                <ul class="tab">
                    <button class="tablink active" onclick="setabform(event,'sinopsis_es')">ES</button>
                    <button class="tablink" onclick="setabform(event,'sinopsis_en')">EN</button>
                    <button class="tablink" onclick="setabform(event,'sinopsis_va')">VA</button>
                    <button class="tablink" onclick="setabform(event,'sinopsis_ca')">CA</button>
                </ul>
                <input type="text" onchange="inputchanges(event.target)" style='display:block;' name="sinopsis_es" placeholder='Sinopsis Español' value='<?= $v['sinopsis_es'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="sinopsis_en" placeholder='Sinopsis Ingles' value='<?= $v['sinopsis_en'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="sinopsis_va" placeholder='Sinopsis Valenciano' value='<?= $v['sinopsis_va'] ?>'>
                <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="sinopsis_ca" placeholder='Sinopsis Catalan' value='<?= $v['sinopsis_ca'] ?>'>
            </div>
            <input type="date" onchange="inputchanges(event.target)" name="date_publication" placeholder='Fecha de Publicación' value='<?= $v['date_publication'] ?>'>
            <input type="date" onchange="inputchanges(event.target)" name="date_finalization" placeholder='Fecha de Finalización' value='<?= $v['date_finalization'] ?>'>
            <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="views" placeholder='views' value='<?= $v['views'] ?>'>
            <input type="text" onchange="inputchanges(event.target)" style='display:none;' name="downloads" placeholder='downloads' value='<?= $v['downloads'] ?>'>
            <div class="input-group">
                <div class="star-rating">
                    <?php foreach ($v['star_valorations'] as $k => $value) : ?>
                        <span id="star-<?= $k ?>" onclick='setvalorations(<?= $k ?>)'>
                            <i class="<?= $value ?>"></i>
                        </span>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="valorations" value='<?= $v['valorations'] ?>'>
            </div>
            <div class="input-group radio">
                <p class='label'>Idiomas: </p>
                <input type="radio" onchange="inputchanges(event.target)" id='es' name="idiomas" <?= $v['idiomas'] == 'es' ? 'checked' : '' ?> value="es">
                <label for='es'>Español</label>
                <input type="radio" onchange="inputchanges(event.target)" id='ca' name="idiomas" <?= $v['idiomas'] == 'ca' ? 'checked' : '' ?> value="ca">
                <label for='ca'>Catalan</label>
                <input type="radio" onchange="inputchanges(event.target)" id='va' name="idiomas" <?= $v['idiomas'] == 'va' ? 'checked' : '' ?> value="va">
                <label for='va'>Valenciano</label>
                <input type="radio" onchange="inputchanges(event.target)" id='ja' name="idiomas" <?= $v['idiomas'] == 'ja' ? 'checked' : '' ?> value="ja">
                <label for='ja'>Japones</label>
            </div>
            <div class="input-group radio">
                <p class='label'>Estado: </p>
                <input type="radio" onchange="inputchanges(event.target)" id='Finalizada' name="state" <?= $v['state'] == 'Finalizado' ? 'checked' : '' ?> value="Finalizado">
                <label for='Finalizada'>Finalizado</label>
                <input type="radio" onchange="inputchanges(event.target)" id='En Emisión' name="state" <?= $v['state'] == 'En Emisión' ? 'checked' : '' ?> value="En Emisión">
                <label for='En Emisión'>En Emisión</label>
                <input type="radio" onchange="inputchanges(event.target)" id='Pendiente' name="state" <?= $v['state'] == 'Pendiente' ? 'checked' : '' ?> value="Pendiente">
                <label for='Pendiente'>Pendiente</label>
            </div>
            <?= $v['kind_list'] ?>
            <?= $v['generes_list'] ?>
            <?= $v['temporada_list'] ?>
            <div class="dropzone">
                <?= $v["web"]->render("Upload", $v['media']); ?>
            </div>
            <div class="input-group">
                <input type='hidden' name='action' value='inserteditOneanime'>
                <?php if (isset($params['id'])) : ?> <input type='button' class='submit' onclick="remove(<?= $v['id'] ?>)" value='Eliminar'><?php endif; ?>
                <?php if (isset($params['id'])) : ?> <a class="submit link" href="<?= $v["web"]->hrefMake("Anime&id={$v['id']}") ?>">Ver</a><?php endif; ?>
                <input type="button" class='submit' onclick="handledata()" value="<?= $v['text'] ?> ">
            </div>
        </div>
    </div>
</div>