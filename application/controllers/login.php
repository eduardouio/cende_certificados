<?php
/**
 * 
 * Clase principal de la aplicacion
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access public
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 2.0
 *
 */
class Login extends CI_Controller{
	//variables que contienen la pagina y la estructura de la misma a
	//asi como tambien lleban el control de la seguridad
	private static $Data_ = array();
	private $SessionUser_;
	private $SessionPass_;
	private $SessionId_;
	private $Pagina_ ;

	/**
	* Funcion constructora
	*/
	public function __construct(){
				parent::__construct();	
				$this->load->library('session')	;
		}

	/**
	* Carga datos principales de la pagina como titulo 
	* y foco en los menus
	*/	
	private function informacion(){
		self::$Data_['titulo'] = 'Identfíquese...';	
		self::$Data_['ok'] = '<div class="hero-unit container">Bienvendido, Si no puede ingresar Click 
						<a class="btn btn-info"href="'.	base_url() . 'index.php/clientes">Aquí</a></div>';
	}		

	/**
	* Pagina principal de la clase
	*/
	public function index(){	
		//destruimos posibles datos de session 
		$this->session->sess_destroy();
		//construimos informacion inportante
		$this->informacion();

		$this->Pagina_ = $this->load->view('v_acabecera',self::$Data_,true);
		$this->Pagina_ = $this->Pagina_ . $this->load->view('v_login','',true);
		$this->Pagina_ = $this->Pagina_ . $this->load->view('v_fpie','',true);
		print $this->Pagina_;

	}
	/**
	* Validamos los datos del usuario contra la base de datos
	*/
	public function identificar(){		
		//construimos informacion inportante
		$this->informacion();

		$usuario;
		$pass;

		$this->SessionUser_ = $this->input->post('usuario');
		$this->SessionPass_ = $this->input->post('pass');
		$usuarios = $this->db->get('usuarios');

		foreach ($usuarios->result() as $key) {
		 	$usuario =  $key->Usuario;
			$pass = $key->Pass;
		}
		//caso en el que el usuario y la contraseña coinciden
		if (($usuario == $this->SessionUser_)&&($pass == $this->SessionPass_)):

			$sesion_data = array(
								'user' => $usuario,
								'estado' => 'loged'
								);
			$this->session->set_userdata($sesion_data);

			$this->Pagina_ = $this->load->view('v_acabecera',self::$Data_);
			print (self::$Data_['ok']);
			$this->Pagina_ = $this->Pagina_ . $this->load->view('v_fpie','');	
			
		//cuando no coincide el usuario y/o la contraseña
		else:					
			self::$Data_['error'] = '<div class="alert"> Lo Siento El <b>Usuario</b> o La <b>Contraseña</b> 
									No Coinciden, Intentelo Nuevamente...</div>';	
			$this->Pagina_ = $this->load->view('v_acabecera',self::$Data_,true);
			$this->Pagina_ = $this->Pagina_ . $this->load->view('v_login',self::$Data_,true);
			$this->Pagina_ = $this->Pagina_ . $this->load->view('v_fpie','',true);			
		print $this->Pagina_;
		endif;
	}
	
	public function salir(){
		$this->index();
	}
}

