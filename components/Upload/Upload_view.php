<div class='upload'>
    <?=$v['SinID'];?>
    <div id="sortableupload" <?=$v['upload_div'];?>>
        <table borde=0>
            <?=$v['li'];?>
        </table>
    </div>
    <div class="buttons">
        <div class="button" onclick='clickselectfoto()' ><i class="fas fa-cog"></i></div>
        <div class="button" onclick='clickcreateform()'><i class="fas fa-cloud-upload-alt"></i></div>
    </div>
</div>