<?php 
function Descargas_run($GET, $params, $currentPage, $lang, $POST, $headers) {  

  return $v;
}

function obtain_paises_DAO($url) {
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $file_contents = curl_exec($ch);

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $accepted_response = array(200, 301, 302);
    if (!in_array($httpcode, $accepted_response)) {
      return FALSE;
    } else {
      return ($file_contents) ? $file_contents : FALSE;
    }
}

function obtain_provincias_DAO() {
  $json = array();
  $tmp = array();

  $provincias = simplexml_load_file(RESOURCES . "provinciasypoblaciones.xml");
  $result = $provincias->xpath("/lista/provincia/nombre | /lista/provincia/@id");
  for ($i = 0; $i < count($result); $i+=2) {
      $e = $i + 1;
      $provincia = $result[$e];
      $tmp = array(
          'id' => (string) $result[$i], 'nombre' => (string) $provincia
      );
      array_push($json, $tmp);
  }
  return $json;
}

function obtain_poblaciones_DAO($arrArgument) {
  $json = array();
  $tmp = array();

  $filter = (string) $arrArgument;
  $xml = simplexml_load_file(RESOURCES . 'provinciasypoblaciones.xml');
  $result = $xml->xpath("/lista/provincia[@id='$filter']/localidades");

  for ($i = 0; $i < count($result[0]); $i++) {
      $tmp = array(
          'poblacion' => (string) $result[0]->localidad[$i]
      );
      array_push($json, $tmp);
  }
  return $json;
}

// public function validaeform($values) {
//   $value = $values;
//   $filtro = array(
//       'user' => array(
//           'filter' => FILTER_VALIDATE_REGEXP,
//           'options' => array('regexp' => '/^.{4,20}$/')
//       ),
//       'nombre' => array(
//           'filter' => FILTER_VALIDATE_REGEXP,
//           'options' => array('regexp' => '/^\D{3,30}$/')
//       ),
//       'apellidos' => array(
//           'filter' => FILTER_VALIDATE_REGEXP,
//           'options' => array('regexp' => '/^\D{4,120}$/')
//       ),
//       'email' => array(
//           'filter' => FILTER_CALLBACK,
//           'options' => 'validatemail'
//       ),
//       'password' => array(
//           'filter' => FILTER_VALIDATE_REGEXP,
//           'options' => array('regexp' => '/^.{8,36}$/')
//       ),
//       'date_birthday' => array(
//           'filter' => FILTER_VALIDATE_REGEXP,
//           'options' => array('regexp' => '/\d{2}.\d{2}.\d{4}$/')
//       )
//   );


//   $resultado = filter_var_array($value, $filtro);
//   $user =  trim(htmlspecialchars($value['user']));
//   $nombre =  trim(htmlspecialchars($value['nombre']));
//   $email =  trim(htmlspecialchars($value['email']));
//   $password =  trim(htmlspecialchars($value['password']));
//   // $resultado['provincia'] = $value['provincia'];
//   // $resultado['poblacion'] = $value['poblacion'];
//   // $resultado = validateUsers($resultado);

//   $v['error']['user'] = $this->validate_user($user);
//   $v['error']['nombre'] = $this->validate_nombre($nombre);
//   $v['error']['email'] = $this->validate_email($email);
//   $v['error']['password'] = $this->validate_passowrd($password);

//   $v['resultado'] = !empty($resultado['user']) ? true : false;
//   return $v;
// }

// public function  validate_user($user) {
//   return 'Usuario debe tener de 4 a 20 caracteres';
// }

// public function  validate_nombre($nombre) {
//   return 'El usuario ya existe';
// }

// public function  validate_email($email) {
//   //if (!validatemail($email)) {
//       return 'El email debe contener de 5 a 50 caracteres y debe ser un email valido';
// // }
// }

// public function  validate_passowrd($pasword) {
//   return 'Password debe tener de 4 a 6 caracteres y las dos contrasenyas deben ser iguales';
// }


// public function  validatemail($email) {
//   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
//   if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//       if (filter_var($email, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^.{5,50}$/')))) {
//           return $email;
//       }
//   }
//   return false;
// }

// public function  validate_age($date) {
//   list($día_one, $mes_one, $anio_one) = explode('/', $date);
//   $dateOne = new DateTime($anio_one . "-" . $mes_one . "-" . $día_one);
//   $now = new Datetime('today');
//   $interval = $dateOne->diff($now);
//   return $interval->y;
// }

// public function  validate_dni($dni) {
//   $letra = substr($dni, -1);
//   $numeros = substr($dni, 0, -1);
//   if (substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros % 23, 1) == $letra && strlen($letra) == 1 && strlen($numeros) == 8) {
//       return true;
//   } else {
//       return false;
//   }
// }

  function backfunctionality() {
        // control volver atrás
        $ruta = $_SERVER['REQUEST_URI'];
        if ($_SESSION['ruta_actual'] != $ruta && !isset($GET['backpressed'])) {
          $ruta = $_SESSION['ruta_actual'];
    
          // control 
          if (!isset($_SESSION['back'])) {
            $_SESSION['back'] = [];
            $_SESSION['back'][] = $ruta;
          } elseif (end($_SESSION['back']) != $ruta) {
            $_SESSION['back'][] = $ruta;
          }     
    
          // control de histórico
          $limit_to_back = 10;
          if ( count($_SESSION['back']) > $limit_to_back ) { array_shift($_SESSION['back']); }
        } 
    
        $_SESSION['ruta_actual'] = str_replace("&backpressed={$GET['backpressed']}","",$_SERVER['REQUEST_URI']);
        
  }
?>