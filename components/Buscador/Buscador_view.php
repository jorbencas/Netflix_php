<div class='<?=$v['class']?>'>
<form <?= $v['chekcFunction']?> action='<?=$v['web']->hrefMake('Buscador')?>' method='post'>
        <input type='text' class='form-control' placeholder='<?=$v['placeholder']?>' onchange='<?=$v['function']?>(this)' />   
        <input type='hidden' name='action' value='handlesearch' />   
        <input type='hidden' name='search' value='' />   
        <input type='hidden' name='kind' value='letters' />   
        <input type='hidden' name='profile' value='<?=$v['profile']?>' />   
        <input type='hidden' name='limit' value='0_2' />   
        <button type='submit' class='search_icon'> 
            <i class='fa fa-search'></i>
        </button>
    <div class='lista_resultados'>
        <?=$v['results']?>
    </div>
</form>
</div>