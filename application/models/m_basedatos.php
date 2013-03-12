<?php
/**
 * Modelo encargado de manejar la base de datos cendendt_certificados
 * los métodos principales de esta clase será hacer CRUD a las tablas del modelo
 * Metodos de la clase (param sin especificar)
 * Get()
 * Getcolumns()
 * Getrows()
 * Set()
 * Upd()
 * Del()
  * 
 * @package App Cende Certificados
 * @subpackage Modelo Base Datos 
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio> 
 * @copyright 2012 CendeNdt <info@cendendt.com>
 * @license (c)CendeNdt Todos los derechos reservados 
 * @link http://www.cendendt.com
 * @version 2.0
 * @access public
 *
 */
class M_Basedatos extends CI_Model{
	private $Query_;

	/**
	* Constructor de la clase, activamos el constructor de la clase padre para usar sus
	* métodos 
	*/
	public function __construct(){
		parent::__construct();				
	}

	/**
	* Obtiene los registros de una tabla la consulta mas compleja a armar sería esta 
	* * SELECT cedula, nombres, apellidos FROM clientes WHERE id_cliente > 10 LIMIT 3;
	* @param string $tabla nombre de la tabla
	* @param string $condiciones condiciones WHERE del Sql
	* @param array $columnas clumnas a seleccionar	
	* @param int $limit Límie de resultados de la consulta
	*
	* @return array $query resultados de la consulta
	*/
	public function Get($columnas,$tabla,$condiciones,$limit,$offset = 0){		
		#armamos la consulta
		$sql =  'SELECT';
		$i = 0;

		foreach ($columnas as $nombre) {
			if ($i == 0){
				$sql = $sql . ' ' . $nombre;	//ponemos espacio
			}
			else {
				$sql = $sql . ',' . $nombre;  	//ponemos coma
			}
			$i++;
		}

		$sql = $sql . ' FROM ' . $tabla;
		$sql = $sql . ' WHERE ' . $condiciones;		
		
		//validamos que exista un limite de consultas válido para paginacion
		if ($limit == 0 ){
			$sql = $sql . ' LIMIT ' . $limit . ',' . $offset;
			$this->Query_ = $this->db->query($sql);			
		}
		else{			
			$sql = $sql . ' LIMIT ' . $limit . ',' . $offset;							
			$this->Query_ = $this->db->query($sql);			
		}				
		return $this->Query_;
	}

	/**
	* Recupera los nombres de columna de una tabla
	* @param $tabla nombre de la tabla
	*
	* @return int numero de columnas de una tabla
	*/
	public function Getcolumns($tabla){
		$columns = $this->db->list_fields($tabla);
		return $columns;

	}

	/**
	* Recupera el numero de filas de una tabla
	* @param $tabla nombre de la tabla
	*
	* @return int numero de filas de una tabla
	*/
	public function Getrows($tabla){
		$var = $this->db->count_all_results($tabla);
		return $var;

	}

	/**
	* Establece registros en una tabla
	* @param $tabla la tabla a la que se le va a realizar la insercion
	* @param array $datos array asociativo con nombre/valor del nuevo registro
	*
	* @return boolean 
	*/
	public function Set($tabla,$datos){		
		$sql = $this->db->insert_string($tabla,$datos);		
		$this->db->query($sql);
		return true;
	}

	/**
	* Actualiza un registros de una tabla
	* @param string $tabla nombre de la tabla a actualiazar
	* @param array $datod Array asociativo nombre/valor 
	* @param string $where condiciones para las actualizacuiones
	* 
	* @return boolean
	*/
	public function Upd($tabla,$datos,$where){
		$sql = $this->db->update_string($tabla,$datos,$where);				
		$this->db->query($sql);
		return true;
		}

	/**
	* Borra registros de una tabla
	* @param string $tabla nombre de la tabla
	* @param string $condiciones condiciones WHERE del Sql	
	* @param int $limit Límie de los registros a afectar
	*/
	public function Del($tabla,$condiciones,$limit){
		$sql = "DELETE FROM " . $tabla;
		$sql = $sql . ' WHERE ' . $condiciones;
		
		//validamos que exista un limite de consultas válido para paginacion
		if ($limit == 0 ){
			$this->Query_ = $this->db->query($sql);
		}
		else{
			$sql = $sql . ' LIMIT ' . $limit;	
			$this->Query_ = $this->db->query($sql);			
		}		
		return true;
	}

	/**
	* ultimo id insertado e la tabla
	*/
	public function LastId(){		
		return $this->db->insert_id();		
	}

	/**
	* Recupera informacion de una tabla respecto a un id
	* informacion util para llenar un formulario de actualizacion
	*/
	public function GetRow($tabla,$campo,$id){
		$sql = 'SELECT * FROM ' . $tabla . ' WHERE ' . $campo . ' = ' . $id ;
		$res = $this->db->query($sql);
		return $res->row_array();		
	}

	/**
	* Esta funcion retorna el string de la última consulta realizada
	*/
	public function lastQuery(){
		return $this->db->last_query();
	}

}
