<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');


class Pruebasdb extends CI_Controller {

	public function __construct(){
		
		parent::__construct();
		
		$this->load->database();
		
		$this->load->helper('utilidades');
	}

	public function eliminar(){

		$this->db->where('id', 1);
		$this->db->delete('test');

		echo "Se borro ok";		
	}

	public function insert(){
		
		$data = array(
			array(
				'nombre' => 'paco',
				'apellido' => 'perez'
			),
			array(
				'nombre' => 'juan',
				'apellido' => 'perez'
			)
		);
		$this->db->insert_batch('test', $data);

		echo $this->db->affected_rows();
	}

	public function update(){
		$data = array(
			'nombre' => 'facu',
			'apellido' => 'aballay'
		);

		$data = capitalizar_todo($data);

		$this->db->where('id', 2);
		$this->db->update('test', $data);

		echo "Todo ok";
	}


	public function tabla(){
		
		$this->db->select('pais, COUNT(*) as clientes');
		$this->db->from('clientes');
		
		$this->db->group_by('pais');
		$this->db->order_by('pais', 'ASC');

		$query = $this->db->get();


		echo json_encode($query->result());
	}

	public function clientes_beta(){
		
		$query = $this->db->query('SELECT id, nombre, correo FROM clientes');

		$respuesta = array(
			'err'=> FALSE,
			'mensaje'=>'Registros cargados correctamente',
			'total_registros'=> $query->num_rows(),
			'clientes'=> $query->result()
		);

		echo json_encode($respuesta);
	}

	public function cliente($id){
		
		$query = $this->db->query('SELECT id, nombre, correo FROM clientes where id= '.$id);

		$fila = $query->row();

		if(isset($fila)){
			
			$respuesta = array(
				'err'=> FALSE,
				'mensaje'=>'Registro cargado correctamente',
				'total_registros'=> $query->num_rows(),
				'cliente'=> $fila
			);
		}else{
			
			$respuesta = array(
				'err'=> TRUE,
				'mensaje'=>'El registro no se cargo',
				'total_registros'=> $query->num_rows(),
				'cliente'=> null
			);
		}
		echo json_encode($respuesta);
	}
}