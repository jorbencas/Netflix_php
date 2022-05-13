<p>Accepta las cokkies del sitio, para que podamos realizar las pertinentes gestiones para poder ofrecerte una mejor experiencia en <?= $v["web"]->translate("Web",'titulo') ?></p>
<div class="p">
    <a class='button' href="<?=$v["web"]->hrefMake("misInfo")?>"><?= $v['web']->translate('Modals_Cookie', 'mas_info')?></a>
    <a class='button' onclick="createCokkies('<?=$v['web']->getApiToken()?>')"><?= $v['web']->translate('Modals_Cookie','accept')?></a>
</div>
