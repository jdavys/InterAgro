<?php
class DetalleModel
{
	private $pdo;

	public function __CONSTRUCT()
	{
		try
		{
			$this->pdo = new PDO('mysql:host=localhost;dbname=detalle_factmenu', 'root', '123456');
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

			$stm = $this->pdo->prepare("SELECT * FROM comprobante_detalle");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$eq = new Detalle();

				$eq->__SET('detalle_id', $r->detalle_id);
				$eq->__SET('comprobante_id', $r->comprobante_id);
				$eq->__SET('producto_id', $r->producto_id);
				$eq->__SET('presenta', $r->presenta);
				$eq->__SET('cantidad', $r->cantidad);
				$eq->__SET('precioUnitario', $r->precioUnitario);
				$eq->__SET('descuento', $r->descuento);
				$eq->__SET('total', $r->Total);
				

				$result[] = $eq;
			}

			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Obtener($deta)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("SELECT * FROM comprobante_detalle WHERE detalle_id = ?");
			          

			$stm->execute(array($deta));
			$r = $stm->fetch(PDO::FETCH_OBJ);

			$eq = new Detalle();

			$eq->__SET('detalle_id', $r->detalle_id);
			$eq->__SET('comprobante_id', $r->comprobante_id);
			$eq->__SET('producto_id', $r->producto_id);
			$eq->__SET('presenta', $r->presenta);
			$eq->__SET('cantidad', $r->cantidad);
			$eq->__SET('precioUnitario', $r->precioUnitario);
			$eq->__SET('descuento', $r->descuento);
			$eq->__SET('total', $r->Total);
			

			return $eq;
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Eliminar($deta)
	{
		try 
		{
			$stm = $this->pdo
			          ->prepare("DELETE FROM comprobante_detalle WHERE detalle_id = ?");			          

			$stm->execute(array($deta));
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Actualizar(Detalle $data)
	{
		try 
		{
			$sql = "UPDATE comprobante_detalle SET 
						comprobante_id        = ?,
						producto_id = ?,
						presenta = ?,
						cantidad = ?,
						precioUnitario = ?,
						descuento = ?,
						total = ?
					WHERE detalle_id = ?";

			$this->pdo->prepare($sql)->execute(
				array(					 
					$data->__GET('comprobante_id'), 
					$data->__GET('producto_id'),
					$data->__GET('presenta'),
					$data->__GET('cantidad'),
					$data->__GET('precioUnitario'),
					$data->__GET('descuento'),
					$data->__GET('total'),
					$data->__GET('detalle_id')
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar(Detalle $data)
	{
		try 
		{
		$sql = "INSERT INTO comprobante_detalle (comprobante_id,producto_id,presenta,cantidad,precioUnitario,descuento,total) 
		        VALUES (?, ?, ?, ?, ?, ?, ?)";

		$this->pdo->prepare($sql)
		     ->execute(
			array(
				$data->__GET('comprobante_id'), 
					$data->__GET('producto_id'),
					$data->__GET('presenta'),
					$data->__GET('cantidad'),
					$data->__GET('precioUnitario'),
					$data->__GET('descuento'),
					$data->__GET('total')
				)
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
}