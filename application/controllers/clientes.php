<?php
/**
 *
 * Cendendt App Web
 *
 * Clase encargada de manejar los clientes de CendeNdt
 * Las acciones (funciones publicas) de esta clase son:
 * Listar Clientes (default)
 * Crear clientes
 * Actualizar Clientes
 * Eliminar Clientes
 * confirmar eliminacion
 * Buscar Clientes 
 * Presentar un Cliente
 * Ver Certificaciones
 * Certificar
 * Contenidos 
 * reglas Formulario
 * Paginacion
 * index 
 *
 * Variables de clase
 * @var $Pagina_ Variable que almacena el html de la Pagina
 * @var $CatalogoVistas Alamcena el nombre de una vista y sus datos
 * @var $Talbla_ nombre de tabla a la que se la van a hacer las consultas
 * @var $Config_ Parametros de configuracion para la paginacion
 * @var $Limit_ Lmite superior de consulta <paginacion desde>
 * @var $Offset_ Limite inferior de consultas <paginacion hasta>
 *
 * @package App Cende Certificados
 * @subpackage  
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio> 
 * @copyright 2012 CendeNdt <info@cendendt.com>
 * @license (c)CendeNdt Todos los derechos reservados 
 * @link http://www.cendendt.com
 * @version 2.0
 * @access public
 */
class Clientes extends CI_Controller{	
	private $Pagina_;
	private $CatalogoVistas_;
	private $Tabla_ = 'clientes';
	private $Config_;
	private $Limit_;	
	private $Offset_ = 50;

	/**
	* Funcion constructora
	* Cargamos archivos necesarios para funcionar
	*/
	public function __construct(){
		parent::__construct();		
		$this->load->library('session')	;
		$this->load->model('m_basedatos');
		$this->load->library('pagination');
		$this->load->library('form_validation');
	}

	/***
	* Se encarga de armar los contenidos, esto en vista de que no se puede usar una variable
	* para toda la clase ya que esta se resetea
	*
	* @return array contenido de las vistas estaticas de esta pagina
	*/
	private function contenidos(){
		$this->CatalogoVistas_ = array(
									'v_acabecera' => array('titulo' => 'Clientes'),
									'v_bmenu' => array('titulo' => 'Clietes Cendendt')									
									);
		return $this->CatalogoVistas_;
	}

	/**
	* Cargamos informacion para la paginacion de mi ¡s clientes
	* 
	* @return un arreglo con las configuraciones de paginacion
	*/
	private function paginacion(){
				$this->Config_['base_url'] = site_url('/clientes/listar');
				$this->Config_['total_rows'] = $this->m_basedatos->Getrows($this->Tabla_);			
				$this->Config_['display_pages'] = '5';
				$this->Config_['per_page'] = $this->Offset_;
				$this->Config_['first_link'] = 'Primero';
				$this->Config_['last_link'] = 'Último';
				$this->Config_['full_tag_open'] = ' <div class="pagination pull-right"><ul>';
				$this->Config_['full_tag_close'] = '</ul></div>';
				$this->Config_['last_tag_open'] = '<li>';
				$this->Config_['last_tag_close'] = '</li>';
				$this->Config_['next_tag_open'] = '<li>';
				$this->Config_['next_tag_close'] = '</li>';
				$this->Config_['num_tag_open'] = '<li>';
				$this->Config_['num_tag_close'] = '</li>';
				$this->Config_['cur_tag_open'] = '<li><a>';
				$this->Config_['cur_tag_close'] = '</a></li>';
				return $this->Config_;
	}

	/**
	* Generamos las reglas de validacion del formulario de clientes
	* Actualizar y Crear se someten a estas reglas
	*/
	private function reglasFormularios(){
		$this->form_validation->set_rules('nombres', 'Nombres', 'trim|required|min_length[2]|max_length[60]|xss_clean');		
		$this->form_validation->set_rules('id', 'Id Cliente', 'trim|xss_clean');		
		$this->form_validation->set_rules('apellidos', 'Apellidos', 'trim|required|min_length[2]|max_length[60]|xss_clean');
		$this->form_validation->set_rules('cedula', 'Cédula', 'trim|required|min_length[9]|max_length[10]|xss_clean');
		$this->form_validation->set_rules('empresa', 'Empresa', 'trim|required|min_length[2]|max_length[60]|xss_clean');
		$this->form_validation->set_rules('pais', 'País', 'trim|required|min_length[2]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('notas', 'Notas', 'trim|min_length[0]|max_length[500]|xss_clean');
	}

	/**
	* Genreamos una lista completa de nuestras vistas y losvalores que ya podemos incluir en ellas
	*/
	public function index(){										
		$this->listar();		
	}

	/**
	* Encagada de listar los clietes se usa paginacion
	*/
	public function listar(){				
		//cargamos el contenido estatico de la pagina
		$catalogo = $this->contenidos();
		$catalogo['v_dform'] = array('controlador' => 'clientes',
								      'columnas' => $this->m_basedatos->GetColumns($this->Tabla_));										
		$campos = array('Id_cliente,
						Cedula,
						Profesion,
						Nombres,
						Apellidos,
						Empresa,
						Pais,
						fecha_Creacion');
		$this->Limit_ = ($this->uri->segment(3) == 0)?0:$this->uri->segment(3);		
		$catalogo['v_etabla'] = array(
									'query' => $this->m_basedatos->Get($campos,$this->Tabla_,'1=1',$this->Limit_,$this->Offset_),
									'columnas' => array('Id', 
														'Cedula', 
														'Profesion',
														'Nombres', 
														'Apellidos',
														'Empresa',
														'Pais',
														'Creacion'
														),
									'controlador' => 'clientes'
									);
		$this->pagination->initialize($this->paginacion());	
		$this->mostrarhtml($catalogo);
	}

	/**
	* Si no estamos recibiendo nada por POST preentamos un formulario en blanco
	* Caso contrario recibimos los datos del formulario y los validamos
	*/
	public function crear(){		
		$catalogo = $this->contenidos();
		if (!$_POST){
			$catalogo['v_eform_cliente'] = array('accion' => 'crear');			
		}
		else{
			$this->reglasFormularios();
			if($this->form_validation->run() == TRUE){
				//enviamos los datos a la tabla
				$datos = array(
								'cedula' => $this->input->post('cedula'),
								'profesion' => strtoupper($this->input->post('profesion')),
								'nombres' => strtoupper($this->input->post('nombres')),
								'apellidos' => strtoupper($this->input->post('apellidos')),
								'empresa' => strtoupper($this->input->post('empresa')),
								'pais' => strtoupper($this->input->post('pais')),
								'notas' => $this->input->post('notas'),
								'fecha_Creacion' => date('Y-m-d H:i:s')
								);
				$this->m_basedatos->Set($this->Tabla_,$datos);
				//mostramos los datos del cliente
				$catalogo['v_eexito']  = array('controlador' => 'certificados'
												);				
			}
			else{
				//mostramos error y devolvemos el formulario
				$catalogo['v_calertas'] =  array('alert' => 'Lo Siento... Uno de los Datos Ingresados Esta Errado Es Demaciado Corto o No Está Ingresado');
				$catalogo['v_eform_cliente'] = array('accion' => 'crear');							
			}

		}	

		$this->mostrarhtml($catalogo);

	}

	/**
	* presenta un formulario con la informacion actual de un cliente para actualizarla
	*
	* @param int $id id único del registro
	*/
	public function actualizar(){		
		$catalogo = $this->contenidos();
		if (!$_POST){						
			$datos = $this->m_basedatos->GetRow($this->Tabla_,'Id_cliente',$this->uri->segment(3));			
			$datos['accion'] = 'actualizar';			
			$catalogo['v_eform_cliente'] = $datos;			
		}
		else{
			$this->reglasFormularios();
			if($this->form_validation->run() == TRUE){
				//enviamos los datos a la tabla
				$datos = array(
								'cedula' => $this->input->post('cedula'),
								'profesion' => strtoupper($this->input->post('profesion')),
								'nombres' => strtoupper($this->input->post('nombres')),
								'apellidos' => strtoupper($this->input->post('apellidos')),
								'empresa' => strtoupper($this->input->post('empresa')),
								'pais' => strtoupper($this->input->post('pais')),
								'notas' => $this->input->post('notas'),
								'fecha_Creacion' => date('Y-m-d H:i:s')
								);
				$this->m_basedatos->Upd($this->Tabla_,$datos,'Id_cliente = ' . $this->input->post('id'));
				//mostramos los datos del cliente
				$catalogo['v_eexito'] = array('controlador' => 'clientes',
												'id' => $this->input->post('id'));				
			}
			else{
				//mostramos error y devolvemos el formulario
				$catalogo['v_calertas'] =  array('alert' => 'Lo Siento... Uno de los Datos Ingresados Esta Errado o es Insuficiente.');
				$catalogo['v_eform_cliente'] = array('accion' => 'actualizar/');							
			}
		}	
		$this->mostrarhtml($catalogo);
	}

	/**
	* Elimina a un cliente de la base de datos aunque este tenga certificados
	* se comprueba el tercer parametro de la url antes de proceder en caso de no existir
	* dicho parametro se llama a la funcion que lista a todos los clientes home
	*/
	public function eliminar(){		
		$catalogo = $this->contenidos();
		$catalogo['v_calertas']	= array('titulo' => 'Eliminar Registro?',
											'alert' => 'Esta Seguro de eliminar este registro?... luego de hacerlo no se 
														podrá recuperar la información' );
		if($this->uri->segment(3) != false){
			$datos['detalles'] = $this->m_basedatos->GetRow($this->Tabla_,'Id_cliente',$this->uri->segment(3));
			//recupermaos informacion adicional del cliente
			$i = $this->m_basedatos->GetRow($this->Tabla_,'Id_cliente',$this->uri->segment(3));
			$datos['nombre'] = $i['Apellidos'] . ' ' . $i['Nombres'];
			$datos['tipo'] = 'Cliente';
			$datos['id'] =  $i['Id_Cliente'];
			$datos['accion'] = 'eliminar';
			$datos['controlador'] = 'clientes';
			$catalogo['v_epresentacion'] = $datos;	

			$this->mostrarhtml($catalogo);
		}else{			
			$this->index();
			}
	}

	/**
	* Confirma la eliminacion de un registro en la base de datos se aplica consultas e cascada, por edfecto del motor
	* de bases de datos
	*/
	public function confirmar(){
		$catalogo = $this->contenidos();
		if($this->uri->segment(3) != false){			
				$where ='Id_Cliente = '. $this->uri->segment(3);
				$this->m_basedatos->Del($this->Tabla_,$where,1);
				$catalogo['v_eexito'] = array('controlador' => 'clientes',
												'id' => '0');
				$this->mostrarhtml($catalogo);			
		}else{			
			$this->index();
			}
	}

	/**
	* busca un cliente apartir de un criterio retorna un resultado en paginacion
	* @var $limit es el limite de resultados que nos retorna la consulta lo vamos a dejar en 100
	*/
	public function buscar(){
		$limit = 100;
		$catalogo = $this->contenidos();		
		if ($_POST){							
		$catalogo['v_dform'] = array('controlador' => 'clientes',
								      'columnas' => $this->m_basedatos->GetColumns($this->Tabla_));										
		$campos = array('Id_cliente,
						Cedula,
						Profesion,
						Nombres,
						Apellidos,
						Empresa,
						Pais,
						fecha_Creacion');
		$this->Limit_ = ($this->uri->segment(3) == 0)?0:$this->uri->segment(3);		
		$catalogo['v_etabla'] = array(
									'query' => $this->m_basedatos->Get($campos,$this->Tabla_, $this->input->post('columna') . " LIKE '" . $this->input->post('criterio') . "%'", 0 ,$limit),                                    
									'columnas' => array('Id', 
														'Cedula', 
														'Profesion',
														'Nombres', 
														'Apellidos',
														'Empresa',
														'Pais',
														'Creacion'
														),
									'controlador' => 'clientes'
									);		
		$this->mostrarhtml($catalogo);
		}else{
			$this->index();
		}

	}

	/**
	* Se muestra toda la informacion de un cliente
	* Se las mismas opciones que en el menu desplegable
	* si el id del cliente no existe se retorna a la pagina principal donde se listan todos
	* ls clientes de la base de datos
	*/
	public function presentar(){		
		$catalogo = $this->contenidos();
		$datos;
		if ($this->uri->segment(3) != false){			
			$datos['detalles'] = $this->m_basedatos->GetRow($this->Tabla_,'Id_cliente',$this->uri->segment(3));
			//recupermaos informacion adicional del cliente
			$i = $this->m_basedatos->GetRow($this->Tabla_,'Id_cliente',$this->uri->segment(3));			
			if (!empty($i)){
				$datos['nombre'] = $i['Apellidos'] . ' ' . $i['Nombres'];
				$datos['tipo'] = 'Cliente';
				$datos['id'] =  $i['Id_Cliente'];
				$datos['accion'] = 'presentar';
				$datos['controlador'] = 'clientes';
				$catalogo['v_epresentacion'] = $datos;			
				$this->mostrarhtml($catalogo);
			}else{
				$catalogo['v_calertas'] = array('titulo' => 'No existe!' ,
												'alert' => 'Este cliente ya no Existe' );
				$this->mostrarhtml($catalogo);
			}
		}else{
			$this->index();
		}		
		
	}	

	/**
	* funcion encargada de mostrar los certificados de las personas
	*
	* Se usa la tabla para mostrar los certificados y un hero-unit para mostrar los datos del cliente
	*/	
	public function certificaciones(){
		$this->Tabla_ = 'v_certificaciones';
		$catalogo = $this->contenidos();
		$datos;
		if ($this->uri->segment(3) != false){			
			//cargamos el contenido estatico de la pagina
		$catalogo = $this->contenidos();		
		$campos = array('id_cc,
						id_registro,
						edision,
						id_certificado,
						codigo,						
						estado_examen,
						fecha_certificacion,
						fecha_vencimiento'
						);
		$this->Limit_ = ($this->uri->segment(3) == 0)?0:$this->uri->segment(3);	
		$i = $this->m_basedatos->GetRow($this->Tabla_,'id_cliente', $this->uri->segment(3));			
		$catalogo['v_ecertificaciones'] = array(
									'query' => $this->m_basedatos->Get($campos,$this->Tabla_,'id_cliente = ' . $this->uri->segment(3) ,0,100),
									'columnas' => array('Id', 
														'Registro', 
														'Edision',														
														'Codigo',														
														'Examen', 
														'Cerificación', 
														'Vencimiento',
														),
									'controlador' => 'clientes',
									'nombre' => $i['nombres'] . ' ' . $i['apellidos'],
									'id_cliente' => $i['id_cliente'],
									'Cedula' => $i['cedula']
									);		
		$this->mostrarhtml($catalogo);
			
		}else{
			$this->index();
		}
	}

	/**
	* Se encrarga de recibir la informacion y genera la pantalla de salia
	* Todos los valores se guardan en una variable de clase $Pagina_	
	* Es este metodo el que decide que vistas mostrar a partir de los paramtros recibidos
	*
	* @param array $catalogo array con las plantillas necesarias y su informacion
	*
	*/
	private function mostrarhtml($catalogo){		
			$vistas;
			$this->Pagina_;
			foreach ($catalogo as $arreglos => $nombres) {								
					$vistas[] =  $arreglos;				
			}
			
			foreach ($vistas as $nombre) {
				$this->Pagina_ = $this->Pagina_ . $this->load->view($nombre,$catalogo[$nombre],true);
			}
			$this->Pagina_ = $this->Pagina_ . $this->pagination->create_links();
			$this->Pagina_ = $this->Pagina_ . $this->load->view('v_fpie','',true);
			print $this->Pagina_;
	}
}