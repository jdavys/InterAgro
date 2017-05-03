<?php
class ExtraModel
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

			$stm = $this->pdo->prepare("SELECT * FROM extra where estado=1");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$ex = new Extra();

				$ex->__SET('id_extra', $r->id_extra);
				$ex->__SET('descripcion', $r->descripcion);
				$ex->__SET('precio', $r->precio);
				

				$result[] = $ex;
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
			          ->prepare("SELECT * FROM extra WHERE id_extra = ?");
			          

			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);

			$eq = new Extra();

			$eq->__SET('id_extra', $r->id_extra);
			$eq->__SET('descripcion', $r->descripcion);
			$eq->__SET('precio', $r->precio);

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
			          ->prepare("UPDATE extra SET 
						         estado = 0
					             WHERE id_extra = ?");			          

			$stm->execute(
				array($id->__GET('id_extra')));

		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar(Extra $data)
	{
		try 
		{
			$sql = "UPDATE extra SET 
						descripcion     = ?, 
						precio          = ?
					WHERE id_extra = ?";

			$this->pdo->prepare($sql)->execute(
				array(
					$data->__GET('descripcion'), 
					$data->__GET('precio'),
					$data->__GET('id_extra')
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar(Extra $data)
	{
		try 
		{
		$sql = "INSERT INTO extra (descripcion,precio,estado) 
		        VALUES (?, ?, 1)";

		$this->pdo->prepare($sql)
		     ->execute(
			array(
				$data->__GET('descripcion'), 
				$data->__GET('precio')
				)
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}