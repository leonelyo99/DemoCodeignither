<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
use Restserver\Libraries\REST_Controller;
require( APPPATH.'/libraries/REST_Controller.php');



class Clientes extends REST_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('Cliente_model');
	}


	public function cliente_put(){

		$data = $this->put();

		$this->load->library('form_validation');

		$this->form_validation->set_data($data);
		
		if($this->form_validation->run('cliente_put')){
			$cliente = $this->Cliente_model->set_datos($data);

			$respuesta = $cliente->insert();

			if($respuesta['err']){
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			}else{
				$this->response($respuesta);
			}


		}else{
			
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores eb el envio de la informacion',
				'errores'=> $this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function cliente_post(){
		
		$data = $this->post();
		
		$ciente_id = $this->uri->segment(3);
		
		$this->load->library('form_validation');
		
		$data['id'] = $ciente_id;
		$this->form_validation->set_data($data);
			
		
		if($this->form_validation->run('cliente_post')){
			$cliente = $this->Cliente_model->set_datos($data);

			$respuesta = $cliente->update();

			if($respuesta['err']){
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			}else{
				$this->response($respuesta);
			}


		}else{
			
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores eb el envio de la informacion',
				'errores'=> $this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function paginar_get(){
		$this->load->helper('paginacion');
		
		$pagina 	= $this->uri->segment(3);
		$por_pagina = $this->uri->segment(4);

		$respuesta=paginar_todo('clientes', $pagina, $por_pagina);

		$this->response($respuesta);
	}

	public function cliente_delete(){		
		
		$cliente_id = $this->uri->segment(3);

		$respuesta = $this->Cliente_model->delete($cliente_id);
		
		$this->response($respuesta);
	}

	public function cliente_get(){
		
		$cliente_id = $this->uri->segment(3);

		if (!isset($cliente_id)) {
			$respuesta = array(
				'err'=>TRUE,
				'mensaje'=>'Es necesario el id del cliente'
			);

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}

		$cliente = $this->Cliente_model->get_cliente($cliente_id);

		if(isset($cliente)){
			$respuesta=array(
				'err'=>FALSE,
				'mensaje'=>'Registro cargado correctamente',
				'cliente'=>$cliente
			);

			$this->response($respuesta);
		}else{
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'El registro con el id '.$cliente_id.', no exste',
				'cliente'=>null
			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
}