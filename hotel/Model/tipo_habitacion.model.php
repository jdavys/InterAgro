<?php
class Tipo_habitacionModel
{
	private $pdo;

	public function __CONSTRUCT()
	{
		try
		{
			$this->pdo = new PDO('mysql:host=localhost;dbname=hoteleria', 'root', '');
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		        
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}




	public function Listar()
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("select id_tipo_hab, descripcion from tipo_habitacion where estado=1");

			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$eq = new tipo_habitacion();

				$eq->__SET('id_tipo_hab', $r->id_tipo_hab);
				$eq->__SET('descripcion', $r->descripcion);
				$result[] = $eq;
			}

			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
}