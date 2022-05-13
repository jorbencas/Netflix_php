<?php 
    function Modals_run($web, $params) {
        $v['modal_header'] = "";
        $v['modal_footer'] = "";
        $v['params'] = "";
        if (isset($params['kind']) && $params['kind'] == 'Video') {
            $v['modal_header'] = "<div class='modal_header'>
                <i onclick='closemodal()' class='fas fa-times'></i>
            </div>";

            $data = $params['data'];
            $lang = isset($data['lang']) ? $data['lang'] : null;
            $v['params'] = $web->render($params['kind'],array(
                'img' => $web->handleMedia("img","no","jpg"),
                'src' => $web->handleMedia($data['type'],$data['name'],$data['ext'],$data['siglas'],$lang)
            ));
        } else if(isset($params['kind']) && $params['kind'] == 'Personage'){
            $v['modal_header'] = "<div class='modal_header'>
                <i onclick='closemodal()' class='fas fa-times'></i>
            </div>";

            $v['params'] = $web->render($params['kind'],$params['data']);
        } else if(isset($params['kind']) && $params['kind'] == 'Entradas') {
            $v['modal_footer'] = "
            <div class='modal_footer'>
                <div class='total'>
                    <div class='titulo'>Total Iva</div> 
                    <div class='valor'>16.3 €</div>
                </div> 
                <div class='total_envio'> 
                    <div class='envio_gratuito'>Envio gratuito</div> 
                </div> 
                <div class='total_recargo'>
                    <div class='titulo'>Total recargo</div> 
                    <div class='valor'>4.04 €</div>
                </div> 
                <div class='total_pedido'>
                    <div class='titulo'>Total Pedido</div>
                        <div class='valor'>97.95 € </div>
                    </div>
                </div>
            </div>";
            $v['params'] = "<div class='modals modal_lista' onclick='openmodal(event, `Modals_Importador`,1)'>
                <div class='img'>
                    <img  src='" .$web->handleMedia("img","no","jpg"). "' alt='Modals_Importador'>
                </div> 
                <strong> Modals_Importador </strong>
            </div>";
        } else if(isset($params['kind']) && $params['kind'] == 'Profiles') {
            $v['modal_header'] = "<div class='modal_header'>
            <h3>Seleciona tu perfil </h3> 
                <i onclick='closemodal()' class='fas fa-times'></i>
            </div>";
            $v['params'] = $web->render($params['kind']);
        } else if(isset($params['kind']) && $params['kind'] == 'Cookies') {
            $v['params'] = $web->render($params['kind']);
        } else {
            $v['modal_header'] = "<div class='modal_header'>
            <h3>Modales </h3> 
                <i onclick='closemodal()' class='fas fa-times'></i>
            </div>";
        }
        return $v;
    };
?>