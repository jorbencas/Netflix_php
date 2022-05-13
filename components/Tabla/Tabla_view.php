<table class="table" role="table">
    <?php if($v['mod'] == 'Anime' || $v['mod'] == 'Edit'):?>
        <thead class="table-thead">
            <tr role="rowheader">
                <td role="cell" class="thead-cell" >Titulo</td>
                <td role="cell" class="thead-cell" >Fecha de inicio</td>
                <td role="cell" class="thead-cell" >Fecha de fin</td>
                <td role="cell" class="thead-cell" >Estado</td>
                <td role="cell" class="thead-cell" >Valoraciones</td>
                <td role="cell" class="thead-cell" >Favorito</td>
            </tr>
        </thead>
    <?php endif; ?>
    <tbody class="table-tbody" role="tablist" id="idTableRankingBody">
        <?php foreach ($v['elements'] as $key => $element):?>
            <tr role="row" class="tbody-row" onclick="hrefedit(this)" data-href="<?=$v["web"]->hrefMake("{$v['mod']}&id={$element['id']}{$anime['temporada']}") ?>" >
            <?php if($v['mod'] == 'Anime' || $v['mod'] == 'Edit'):?>
                <td role="cell" class="tbody-cell"><?= $element["titulo"] ?></td>
                <td role="cell" class="tbody-cell"><?= $element['date_publication'] ?></td>
                <td role="cell" class="tbody-cell"><?= $element['date_finalization'] ?></td>
                <td role="cell" class="tbody-cell <?=$element['state_class']  ?>"><?= $element['state'] ?></td>
                <td role="cell" class="tbody-cell" >
                    <div class="serie-header_rating">
                        <div class="star-rating">
                            <?php foreach ($element['star_valorations'] as $k => $value): ?>
                            <span id="star-<?=$k?>-<?=$key?>">
                                <i class="<?=$value?>"></i>
                            </span>
                            <?php endforeach;?>
                        </div>
                        <input type="hidden" class="rating-value" value="<?=$element['valorations']?>">
                    </div>
                </td>
                <td role="cell" class="tbody-cell" >
                    <div class='favorite'> 
                        <i class="<?= $element['head_favorite'] ?> "></i>
                    </div>
                </td>
            <?php elseif($v['mod'] == 'EpisodesDetails') : ?>
                <td role="cell" class="tbody-cell"><i class="fa fa-play"></i></td>
                <td role="cell" class="tbody-cell"><?= $element['num'] ?></td>
                <td role="cell" class="tbody-cell"><?= $element["epititulo"] ?></td>
            <?php elseif($v['mod'] == 'EndingsDetails' || $v['mod'] == 'OpeningsDetails'):?>
                <td role="cell" class="tbody-cell"><?= $element['num'] ?></td>
                <td role="cell" class="tbody-cell"><?= $element["nombre"] ?></td>
                <td role="cell" class="tbody-cell"><?= $element["descripcion"] ?></td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>