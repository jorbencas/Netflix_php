<div class="admin">
    <?php if (isset($v['apis'])) : ?>
    <?php foreach ($v['apis'] as $key => $value) : ?>
        <div class="admin_element <?=$value?>" onclick='getapicals("<?=$value?>",this)'>
            <i class="fas fa-book"></i>
            <p><?= $value ?></p>
        </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class='wrapper'>
        <?= $v["web"]->msg($v['error_msg'],"error") ?>
    </div>
    <?php endif;?>
    <div class="api_list">
        <div class="list">
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['r']</div>
                <div class="text">ruta principal del proyecto</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['id']</div>
                <div class="text">id de detalle</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['p']</div>
                <div class="text">pagina</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['c']</div>
                <div class="text">code del alert</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['t']</div>
                <div class="text">texto del alert</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['f']</div>
                <div class="text">obtener filtros de la url</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['oa']</div>
                <div class="text">obtener listado ordenado ascedentemente</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['od']</div>
                <div class="text">obtener listado ordenado descendentemente</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['fp']</div>
                <div class="text">file path para que el admin filesystem lo escane</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['am']</div>
                <div class="text">opción para cargar el modulo</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['ap']</div>
                <div class="text">parametro de detalle</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['as']</div>
                <div class="text">slides que debe retornar</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['aa']</div>
                <div class="text">opción para obtener algo apartir de un anime</div>
            </div>
            <div class="api_element">
                <div class="method GET">GET</div>
                <div class="url">$_GET['aq']</div>
                <div class="text">se le pasa directamente el nombre de la función ha ejecutar</div>
            </div>
        </div>
    </div>
</div>