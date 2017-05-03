<?php
class CuentasCModel
{
	private $pdo;

	public function __CONSTRUCT()
	{
		try
		{
			$this->pdo = new PDO('mysql:host=localhost;dbname=factmenudo', 'root', '123456');
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

			$stm = $this->pdo->prepare("SELECT * FROM cuenta_cobrar");
			$stm->execute();

//FETCH_OBJ devuelve un objeto anónimo con nombres de propiedadesCuentaC que se corresponden a los nombres de las columnas devueltas en el conjunto de resultados.
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)  
			{
				$eq = new CuentaC();
				$eq->__SET('cuenta_id',$r->cuenta_id);
				$eq->__SET('id_Cliente', $r->id_Cliente);
				$eq->__SET('id_Comprobante', $r->id_Comprobante);
				$eq->__SET('fecha_Inicio', $r->fecha_Inicio);
				$eq->__SET('monto_Inicial', $r->total);

				

				$result[] = $eq;
			}

			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Obtener($id_cuenta)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM cuenta_cobrar WHERE cuenta_id = ?");
			          

			$stm->execute(array($id_cuenta));
			
			$r = $stm->fetch(PDO::FETCH_OBJ);

			$eq = new CuentaC();
			$eq->__SET('cuenta_id',$r->cuenta_id);
			$eq->__SET('id_Cliente', $r->id_Cliente);
			$eq->__SET('id_Comprobante', $r->id_Comprobante);
			$eq->__SET('fecha_Inicio', $r->fecha_Inicio);
			$eq->__SET('monto_Inicial', $r->total);
			

			return $eq;
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Eliminar($id_cuenta)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("DELETE FROM cuenta_cobrar WHERE cuenta_id= ?");			          

			$stm->execute(array($id_cuenta));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar(CuentaC $data)
	{
		try 
		{
			$sql = "UPDATE cuenta_cobrar SET
			 			id_Cliente = ?,
			 			id_Comprobante = ?,
						fecha_Inicio  = ?,
						total = ?,
					WHERE cuenta_id = ?";

			$this->pdo->prepare($sql)->execute(
				array(
					
					$data->__GET('id_Cliente'),
					$data->__GET('id_Comprobante'),
					$data->__GET('fecha_Inicio'),
					$data->__GET('monto_Inicial'), 
					$data->__GET('cuenta_id')

					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar(CuentaC $data)
	{
		try 
		{
		$sql = "INSERT INTO cuenta_cobrar (id_Cliente,id_Comprobante,fecha_Inicio,total) 
		        VALUES (?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
			array(
				$data->__GET('id_Cliente'), 
				$data->__GET('id_Comprobante'),
				$data->__GET('fecha_Inicio'),
				$data->__GET('monto_Inicial')
				)
				
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}