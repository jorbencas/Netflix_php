 <?php
/*
 class B{
 }


 class A{
     public $obj;

     public function  __construct() {
        $this->obj=new B();
    }
     public function  __destruct() {
        echo "FUERA";
    }

 }


 $a=new A();
for($x=0;$x<100;$x++){
    echo "IN";
    $b=clone $a;
}

 exit();
*/

include("../DatabaseObject.class.php");



class Timer{
    static $time=0;
    public static function start(){
        Timer::$time=getMicroTime();
    }

    public static function stop(){
        echo "\n\nTime: ";
        echo (getMicroTime() - Timer::$time) . "\n";
    }
}



function getMicroTime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}



class ordenes extends DatabaseObject{
}





$o=ordenes::findAll();
//    ->where("id_orden",100000, ">=")
//    ->where("id_orden",110000, "<=" );
Timer::start();
$s=0;
foreach($o as $a){
    $s++;
    if($s==40000)break;
}

Timer::stop();
exit();




$limit=40000;
$sql="SELECT *  FROM ordenes LIMIT $limit";


$pdo=new PDO("mysql:dbname=ordenes;host=172.20.1.26", "ordenes", "m3v014c0RR3r");
//$rows=$pdo->query($sql);

$st=$pdo->prepare($sql);
$st->execute();
Timer::start();
while($row=$st->fetch()){}
Timer::stop();


        

exit();


$db=new DBManager();

$s=0;
$a=$db->query("SELECT * FROM ordenes");
Timer::start();
var_dump($a);
foreach($a as $b){
    
    $s++;
    if($s==40000)break;

}
Timer::stop();

echo "----";
exit();


$db=new DBManager();
$s=0;
$db->executeQuery("SELECT * FROM ordenes");
Timer::start();
while ($b=$db->getResultAsObject()){
    $s++;
    if($s==40000)break;
}
Timer::stop();



exit();
//Timer::start();
//for($x=100000; $x<=101000;$x++){
//    $o=new ordenes($x);
//}
//Timer::stop();

Timer::start();
$o=ordenes::findAll();
//    ->where("id_orden",100000, ">=")
//    ->where("id_orden",110000, "<=" );

$s=0;
foreach($o as $a){
    $s++;
    if($s==40000)break;
}
echo $s;
Timer::stop();




exit();





$p=new Orden();

$p=$p->findByGenerada_desde("Instalador")
     ->findByComentario("%Demo%")
     ->findByOpcion_compra(1)
     ->findByFecha("2010-01-01")   ;

foreach($p as $o){
	echo $o->id_orden;
}

$o->id_orden="MAMA";

$orden->cliente->nombre;

exit();





include("DBManager.class.php");
$db=new DBManager();
$a=$db->query("SELECT * FROM personas");
foreach($a as $b){
	foreach($b as $key=>$value){
		echo "Key: $key - Value : $value \n";		
	}
	//var_dump($b);
	//var_dump(array_keys($b));
}


exit();



include("DBObject.class.php");

//class Producto extends DBObject{
//	
//}


class Usuario extends DBObject{
	
}

//EJEMPLO CON EVENTOS
class Perfil extends DBObject{

//	public function setBehaviours(){
//		$this->addBehaviour(BEHAVIOUR::DATEABLE_AT_CREATE);
//	}
	
//	public function beforeAdd(){
//		echo "HOLA";
////		//return false;
//	}	

//	public function afterAdd(){
//		//email("vicente");
//		echo "CHAU";
//	}
}


$a=new Perfil();
$a->hydrate(Usuario);

exit();




$u=Perfil::findByNombre("Prueba%");
echo $u;
foreach($u as $a){
	var_dump($a->nombre);
}

exit();


$a=$u->perfil;



var_dump($a);
exit();

//$a=new Perfil(26);



//var_dump($a->table->data);



//$u->perfil=$a;
//var_dump($u->table->data);
//
//exit();
//EJEMPLO CON EVENTOS
//$a=new Perfil();
//$a->nombre="Pruebas35";
//$a->nivel=10;
//$a->add();
////
//exit();
////
//$u=new Usuario(1);
//var_dump($u->perfil->table->data);
//exit();
//
//
//
//

//
//$a=new Producto();
//$a->count(null, "pepe");
//$a=$a->get();
//var_dump($a->pepe);
//
//
//exit();
//$a->setSufijo("pepe");
////$a->sufijo="pepe";
////$a->mostrar="pepe22wa";
//
////$a->add();
////$a->remove();
////$a->modify();
////$a->save();
//
//var_dump($a->sufijo);
//
//
////CONSULTAS
//
//exit();

//$a=Producto::find()
//	->where("sufijo", "E%S")
//	->orWhere("sufijo", "EM%");

//$a=Producto::findAll();

//foreach($a as $b){
//	echo $b->sufijo . "\n";
//}
//
//
//exit();



//Consulta por id
//$producto=new Producto("ESS");


//Consulta completa



//$productos=Producto::find()
//			->where("sufijo","E%S")
//			->andWhere("sufijo", "EA%");
//
//
//foreach($productos as $a){
//	echo $a->sufijo . "\n";
//}
//
//echo "-------------------------\n";

//Consulta con metodos magicos
//$p=new Producto("ESS");var_dump($p->table->data);
//$productos=Producto::findByEnviar("%Smart%");
//
//
//
//while($a=$productos->get()){
//	echo $a->sufijo . "-" . $a->enviar . "\n";
//}



//
//
//

exit();







//EJEMPLO INSEGURO
//foreach($d->query("SELECT * FROM productos") as $a){
//	var_dump($a["sufijo"]);
//}

//$d->query(Query::insert("productoss")->where("pepe",1));

//foreach($d->query(Query::select("productoss")) as $a){
//	var_dump($a["sufijo"]);
//}



//foreach($d->query(Query::select("productos")
//                 	->where("sufijo", Array("EMS", "EMA"))
//                 )  as $a){
//	var_dump($a["sufijo"]);
//}
//

//EJEMPLO SIMPLE

//$sql="UPDATE productos SET img='BAS' WHERE sufijo='BUS'";

//try{
//$d->beginTransaction();
//var_dump($d->simpleQuery($sql));
//var_dump($d->simpleQuery($sql));
//var_dump($d->simpleQuery($sql));
//$d->beginTransaction();
//var_dump($d->simpleQuery($sql));
//var_dump($d->simpleQuery($sql));
//var_dump($d->simpleQuery($sql));
//;$d->commitTransaction()
//var_dump($d->simpleQuery($sql));
//$d->commitTransaction();
//}catch(Exception $e){
//	$d->rollbackTransaction();
//}


//$sql="SELECT * FROM productos";
//$sql2=$sql . " WHERE sufijo='BUS'";
//$sql3=$sql . " WHERE sufijo=:sufijo";
//$sql4=$sql . " WHERE sufijo=?";




//EJEMPLO DIFERIDO
//$d->setQuery($sql);
//$d->execute();
//while($a=$d->getResult()){
//	var_dump($a);
//}

//EJEMPLO DIFERIDO CON PARAMETROS
//$params=Array();
//$params["sufijo"]="BUS";
//
//$d->setQuery($sql3);
//
//$d->execute($params);
//while($a=$d->getResult()){
//	var_dump($a);
//}
//
////EJEMPLO DE SEGUNDA EJECUCION
//$params["sufijo"]="EMA";
//$d->execute($params);
//while($a=$d->getResult()){
//	var_dump($a);
//}


//EJEMPLO DIFERIDO CON EJECUCION Y QUERY AL MISMO TIEMPO
//$d->executeQuery($sql2);
//while($a=$d->getResult()){
//	var_dump($a["sufijo"]);
//}



//EJEMPLO DIFERIDO CON EJECUCION Y QUERY AL MISMO TIEMPO CON PARAMETROS
//$params=Array();
//$params["sufijo"]="BUS";
//
//$d->executeQuery($sql3, $params);
//while($a=$d->getResult()){
//	var_dump($a["sufijo"]);
//}



//EJEMPLO DIFERIDO CON EJECUCION DIRECTA CON DEVOLUCION DE OBJETO
//$params=Array();
//$params["sufijo"]="BUS";
//
//$d->executeQuery($sql3, $params);
//while($a=$d->getResultAsObject()){
//	var_dump($a->sufijo);
//}

//EJEMPLO DIFERIDO CON EJECUCION DIRECTA CON DEVOLUCION DE OBJETO DEFINIDO
//$params=Array();
//$params["sufijo"]="BUS";
//$d->executeQuery($sql3, $params);
//while($a=$d->getResultAsObject("Pepe")){
//	var_dump($a);
//}



//EJEMPLO DIFERIDO CON ENLACE A VARIABLES
//
//$d->setQuery($sql3);
//
//$sufijo="BUS";
//$d->bind(":sufijo", $sufijo);
//
//$d->execute();
//while($a=$d->getResult()){
//	var_dump($a["sufijo"]);
//}



//EJEMPLO DIFERIDO CON ENLACE A VARIABLES en placeholders
//$sufijo="BUS";
//
//$d->setQuery($sql4);
//
//$d->bind(1, $sufijo);
//$d->bind(2, $sufijo);
//
//$d->execute();
//while($a=$d->getResult()){
//	var_dump($a["sufijo"]);
//}

//EJEMPLO DE EJECUCION CON OBJETO COMO PARAMETROS 
//$pepe=new Pepe();
//$pepe->sufijo="BUS";
//
//$d->setQuery($sql3);
//$d->execute((array)$pepe);
//while($a=$d->getResult()){
//	var_dump($a["sufijo"]);
//}






?>
