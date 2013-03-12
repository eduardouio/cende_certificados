<?php
/**
* Cende Ndt App Web
*
 * Clase encargada de manejar los certificados de CendeNdt
 * Las acciones (funciones publicas) de esta clase son:
 * Listar certificados (default)
 * Crear certificados
 * Actualizar certificados
 * Eliminar certificados
 * confirmar eliminacion
 * Buscar certificados 
 * Presentar un Certificado   
 * reglas Formulario
 * Paginacion
 * index funcion que llama a la funcion listar
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
class certificados extends CI_Controller{	
	private $Pagina_;
	private $CatalogoVistas_;
	private $Tabla_ = 'tipos_de_certificados';
	private $Config_;
	private $Limit_;	
	private $Offset_ = 12;

	/**
	* Funcion constructora
	* Cargamos archivos necesarios para la funcionalidad
	*/
	public function __construct(){
		parent::__construct();		
		$this->load->library('session')	;
		$this->load->model('m_basedatos');
		$this->load->library('pagination');
		$this->load->library('form_validation');
	}

	/**
	* Se encarga de armar los contenidos, esto en vista de que no se puede usar una variable
	* para toda la clase ya que esta se resetea
	*
	* @return array contenido de las vistas estaticas de esta pagina junto con los datos dinamicos
	*/
	private function contenidos(){
		$this->CatalogoVistas_ = array(
									'v_acabecera' => array('titulo' => 'Certificados'),
									'v_bmenu' => array('titulo' => 'Certificados Cendendt')									
									);
		return $this->CatalogoVistas_;
	}

	/**
	* Cargamos informacion para la paginacion de mis certificados
	* 
	* @return un arreglo con las configuraciones de paginacion
	*/
	private function paginacion(){
				$this->Config_['base_url'] = site_url('/certificados/listar');
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
	* Generamos las reglas de validacion del formulario de certificados
	* Actualizar y Crear se someten a estas reglas
	*/
	private function reglasFormularios(){
		$this->form_validation->set_rules('codigo', 'Código', 'trim|required|min_length[2]|max_length[10]|xss_clean');				
		$this->form_validation->set_rules('id', 'Id', 'trim|required|xss_clean');				
		$this->form_validation->set_rules('metodo', 'Método', 'trim|required|min_length[2]|max_length[200]|xss_clean');		
	}

	/**
	* Páguina principal de la case esta se encarga de llamar a listar certificados -_-
	*/
	public function index(){										
		$this->listar();		
	}

	/**
	* Encagada de listar los certificados <se usa paginacion>
	*/
	public function listar(){						
		$catalogo = $this->contenidos();
		$catalogo['v_dform'] = array('controlador' => 'certificados',
								      'columnas' => $this->m_basedatos->GetColumns($this->Tabla_));										
		$campos = array('Id_Certificado,
						Codigo,
						Metodo,						
						Fecha_Creacion');
		//en caso de que el limite no exista lo asognamos a cero
		$this->Limit_ = ($this->uri->segment(3) == 0)?0:$this->uri->segment(3);				
		$catalogo['v_etabla'] = array(
									'query' => $this->m_basedatos->Get($campos,$this->Tabla_,'1=1',$this->Limit_,$this->Offset_),
									'columnas' => array('Id', 
														'Código', 
														'Método',														
														'Creación'
														),
									'controlador' => 'certificados'
									);
		$this->pagination->initialize($this->paginacion());	
		$this->mostrarhtml($catalogo);
	}

	/**
	* Funcion encaragada de crear un certificado confirmarlo y de guardarlo
	*
	* Si no estamos recibiendo nada por POST preentamos un formulario en blanco
	* Caso contrario recibimos los datos del formulario y los validamos
	* Este formulario trabaja con dos condiciciones guardadas en la variable accion
	* accion = crear => se direcciona la informacion a la funcion crear
	* accion = actualizar => se direcciona la informacion a la funcion actualizar
	* La accion edtermina el metodo que maneja el formulario -_-
	*/
	public function crear(){		
		$catalogo = $this->contenidos();
		if (!$_POST){			
			$catalogo['v_eform_certificado'] = array('accion' => 'crear');			
		}
		else{
			$this->reglasFormularios();
			if($this->form_validation->run() == TRUE){
				//enviamos los datos a la tabla
				$datos = array(
								'metodo' => strtoupper($this->input->post('metodo')),								
								'codigo' => strtoupper($this->input->post('codigo')),								
								'fecha_Creacion' => date('Y-m-d H:i:s')
								);
				$this->m_basedatos->Set($this->Tabla_,$datos);
				//mostramos los datos del certificado
				$catalogo['v_eexito'] = array('controlador' => 'certificados',
												'id' => $this->m_basedatos->LastId()
												);				
			}
			else{
				//mostramos error y devolvemos el formulario
				$catalogo['v_calertas'] =  array('alert' => 'Lo Siento... Uno de los Datos Ingresados Esta Errado Es Demaciado Corto o No Está Ingresado');
				$catalogo['v_eform_certificado'] = array('accion' => 'crear');							
			}

		}	

		$this->mostrarhtml($catalogo);

	}

	/**
	* Se actualiazan los datos de un certificado a travez de un from web 
	*
	* accion = crear => se direcciona la informacion a la funcion crear
	* accion = actualizar => se direcciona la informacion a la funcion actualizar
	* La accion edtermina el metodo que maneja el formulario -_-
	*
	* @param $this->uri->segment(3) id del certificado a actualizar	
	*/
	public function actualizar(){		
		$catalogo = $this->contenidos();		
		if (!$_POST){						
			$datos = $this->m_basedatos->GetRow($this->Tabla_,'Id_Certificado',$this->uri->segment(3));						
			$datos['accion'] = 'actualizar';			
			$catalogo['v_eform_certificado'] = $datos;			
		}
		else{
			$this->reglasFormularios();
			if($this->form_validation->run() == TRUE){
				//enviamos los datos a la tabla
				$datos = array(
								'metodo' => strtoupper($this->input->post('metodo')),								
								'codigo' => strtoupper($this->input->post('codigo')),								
								'fecha_Creacion' => date('Y-m-d H:i:s')
								);
				$this->m_basedatos->Set($this->Tabla_,$datos);
				//mostramos los datos del certificado
				$catalogo['v_eexito'] = array('controlador' => 'certificados',
												'id' => $this->input->post('id')
												);				
			}
			else{
				//mostramos error y devolvemos el formulario
				$catalogo['v_calertas'] =  array('alert' => 'Lo Siento... Uno de los Datos Ingresados Esta Errado o es Insuficiente.');
				$catalogo['v_eform_certificado'] = array('accion' => 'actualizar/');							
			}
		}	
		$this->mostrarhtml($catalogo);
	}

	/**
	* Elimina a un certificado de la base de datos solamente sino está asignado a ningun cliente
	* 
	* Se usa actualizacion en cascada pero la eliminacion en cascada está restringida
	*
	* se comprueba el tercer parametro de la url antes de proceder en caso de no existir
	* dicho parametro se llama a la funcion que lista a todos los certificados
	*/
	public function eliminar(){		
		$catalogo = $this->contenidos();
		$catalogo['v_calertas']	= array('titulo' => 'Eliminar Registro?',
											'alert' => 'Esta Seguro de eliminar este registro?... luego de hacerlo no se 
														podrá recuperar la información' );
		if($this->uri->segment(3) != false){
			$datos['detalles'] = $this->m_basedatos->GetRow($this->Tabla_,'Id_Certificado',$this->uri->segment(3));
			//recupermaos informacion adicional del cliente
			$i = $this->m_basedatos->GetRow($this->Tabla_,'Id_Certificado',$this->uri->segment(3));
			$datos['nombre'] = $i['Codigo'];
			$datos['tipo'] = 'Certificado';
			$datos['id'] =  $i['Id_Certificado'];
			$datos['accion'] = 'eliminar';
			$datos['controlador'] = 'certificados';
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
				$where ='Id_Certificado = '. $this->uri->segment(3);
				$this->m_basedatos->Del($this->Tabla_,$where,1);
				$catalogo['v_eexito'] = array('controlador' => 'certificados',
												'id' => '0');
				$this->mostrarhtml($catalogo);			
		}else{			
			$this->index();
			}
	}

	/**
	* busca un certificado apartir de un criterio retorna un resultado sin paginacion
	* @var $limit es el limite de resultados que nos retorna la consulta lo vamos a dejar en 100
	*/
	public function buscar(){
		$limit = 100;
		$catalogo = $this->contenidos();		
		if ($_POST){							
		$catalogo['v_dform'] = array('controlador' => 'certificados',
								      'columnas' => $this->m_basedatos->GetColumns($this->Tabla_));										
		$campos = array('Id_Certificado,
						Codigo,
						Metodo,												
						fecha_Creacion');
		$this->Limit_ = ($this->uri->segment(3) == 0)?0:$this->uri->segment(3);		
		$catalogo['v_etabla'] = array(
									'query' => $this->m_basedatos->Get($campos,$this->Tabla_, $this->input->post('columna') . " LIKE '" . $this->input->post('criterio') . "%'", 0 ,$limit),                                    
									'columnas' => array('Id', 
														'Código', 
														'Método',														
														'Creación'
														),
									'controlador' => 'certificados'
									);		
		$this->mostrarhtml($catalogo);
		}else{
			$this->index();
		}

	}

	/**
	* Se muestra toda la informacion de un certificado	
	* si el id del certificado no existe se retorna a la pagina principal donde se listan todos
	* los certificados de la base de datos
	*/
	public function presentar(){		
		$catalogo = $this->contenidos();
		$datos;
		if ($this->uri->segment(3) != false){			
			$datos['detalles'] = $this->m_basedatos->GetRow($this->Tabla_,'Id_Certificado',$this->uri->segment(3));
			//recupermaos informacion adicional del cliente
			$i = $this->m_basedatos->GetRow($this->Tabla_,'Id_Certificado',$this->uri->segment(3));			
			if (!empty($i)){
				$datos['nombre'] = $i['Codigo'];
				$datos['tipo'] = 'Certificado';
				$datos['id'] =  $i['Id_Certificado'];
				$datos['accion'] = 'presentar';
				$datos['controlador'] = 'certificados';
				$catalogo['v_epresentacion'] = $datos;			
				$this->mostrarhtml($catalogo);
			}else{
				$catalogo['v_calertas'] = array('titulo' => 'No existe!' ,
												'alert' => 'Este certificado ya no Existe' );
				$this->mostrarhtml($catalogo);
			}
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