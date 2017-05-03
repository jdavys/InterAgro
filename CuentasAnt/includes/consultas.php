<?php
function generaConsultaRepProd($info){
	if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT f.id,pe.name ,f.fecha,pr.descripcion,dt.cantidad,p.price_out 
							from comprobante f,person pe,comprobante_detalle dt,product p,presentacion pr 
							where Producto_id in(SELECT id from product where name='{$info['detalleP']}') 
							and Producto_id=p.id
							and dt.comprobante_id=f.id
							and p.presentation_id=pr.id
							and f.cliente_id=pe.id";

							}else{
								$fD=date('d-m-Y',strtotime($info['fechaD']));
								$fH=date('d-m-Y',strtotime($info['fechaH']));
								$consul="SELECT f.id,pe.name ,f.fecha,pr.descripcion,dt.cantidad,p.price_out 
							from comprobante f,person pe,comprobante_detalle dt,product p,presentacion pr 
							where Producto_id in(SELECT id from product where name='{$info['detalleP']}') 
							and Producto_id=p.id  and f.cliente_id=pe.id 
							and STR_TO_DATE(fecha,'%d-%m-%Y') between 
							STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y')
							and comprobante_id=f.id and p.presentation_id=pr.id
							order by STR_TO_DATE(fecha,'%d-%m-%Y') ";
							}
	return $consul;
}
function generaConsultaRep($info){
	if($info['tipo']=="t"){   
		if($info['gestor']=="t"){
			if($info['cliente']=="t"){
				if($info['estadoF']=="t"){
					if($info['moneda']=="t" || $info['moneda']=="n"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar";
						}else{				//FECHA
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y')
									and	STR_TO_DATE('{$fH}','%d-%m-%Y') order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}else{					//MONEDA todo
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar where moneda='{$info['moneda']}'";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from comprobante where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}' order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}
				}else{							//ESTADO

					if($info['moneda']=="t" || $info['moneda']=="n"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where estado={$info['estadoF']}";
						}else{             //ESTADO - FECHA 
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') 
											between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado={$info['estadoF']}order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}//fin de fecha
					}else{					//ESTADO - MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where moneda='{$info['moneda']}' and estado={$info['estadoF']}";
						}else{				//ESTADO - MONEDA - FECHA
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado={$info['estadoF']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}//FIN DE FECHA
					}//fin moneda
				}//fin de estado
			}else{								//CLIENTE
				if($info['estadoF']=="t"){
					if($info['moneda']=="t" || $info['moneda']=="n"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where id_Cliente={$info['cliente']} ";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y')
									and	STR_TO_DATE('{$fH}','%d-%m-%Y') and id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar where moneda='{$info['moneda']}' and id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from comprobante where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}' and id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}
				}else{//ESTADO

					if($info['moneda']=="t" || $info['moneda']=="n"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where estado={$info['estadoF']} and id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where
											STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado={$info['estadoF']} and 
											id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where moneda='{$info['moneda']}' and estado={$info['estadoF']}
									 and id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado={$info['estadoF']} and 
											id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}//fin moneda
				}//fin de estado
			}//fin de cliente
		}else{					// AGENTE 
			if($info['cliente']=="t"){
				if($info['estadoF']=="t"){
					if($info['moneda']=="t" || $info['moneda']=="n"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar, person  
									where id=id_Cliente";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar, person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') 
									between STR_TO_DATE('{$fD}','%d-%m-%Y')	and	STR_TO_DATE('{$fH}','%d-%m-%Y')  and id=id_Cliente
									order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}else {//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									from cuenta_cobrar, person where moneda='{$info['moneda']}' 
									and id=id_Cliente";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}'  and id=id_Cliente
										order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}
				}else{//ESTADO

					if($info['moneda']=="t" || $info['moneda']=="n"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where estado={$info['estadoF']} 
									 and id=id_Cliente";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar, person where
											STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado={$info['estadoF']} 
											 and id=id_Cliente order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar,person where moneda='{$info['moneda']}' and estado={$info['estadoF']}
									  and id=id_Cliente order by STR_TO_DATE(fecha_Inicio,'%d/%m/%Y') ";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado={$info['estadoF']}
											 and id=id_Cliente order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}//fin moneda
				}//fin de estado
			}else{
				if($info['estadoF']=="t"){
					if($info['moneda']=="t" || $info['moneda']=="n"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where id_Cliente={$info['cliente']}
									  and id=id_Cliente";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar, person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y')
									and	STR_TO_DATE('{$fH}','%d-%m-%Y') and id_Cliente={$info['cliente']}
									 and id=id_Cliente order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar,person where moneda='{$info['moneda']}' and id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}' and id_Cliente={$info['cliente']} and id=id_Cliente
										order by STR_TO_DATE(fecha_Inicio,'%d/%m/%Y') ";
						}
					}
				}else{//ESTADO

					if($info['moneda']=="t" || $info['moneda']=="n"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where estado={$info['estadoF']} and id_Cliente={$info['cliente']}
									  and id=id_Cliente ";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar, person where
											STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado={$info['estadoF']} and 
											id_Cliente={$info['cliente']}  and id=id_Cliente
											order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where moneda='{$info['moneda']}' and estado={$info['estadoF']}
									 and id_Cliente={$info['cliente']}  and id=id_Cliente";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado={$info['estadoF']} and 
											id_Cliente={$info['cliente']}  and id=id_Cliente
											order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}//fin moneda
				}//fin de estado
			}//fin de cliente
		}//fin de gestor
	}else if($info['tipo']=="a"){   //**************************************************************** TIPO   AGENTE **************************************
		if($info['gestor']=="t"){
			if($info['cliente']=="t"){
				if($info['estadoF']=="t"){
					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar";
						}else{				//FECHA
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y')
									and	STR_TO_DATE('{$fH}','%d-%m-%Y') order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}else{					//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar where moneda='{$info['moneda']}'";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from comprobante where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}' order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}
				}else{							//ESTADO PENDIENTE-CANCELADO

					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where estado='{$info['estadoF']}'";
						}else{             //ESTADO - FECHA 
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') 
											between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado='{$info['estadoF']}' order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}//fin de fecha
					}else{					//ESTADO - MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where moneda='{$info['moneda']}' and estado='{$info['estadoF']}'";
						}else{				//ESTADO - MONEDA - FECHA
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado='{$info['estadoF']}' order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}//FIN DE FECHA
					}//fin moneda
				}//fin de estado
			}else{								//CLIENTE
				if($info['estadoF']=="t"){
					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y')
									and	STR_TO_DATE('{$fH}','%d-%m-%Y') and id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar where moneda='{$info['moneda']}' and id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from comprobante where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}' and id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}
				}else{//ESTADO

					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where estado='{$info['estadoF']}' and id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where
											STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado='{$info['estadoF']}' and 
											id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where moneda='{$info['moneda']}' and estado={$info['estadoF']}
									 and id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado='{$info['estadoF']}' and 
											id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}//fin moneda
				}//fin de estado
			}//fin de cliente
		}else{					// AGENTE -AGENTE
			if($info['cliente']=="t"){
				if($info['estadoF']=="t"){
					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar, person  
									where Agente='{$info['gestor']}' and id=id_Cliente";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar, person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') 
									between STR_TO_DATE('{$fD}','%d-%m-%Y')	and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
									Agente='{$info['gestor']}' and id=id_Cliente order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT id_Comprobante,fecha_Inicio,fecha_Vence,moneda,monto_Inicial,saldo_Factura
									from cuenta_cobrar, person where moneda='{$info['moneda']}' and Agente='{$info['gestor']}' 
									and id=id_Cliente ";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}' and Agente='{$info['gestor']}' and id=id_Cliente 
										order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}
				}else{//ESTADO

					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where estado='{$info['estadoF']}' and Agente={$info['gestor']} 
									 and id=id_Cliente group by name order by name asc";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar, person where
											STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado='{$info['estadoF']}'
											and Agente='{$info['gestor']}' and id=id_Cliente group by id_cliente 
											order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar,person where moneda='{$info['moneda']}' and estado='{$info['estadoF']}'
									 and Agente='{$info['gestor']}' and id=id_Cliente group by id_cliente order by id_cliente asc";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado='{$info['estadoF']}'
											and Agente='{$info['gestor']}' and id=id_Cliente group by id_cliente 
											order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}//fin moneda
				}//fin de estado
			}else{
				if($info['estadoF']=="t"){
					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where id_Cliente={$info['cliente']}
									 and Agente='{$info['gestor']}' and id=id_Cliente group by id_cliente ";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar, person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y')
									and	STR_TO_DATE('{$fH}','%d-%m-%Y') and id_Cliente={$info['cliente']}
									and Agente='{$info['gestor']}' and id=id_Cliente group by id_cliente 
									order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar,person where moneda='{$info['moneda']}' and id_Cliente={$info['cliente']} and Agente='{$info['gestor']}' and id=id_Cliente group by id_cliente ";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}' and id_Cliente={$info['cliente']}
										and Agente='{$info['gestor']}' and id=id_Cliente group by id_cliente
										order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}
				}else{//ESTADO

					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where estado='{$info['estadoF']}' and id_Cliente={$info['cliente']}
									 and Agente='{$info['gestor']}' and id=id_Cliente group by id_cliente";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar, person where
											STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado='{$info['estadoF']}' and 
											id_Cliente={$info['cliente']} and Agente='{$info['gestor']}' and id=id_Cliente
											group by id_cliente	order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where moneda='{$info['moneda']}' and estado='{$info['estadoF']}'
									 and id_Cliente={$info['cliente']} and Agente='{$info['gestor']}' and id=id_Cliente group by id_cliente";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado='{$info['estadoF']}' and 
											id_Cliente={$info['cliente']} and Agente='{$info['gestor']}' and id=id_Cliente 
											group by id_cliente order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}//fin moneda
				}//fin de estado
			}//fin de cliente
		}//fin de gestor
	}else if($info['tipo']=="cl"){   //**************************************************************** CLIENTE
		if($info['gestor']=="t"){
			if($info['cliente']=="t"){
				if($info['estadoF']=="t"){
					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar";
						}else{				//FECHA
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y')
									and	STR_TO_DATE('{$fH}','%d-%m-%Y') order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}else{					//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar where moneda='{$info['moneda']}'";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from comprobante where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}' order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}
				}else{							//ESTADO

					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where estado='{$info['estadoF']}'";
						}else{             //ESTADO - FECHA 
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') 
											between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado='{$info['estadoF']}' order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}//fin de fecha
					}else{					//ESTADO - MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where moneda='{$info['moneda']}' and estado='{$info['estadoF']}'";
						}else{				//ESTADO - MONEDA - FECHA
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado='{$info['estadoF']}' order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}//FIN DE FECHA
					}//fin moneda
				}//fin de estado
			}else{								//CLIENTE
				if($info['estadoF']=="t"){
					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y')
									and	STR_TO_DATE('{$fH}','%d-%m-%Y') and id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar where moneda='{$info['moneda']}' and id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from comprobante where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}' and id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}
				}else{//ESTADO

					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where estado='{$info['estadoF']}' and id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where
											STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado='{$info['estadoF']}' and 
											id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar where moneda='{$info['moneda']}' and estado='{$info['estadoF']}'
									 and id_Cliente={$info['cliente']}";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado='{$info['estadoF']}' and 
											id_Cliente={$info['cliente']} order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y')";
						}
					}//fin moneda
				}//fin de estado
			}//fin de cliente
		}else{					// AGENTE NULO
			if($info['cliente']=="t"){
				if($info['estadoF']=="t"){
					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar, person  
									where id=id_Cliente GROUP BY name ORDER BY name";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar, person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') 
									between STR_TO_DATE('{$fD}','%d-%m-%Y')	and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
									Agente='{$info['gestor']}' and id=id_Cliente GROUP BY name ORDER BY name";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									from cuenta_cobrar, person where moneda='{$info['moneda']}'  
									and id=id_Cliente GROUP BY name ORDER BY name ";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT id_Comprobante,fecha_Inicio,fecha_Vence,moneda,monto_Inicial,saldo_Factura
										 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}'  and id=id_Cliente 
										order by STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') ";
						}
					}
				}else{//ESTADO

					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where estado='{$info['estadoF']}'
									 and id=id_Cliente GROUP BY name ORDER BY name ";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar, person where
											STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado='{$info['estadoF']}' GROUP BY name ORDER BY name";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar,person where moneda='{$info['moneda']}' and estado='{$info['estadoF']}'
									 and id=id_Cliente GROUP BY name ORDER BY name";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado='{$info['estadoF']}'
											 and id=id_Cliente
											GROUP BY name ORDER BY name ";
						}
					}//fin moneda
				}//fin de estado
			}else{
				if($info['estadoF']=="t"){
					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where id_Cliente={$info['cliente']}
									  and id=id_Cliente GROUP BY name ORDER BY name";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT * from 
									cuenta_cobrar, person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y')
									and	STR_TO_DATE('{$fH}','%d-%m-%Y') and id_Cliente={$info['cliente']}
									 and id=id_Cliente GROUP BY name ORDER BY name ";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT * from cuenta_cobrar,person where id_Cliente={$info['cliente']} GROUP BY name ORDER BY name";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
										 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
										STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
										moneda='{$info['moneda']}' and id_Cliente={$info['cliente']}
										 and id=id_Cliente
										GROUP BY name ORDER BY name ";
						}
					}
				}else{//ESTADO

					if($info['moneda']=="t"){
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where estado='{$info['estadoF']}' and id_Cliente={$info['cliente']}
									  and id=id_Cliente GROUP BY name ORDER BY name";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar, person where
											STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and
											STR_TO_DATE('{$fH}','%d-%m-%Y') and estado='{$info['estadoF']}' and 
											id_Cliente={$info['cliente']}  and id=id_Cliente
											GROUP BY name ORDER BY name ";
						}
					}else{//MONEDA
						if($info['fechaD']=="" && $info['fechaH']==""){
							$consul="SELECT *
									 from cuenta_cobrar, person where estado='{$info['estadoF']}'
									 and id_Cliente={$info['cliente']}  and id=id_Cliente GROUP BY name ORDER BY name";
						}else{
							$fD=date('d-m-Y',strtotime($info['fechaD']));
							$fH=date('d-m-Y',strtotime($info['fechaH']));
							$consul="SELECT *
											 from cuenta_cobrar,person where STR_TO_DATE(fecha_Inicio,'%d-%m-%Y') between 
											STR_TO_DATE('{$fD}','%d-%m-%Y') and	STR_TO_DATE('{$fH}','%d-%m-%Y') and 
											moneda='{$info['moneda']}' and estado='{$info['estadoF']}' and 
											id_Cliente={$info['cliente']}  and id=id_Cliente 
											GROUP BY name ORDER BY name ";
						}
					}//fin moneda
				}//fin de estado
			}//fin de cliente
		}//fin de gestor
	}




	return $consul;
}

?>