<?php
class HabitacionModel
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

			$stm = $this->pdo->prepare("SELECT id_habitacion, descripcion, id_tipo_hab, precio FROM habitacion 
            where estado=1");

			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$eq = new Habitacion();

				$eq->__SET('id_habitacion', $r->id_habitacion);
				$eq->__SET('descripcion', $r->descripcion);
				$eq->__SET('id_tipo_hab', $r->id_tipo_hab);
				$eq->__SET('precio', $r->precio);

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
			          ->prepare("SELECT id_habitacion, descripcion, id_tipo_hab, precio FROM habitacion WHERE id_habitacion = ?");
			          

			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);

			$eq = new Habitacion();

			$eq->__SET('id_habitacion', $r->id_habitacion);
			$eq->__SET('descripcion', $r->descripcion);
			$eq->__SET('id_tipo_hab', $r->id_tipo_hab);
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
			          ->prepare("UPDATE habitacion SET 
						         estado = 0
					             WHERE id_habitacion = ?");			          

			$stm->execute(
				array($id->__GET('id_habitacion')));

		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar(Habitacion $data)
	{
		try 
		{
			$sql = "UPDATE habitacion SET 
						descripcion     = ?, 
						precio        = ?,
						id_tipo_hab   = ?,
					WHERE id_habitacion = ?";

			$this->pdo->prepare($sql)->execute(
				array(
					$data->__GET('descripcion'), 
					$data->__GET('precio'),
					$data->__GET('id_tipo_hab')
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar(Habitacion $data)
	{
		try 
		{
		$sql = "INSERT INTO habitacion (descripcion,precio,id_tipo_hab,estado) 
		        VALUES (?, ?,?,1)";

		$this->pdo->prepare($sql)
		     ->execute(
			array(
				$data->__GET('descripcion'), 
				$data->__GET('precio'),
				$data->__GET('id_tipo_hab')
				)
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}