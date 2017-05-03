<?php
class ClienteModel
{
	private $pdo;

	public function __CONSTRUCT()
	{
		try
		{
			$this->pdo = new PDO('mysql:host=localhost;dbname=facturador', 'root', '123456');
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

			$stm = $this->pdo->prepare("SELECT * FROM cliente order by nombre asc");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$eq = new Cliente();

				$eq->__SET('id', $r->id);
				$eq->__SET('Nombre', $r->Nombre);
				$eq->__SET('RUC', $r->RUC);
				$eq->__SET('Direccion', $r->Direccion);
				$eq->__SET('Agente', $r->Agente);
				

				$result[] = $eq;
			}

			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function ListarC($valor)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM cliente where nombre LIKE '$valor%'");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$eq = new Cliente();

				$eq->__SET('id', $r->id);
				$eq->__SET('Nombre', $r->Nombre);
				$eq->__SET('RUC', $r->RUC);
				$eq->__SET('Direccion', $r->Direccion);
				$eq->__SET('Agente', $r->Agente);
				

				$result[] = $eq;
			}

			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function Obtener($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM cliente WHERE id = ?");
			          

			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);

			$eq = new Cliente();

			$eq->__SET('id', $r->id);
			$eq->__SET('Nombre', $r->Nombre);
			$eq->__SET('RUC', $r->RUC);
			$eq->__SET('Direccion', $r->Direccion);
			$eq->__SET('Agente', $r->Agente);

			return $eq;
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Eliminar($id)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("DELETE FROM cliente WHERE id = ?");			          

			$stm->execute(array($id));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar(Cliente $data)
	{
		try 
		{
			$sql = "UPDATE cliente SET 
						Nombre     = ?, 
						RUC        = ?,
						Direccion  = ?,
						Agente     = ?
					WHERE id = ?";

			$this->pdo->prepare($sql)->execute(
				array(
					$data->__GET('Nombre'), 
					$data->__GET('RUC'),
					$data->__GET('Direccion'),
					$data->__GET('Agente'),
					$data->__GET('id')
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar(Cliente $data)
	{
		try 
		{
		$sql = "INSERT INTO cliente (Nombre,RUC,Direccion,Agente) 
		        VALUES (?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
			array(
				$data->__GET('Nombre'), 
				$data->__GET('RUC'),
				$data->__GET('Direccion'),
				$data->__GET('Agente')
				)
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}