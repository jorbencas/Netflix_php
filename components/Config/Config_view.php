<form class="configuration">
    <div class="elem">
        <p>Web claro:</p>
        <label class="switch">
            <input type="checkbox"  onchange="inputchanges(event.target)" <?= $v['config']['theme'] == true ? 'checked' : '' ?> name='theme' value='<?= $v['config']['theme'] ?>'  onchange="inputchanges(this)" >
            <span class="slider round"></span>
        </label>
    </div>
    <div class="elem">
        <p>Idioma a selecionar:</p>
        <ul id='langs' class='langs'>
            <li onclick='specialelem("es", "lang", "langs", this)' class='list_element <?= $v['config']['lang'] == 'es' ? 'active': '';?> '>
                <div class='link'>Español</div>
            </li>
            <li onclick='specialelem("en", "lang", "langs", this)' class='list_element <?= $v['config']['lang'] == 'en' ? 'active': '';?> '>
                <div class='link'>Ingles</div>
            </li>
            <li onclick='specialelem("ca", "lang", "langs", this)' class='list_element <?= $v['config']['lang'] == 'va' ? 'active': '';?> '>
                <div class='link'>Catalan</div>
            </li>
            <li onclick='specialelem("va", "lang", "langs", this)' class='list_element <?= $v['config']['lang'] == 'ca' ? 'active': '';?> '>
                <div class='link'>Valenciano</div>
            </li>
        </ul>
        <input type="hidden"  onchange="inputchanges(event.target)" name="lang" value='<?= $v['config']['lang']?>'>
    </div>
    <div class="elem">
        <p>Seleciona el orden:</p>
        <select name='orden' id='speed' class='speed'  onchange="inputchanges(this)">
            <option <?= $v['config']['orden'] == 'ASC' ? 'selected': '';?>  value='ASC'>Ascendente</option>
            <option <?= $v['config']['orden'] == 'DESC' ? 'selected': '';?>  value='DESC'>Descendete</option>
        </select>
    </div>
    <div class="elem">
        <p>Seleciona el orden de los episodios</p>
        <select name='ordenepi' id='speed' class='speed'  onchange="inputchanges(this)">
            <option <?= $v['config']['ordenepi'] == 'ASC' ? 'selected': '';?> value='ASC'>Ascendente</option>
            <option <?= $v['config']['ordenepi'] == 'DESC' ? 'selected': '';?> value='DESC'>Descendete</option>
        </select>
    </div>
    <div class="elem">
        <p>Activar el autoplay en las videos</p>
        <label class="switch">
            <input type="checkbox"  :  name='autoplay' <?= $v['config']['autoplay'] == true ? 'checked' : ''; ?> value='<?= $v['config']['autoplay'] ?>'  onchange="inputchanges(this)">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="elem">
        <p> Volumen por defecto</p>
        <input type="range" id="progress"  :  name='vol' class="progress" min="0" max="1" step="0.1" value="<?= $v['config']['vol'] ?>"  onchange="inputchanges(this)">
    </div>
    <div class="elem">
        <p>Activar el chat:</p>
        <label class="switch">
            <input type="checkbox"  :  name='avable_chat' <?= $v['config']['avable_chat'] == true ? 'checked' : ''; ?>  value='<?= $v['config']['avable_chat']?>' onchange="inputchanges(this)">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="elem">
        <p>Activar el historial:</p>
        <label class="switch">
            <input type="checkbox"  :  name='avable_history' <?= $v['config']['avable_history'] == true ? 'checked' : ''; ?>  value='<?= $v['config']['avable_history']?>' onchange="inputchanges(this)">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="elem">
        <p>Numero de columnas por defecto</p>
        <div id='columns' class="columns">
            <div onclick='specialelem("1","columns","columns", this)' class="column <?= $v['config']['columns'] == 1 ? 'active' : '';?>">1</div>
            <div onclick='specialelem("2","columns","columns", this)' class="column <?= $v['config']['columns'] == 2 ? 'active' : '';?>">2</div>
            <div onclick='specialelem("3","columns","columns", this)' class="column <?= $v['config']['columns'] == 3 ? 'active' : '';?>">3</div>
        </div>
        <input type="hidden" name="columns" value='<?= $v['config']['columns']?>'>
    </div>
    <div class="elem">
        <p>Limite para lo elementos de la coleción</p>
        <div id='limit' class="columns">
            <div onclick='specialelem("100","limit_elem_collection", "limit", this)' class="column <?= $v['config']['limit_elem_collection'] == 100 ? 'active' : '';?>">100</div>
            <div onclick='specialelem("500","limit_elem_collection", "limit", this)' class="column <?= $v['config']['limit_elem_collection'] == 500 ? 'active' : '';?>">500</div>
            <div onclick='specialelem("1000","limit_elem_collection", "limit", this)' class="column <?= $v['config']['limit_elem_collection'] == 1000 ? 'active' : '';?>">1000</div>
        </div>
        <input type="hidden" name="limit_elem_collection" value='<?= $v['config']['limit_elem_collection']?>'>
    </div>
    <div class="elem">
        <p>Vista por defecto en elistado de animes</p>
        <div id='view' class="columns">
            <div onclick='specialelem("grid","default_view", "view", this)' class="column <?= $v['config']['default_view'] == 'grid' ? 'active' : '';?>">Grid</div>
            <div onclick='specialelem("lista","default_view", "view", this)' class="column <?= $v['config']['default_view'] == 'list' ? 'active' : '';?>">List</div>
        </div>
        <input type="hidden" name="default_view" value='<?= $v['config']['default_view']?>'>
    </div>

    <div class="input-group">
        <input type='hidden' name='action' value='setconfig'>
        <input type="button" class='submit' value="Guardar" onclick='saveconfig()' id='btn-submit'>
    </div>
</form>