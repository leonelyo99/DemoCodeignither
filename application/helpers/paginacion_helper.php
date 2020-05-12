<?php

function paginar_todo($tabla, $pagina, $por_pagina, $campos =array()){
	$CI =& get_instance();
	$CI->load->database();

	//si el dato viene vacio
	 if(!isset($por_pagina)){
	 	$por_pagina = 20;
	 }
	 if(!isset($pagina)){
	 	$pagina = 1;
	 }

	//cuento todos los registros de la base de datos
	$cuantos = $CI->db->count_all($tabla);

	//veo el total de paginas dividiendo el total de registros por la cantidad que quiero por pagina
	$total_paginas = ceil($cuantos / $por_pagina);

	//si la pagina ingresada es mayor al total de paginas me lleva a la ultima
	if($pagina>$total_paginas){
		$pagina = $total_paginas;
	}

	//sino a pagina le resto uno entonces si estoy en la pagina uno 
	//al restarle 1 me queda 0, 0*por la cantidad que quiero por pagina
	//me da desde donde enpiezo a traer los registros
	$pagina -= 1;
	$desde = $pagina * $por_pagina;

	//si la pagina es mayor o igual al total de ppaginas
	//igualo pagina siguiente al total de pagian
	//sino a la pagina_siguiente le sumo 2
	if($pagina >= $total_paginas - 1){
		$pag_siguiente = $total_paginas;
	}else{
		$pag_siguiente = $pagina + 2;
	}

	//si pagina es menor a 1 entonces pagina anterior es igual a uno 
	//sino es igual a pagina ya que a pagina anteriormente ya le habia restado 1
	if($pagina < 1){
		$pag_anterior = 1;
	}else{
		$pag_anterior = $pagina;
	}

	//hago la query con los que quiero por pagina y el desde que lo
	//saco de multiplicar a pagina a la cantidad que quiero por pagina
	$CI->db->select($campos);
	$query = $CI->db->get($tabla, $por_pagina, $desde);

	//creo la respuesta y la muestro
	$respuesta =  array(
		'err'=> FALSE,
		'cuantos'=> $cuantos,
		'total_pagina' =>$total_paginas,
		'pag_actual'=>($pagina + 1),
		'pag_siguiente' =>$pag_siguiente,
		'pag_anterior' => $pag_anterior,
		$tabla => $query->result()
	);

	return $respuesta;
}

?>