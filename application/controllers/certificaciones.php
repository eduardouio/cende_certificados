<?php
/**
* Cende Ndt App Web
*
 * Clase encargada de manejar las certificaciones de los clientes
 * Las acciones (funciones publicas) de esta clase son: 
 * crear certificaciiones
 * actualizar certificaciones 
 * eliminar cerificaciones
 * confirmar eliminacion certificacion 
 * Presentar una certificacion  
 * reglas Formulario 
 * index llama a 404
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
class Certificaciones extends CI_Controller{	
	private $Pagina_;
	private $CatalogoVistas_;
	private $Tabla_ = 'clientes_certificados';		
	private $Offset_ = 12;

	/**
	* Funcion constructora
	* Cargamos archivos necesarios para la funcionalidad
	*/
	public function __construct(){
		parent::__construct();		
		$this->load->library('session')	;
		$this->load->model('m_basedatos');		
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
									'v_acabecera' => array('titulo' => 'Certificaciones'),
									'v_bmenu' => array('titulo' => 'Certificaciones')									
									);
		return $this->CatalogoVistas_;
	}
	
	/**
	* Generamos las reglas de validacion del formulario de certificados
	* Actualizar y Crear se someten a estas reglas
	*/
	private function reglasFormularios(){
		$this->form_validation->set_rules('id_cc', 'id certificacion', 'trim|required|min_length[1]|max_length[10]|xss_clean');
		$this->form_validation->set_rules('id_cliente', 'id cliente', 'trim|required|min_length[1]|max_length[10]|xss_clean');		
		$this->form_validation->set_rules('id_registro', 'id registro', 'trim|required|min_length[5]|max_length[12]|xss_clean');
		$this->form_validation->set_rules('edision', 'edision', 'trim|min_length[2]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('fecha_certificacion', 'fecha de certificacion', 'trim|required|min_length[10]|max_length[15]|xss_clean');
		$this->form_validation->set_rules('fecha_vencimiento', 'fecha de vencimiento', 'trim|required|min_length[10]|max_length[15]|xss_clean');
		$this->form_validation->set_rules('certificado', 'certificado', 'trim|required|min_length[2]|max_length[100]|xss_clean');
		$this->form_validation->set_rules('examinacion_general', 'examinacion general', 'trim|required|min_length[1]|max_length[3]|xss_clean');
		$this->form_validation->set_rules('examinacion_especifica', 'examinacion especifica', 'trim|required|min_length[1]|max_length[3]|xss_clean');
		$this->form_validation->set_rules('examinacion_parcial', 'examinacion parcial', 'trim|required|min_length[1]|max_length[3]|xss_clean');
		$this->form_validation->set_rules('examen', 'examen', 'trim|required|min_length[2]|max_length[20]|xss_clean');
	}

	/**
	* Páguina principal de la case esta se encarga de llamar a listar certificados -_-
	*/
	public function index(){										
		show_404();
	}
	
	/**
	* Funcion encaragada de crear una certificacion hacia un cliente el id del cliente es recibida
	* como paramatreo a travez de la URI
	* El metodo usado para certificar a una persona sigue los siguientes pasos:
	* 1.- se elige a un cliente <de cualquier forma>
	* 2.- se selecciona el menú de certificar o crear certificado
	* 3.- se presenta un formulario en blanco para la nueva certificacion
	* 4.- en el listado de certificaciones disponibles no se muestran las que el cliente ya tiene
	* 5.- Se muestra un listado con los nombres de los metodos 
	* 5a. El id de la certificaicon se lo toma tratando la cadena el formato de la cadena
	* es ID_certficado-codigo-metodo  ejem: 2-UT/II--Ultrasonido Industrial
	* 6.- luego de llenar el formulario se guarda y se presenta mensaje de exito
	*	con un link haci la presentacion del certificado
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
		//si no se recibe nada por post
		if (!$_POST){
			//se comprueba que se reciba el parametro y mostramos el formulario
			if($this->uri->segment(3) == ! false ){
				$cliente = $this->m_basedatos->GetRow('clientes','id_cliente',$this->uri->segment(3));				
				$campos = array(
						'id_certificado,
						concat(codigo,"--", metodo)
						as certificado'
						);	
				//listado de todos los certificados													
				$certificados = $this->m_basedatos->Get($campos,'tipos_de_certificados','1=1',0,100);
				$campos = array(
						'id_certificado,
						fecha_creacion'
						);
				//listado de las certificaciones del cliente
				$certificaciones_cliente = $this->m_basedatos->Get($campos,'clientes_certificados','id_cliente = ' . $this->uri->segment(3),0,100);
				//diferencia de certificaciones, es un listado de las certificaciones que no tiene este cliente												
				$i = 0;					
				$clon_certificados = $certificados->result();
				foreach ($certificados->result() as $key => $value) {					
					foreach ( $certificaciones_cliente->result() as $indice => $valor) {
							if ($value->id_certificado == $valor->id_certificado){																
								unset($clon_certificados[$i]);
							}
					}
					$i++;
				}				
				$mi_cliente = $cliente['Apellidos'] . ' ' . $cliente['Nombres'];
				$catalogo['v_eform_certificacion'] = array('accion' => 'crear',
															'Id_cc' => '0',
															'nombres' => $mi_cliente,
															'certificados' => $clon_certificados,															
															'Id_Cliente' => $this->uri->segment(3));

			}else{
				$this->index();
			}
		// en caso de que se reciba algo por post se valida el formulario y se procede
		// con el registro en la base de datos 
		}else{
			$this->reglasFormularios();
				if($this->form_validation->run() == TRUE){
					//enviamos los datos a la tabla
					//separamos el id del registro 	de la cadena del campo input
					$id_certificado =  $this->input->post('certificado');										    
					 $indice = strpos($id_certificado,'-');
					(int)$prefijo =  substr($id_certificado,0,$indice);
					$datos = array(																
									'id_cliente' => $this->input->post('id_cliente'),								
									'id_certificado' => $prefijo,
									'id_registro' => strtoupper($this->input->post('id_registro')),								
									'edision' => strtoupper($this->input->post('edision')),								
									'fecha_certificacion' => $this->input->post('fecha_certificacion'),								
									'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),								
									'examinacion_general' => $this->input->post('examinacion_general'),								
									'examinacion_parcial' => $this->input->post('examinacion_parcial'),								
									'examinacion_especifica' => $this->input->post('examinacion_especifica'),								
									'estado_examen' => strtoupper($this->input->post('examen')),								
									'fecha_creacion' => date('Y-m-d H:i:s')
									);
					$this->m_basedatos->Set($this->Tabla_,$datos);
					//mostramos los datos del certificado
					$catalogo['v_eexito'] = array('controlador' => 'certificaciones',
													'id' => $this->m_basedatos->LastId(),
													'id_cliente' => $this->input->post('id_cliente')
													);						
				}
				else{
				$cliente = $this->m_basedatos->GetRow('clientes','id_cliente',$this->input->post('id_cliente'));				
				$campos = array(
						'id_certificado,
						concat(codigo,"--", metodo)
						as certificado'
						);	
				//listado de todos los certificados													
				$certificados = $this->m_basedatos->Get($campos,'tipos_de_certificados','1=1',0,100);
				$campos = array(
						'id_certificado,
						fecha_creacion'
						);
				//listado de las certificaciones del cliente
				$certificaciones_cliente = $this->m_basedatos->Get($campos,'clientes_certificados','id_cliente = ' . $this->input->post('id_cliente'),0,100);
				//diferencia de certificaciones, es un listado de las certificaciones que no tiene este cliente												
				$clon_certificados = $certificados->result();
				$i = 0;					
				foreach ($certificados->result() as $key => $value) {					
					foreach ( $certificaciones_cliente->result() as $indice => $valor) {
							if ($value->id_certificado == $valor->id_certificado){																
								unset($clon_certificados[$i]);
							}
					}
					$i++;
				}				
					$mi_cliente = $cliente['Apellidos'] . ' ' . $cliente['Nombres'];
					$catalogo['v_calertas'] =  array('alert' => 'Lo Siento... Uno de los Datos Ingresados Esta Errado Es Demaciado Corto o No Está Ingresado');					
					$catalogo['v_eform_certificacion'] = array('accion' => 'crear',
															'id_cc' => '0',
															'nombres' => $mi_cliente,
															'certificados' => $clon_certificados,															
															'Id_Cliente' => $this->input->post('id_cliente'));
								
					
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
	* @param $this->uri->s egment(3) id del certificado a actualizar	en caso de que no exista se 
	* redirecciona a 404
	*/
	public function actualizar(){		
		$catalogo = $this->contenidos();	
		if (!$_POST){	
			if ($this->uri->segment(3) != false){
				//obtenemos la informacion de la certificacion
				$datos = $this->m_basedatos->GetRow('clientes_certificados','id_cc',$this->uri->segment(3));
				//otenemos la informacion del cliente
				$cliente = $this->m_basedatos->GetRow('clientes','id_cliente',$datos['Id_Cliente']);				
				//obtenemos la informacion del certificado				
				$campos = array(
						'id_certificado,
						concat(codigo,"--", metodo)
						as certificado'
						);
				$certificado = $this->m_basedatos->get($campos,'tipos_de_certificados','Id_Certificado =' . $datos['Id_Certificado'],0,1);				
				//mostramos la informacion completa de esta certificacion
				$mi_cliente = $cliente['Apellidos'] . ' ' . $cliente['Nombres'];
				$datos['accion'] = 'actualizar';				
				$datos['nombres'] = $mi_cliente;
				$datos['certificados'] = $certificado->result();
				$datos['Id_Cliente'] = $datos['Id_Cliente'];
				$catalogo['v_eform_certificacion'] = $datos;				
			}else{
				$this->index();						
				}}
		else{			
			$this->reglasFormularios();			
			if($this->form_validation == TRUE){
				$datos = array(
									'id_cc' => $this->input->post('id_cc'),						
									'id_cliente' => $this->input->post('id_cliente'),																	
									'id_registro' => strtoupper($this->input->post('id_registro')),								
									'edision' => strtoupper($this->input->post('edision')),								
									'fecha_creacion' => $this->input->post('fecha_creacion'),								
									'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),								
									'examinacion_general' => $this->input->post('examinacion_general'),								
									'examinacion_parcial' => $this->input->post('examinacion_parcial'),								
									'examinacion_especifica' => $this->input->post('examinacion_especifica'),								
									'estado_examen' => strtoupper($this->input->post('examen')),								
									'fecha_creacion' => date('Y-m-d H:i:s')
									);
					$this->m_basedatos->Upd($this->Tabla_,$datos,'id_cc = ' . $this->input->post('id_cc'));
					//mostramos los datos del certificado
					$catalogo['v_eexito'] = array('controlador' => 'certificaciones',
													'id' => $this->input->post('id_cc'),
													'id_cliente' => $this->input->post('id_cliente')
													);
				//en caso de que las reglas de formularios fallen	
				}else{

				//obtenemos la informacion de la certificacion
				$datos = $this->m_basedatos->GetRow('clientes_certificados','id_cc',$this->input->post('id_cc'));
				//otenemos la informacion del cliente
				$cliente = $this->m_basedatos->GetRow('clientes','id_cliente',$datos['Id_Cliente']);				
				//obtenemos la informacion del certificado				
				$campos = array(
						'id_certificado,
						concat(codigo,"--", metodo)
						as certificado'
						);
				//mostramos la informacion completa de esta certificacion
				$mi_cliente = $cliente['Apellidos'] . ' ' . $cliente['Nombres'];
				$datos['accion'] = 'actualizar';				
				$datos['nombres'] = $mi_cliente;				
				$datos['Id_Cliente'] = $datos['Id_Cliente'];
				$catalogo['v_calertas'] =  array('alert' => 'Lo Siento... Uno de los Datos Ingresados Esta Errado o es Insuficiente.');
				$catalogo['v_eform_certificacion'] = $datos;	
				}

		}
			
		$this->mostrarhtml($catalogo);
	}

	/**
	*
	* Elimina un certificacion de un cliente
	*
	*/
	public function eliminar(){		
		$catalogo = $this->contenidos();
		$catalogo['v_calertas']	= array('titulo' => 'Eliminar Registro?',
											'alert' => 'Esta Seguro de eliminar este registro?... luego de hacerlo no se 
														podrá recuperar la información' );
		if($this->uri->segment(3) != false){
			$datos['detalles'] = $this->m_basedatos->GetRow($this->Tabla_,'id_cc',$this->uri->segment(3));
			//recupermaos informacion adicional del cliente
			$this->Tabla_ = 'v_certificaciones';
			$i = $this->m_basedatos->GetRow($this->Tabla_,'id_cc',$this->uri->segment(3));
			$datos['nombre'] = $i['nombres'] . ' ' . $i['apellidos'];
			$datos['tipo'] = 'certificacion';
			$datos['id'] =  $i['id_cc'];
			$datos['accion'] = 'eliminar';
			$datos['controlador'] = 'certificaciones';
			$catalogo['v_epresentacion'] = $datos;	

			$this->mostrarhtml($catalogo);
		}else{			
			$this->index();
			}
	}

	/**
	* confirma y Elimina una certificacion de la base de datos
	*/
	public function confirmar(){
		$catalogo = $this->contenidos();
		if($this->uri->segment(3) != false){			
				$where ='id_cc = '. $this->uri->segment(3);
				$this->m_basedatos->Del($this->Tabla_,$where,1);
				$catalogo['v_eexito'] = array('controlador' => 'certificaciones',
												'id' => '0');
				$this->mostrarhtml($catalogo);			
		}else{			
			$this->index();
			}
	}

	/**
	* Presenta la informacion completa de una certificacion 
	* junto con la informacion basica del cliente
	*
	*/
	public function presentar(){		
		$catalogo = $this->contenidos();
		$datos;
		if ($this->uri->segment(3) != false){			
			$datos['detalles'] = $this->m_basedatos->GetRow($this->Tabla_,'id_cc',$this->uri->segment(3));
			//recupermaos informacion adicional del cliente
			$i = $this->m_basedatos->GetRow($this->Tabla_,'id_cc',$this->uri->segment(3));
			$url = base_url() . 'index.php/clientes/certificaciones/' . $i['Id_Cliente'];			
			$enlace = '<a href="' . $url .' ">&nbsp;&nbsp;&nbsp;Volver</a>';
			if (!empty($i)){
				$datos['nombre'] = $i['Id_Registro'];
				$datos['tipo'] = 'certificacion';
				$datos['id'] =  $i['Id_cc'];
				$datos['accion'] = 'presentar';
				$datos['controlador'] = 'certificaciones';
				$datos['enlace'] = $enlace;
				$catalogo['v_epresentacion'] = $datos;			
				$this->mostrarhtml($catalogo);
			}else{
				$catalogo['v_calertas'] = array('titulo' => 'No existe!' ,
												'alert' => 'Esta certificacion ya no Existe' );
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
			
			$this->Pagina_ = $this->Pagina_ . $this->load->view('v_fpie','',true);
			print $this->Pagina_;
	}
}