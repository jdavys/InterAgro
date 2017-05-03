<?php
class ClienteModel
{
	private $pdo;

	public function __CONSTRUCT()
	{
		try
		{
            $this->pdo = Database::Conectar();
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Buscar($criterio)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT id,name as Nombre,address1 as Direccion,phone1 as RUC,tipo_cliente as Tipo  FROM person WHERE name LIKE '%$criterio%' and kind=1 ORDER BY name LIMIT 8");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
}