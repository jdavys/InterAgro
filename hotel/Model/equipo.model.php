<?php
class EquipoModel
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

			$stm = $this->pdo->prepare("SELECT * FROM equipo where estado=1");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$eq = new Equipo();

				$eq->__SET('id_equipo', $r->id_equipo);
				$eq->__SET('descripcion', $r->descripcion);
				$eq->__SET('cantidad', $r->cantidad);
				

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
			          ->prepare("SELECT * FROM equipo WHERE id_equipo = ?");
			          

			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);

			$eq = new Equipo();

			$eq->__SET('id_equipo', $r->id_equipo);
			$eq->__SET('descripcion', $r->descripcion);
			$eq->__SET('cantidad', $r->cantidad);

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
			          ->prepare("UPDATE equipo SET 
						         estado = 0
					             WHERE id_equipo = ?");			          

			$stm->execute(
				array($id->__GET('id_equipo')));

		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar(Equipo $data)
	{
		try 
		{
			$sql = "UPDATE equipo SET 
						descripcion     = ?, 
						cantidad        = ?
					WHERE id_equipo = ?";

			$this->pdo->prepare($sql)->execute(
				array(
					$data->__GET('descripcion'), 
					$data->__GET('cantidad'),
					$data->__GET('id_equipo')
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar(Equipo $data)
	{
		try 
		{
		$sql = "INSERT INTO equipo (descripcion,cantidad,estado) 
		        VALUES (?, ?,1)";

		$this->pdo->prepare($sql)
		     ->execute(
			array(
				$data->__GET('descripcion'), 
				$data->__GET('cantidad')
				)
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}