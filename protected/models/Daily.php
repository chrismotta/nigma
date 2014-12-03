<?php 

class DailyReport
{

	/**
	 * FIXME: Refactoring
	 *
	 * 		- Se puede unificar la logica de getDataDash y getTops?
	 */
	

	/**
	 * Get totals of dailys group by date, include DailyReport's and DailyPublisher's info, 
	 * for the date range specified.
	 * This method must return an associative array with keys: 
	 * 		'date', 'conversions', 'clicks', 'impressions', 'revenue', 'spend'.
	 * 
	 * @param  $dateStart
	 * @param  $dateEnd
	 * @return associative array('date', 'conversions', 'clicks', 'impressions', 'revenue', 'spend')
	 */
	public function getDailyTotals($dateStart=NULL, $dateEnd=NULL)
	{
		// TODO
		// Obtener totales de DailyReport y DailyPublishers
		// Devolver array asociativo como indica @return
		// 
		// Este metodo se llamara desde DailyTotals::consolidate en lugar de DailyReport::findAll
	}


	// /**
	//  * [getDataDash description]
	//  * This method must return an associative array with keys: 
	//  * 		'array', 'dataProvider'.
	//  * 
	//  * @param  $dateStart
	//  * @param  $dateEnd
	//  * @param  $order
	//  * @return associative array('array', 'dataProvider')
	//  */
	// public function getDataDash($dateStart=NULL, $dateEnd=NULL, $order)
	// {
	// 	// TODO
	// 	// Ya que Publishers no tiene clicks ni conv. No se deberia agregar funcionalidad nueva, 
	// 	// llamando a DailyReport::getDataDash alcanzaria
	// 	// Para mayor claridad se podria copiar el codigo de su actual implementacion aqui
	// 	// 
	// 	// Este metodo se llamara desde SiteController::actionIndex
	// }


	/**
	 * [getTops description]
	 * This method must return an associative array with keys: 
	 * 		'array', 'dataProvider'.
	 * 
	 * @param  $dateStart
	 * @param  $dateEnd
	 * @param  $order
	 * @return associative array('array', 'dataProvider')
	 */
	public function getTops($dateStart=NULL, $dateEnd=NULL, $order)
	{
		// TODO
		// Obtener tops de DailyReport y DailyPublishers. Llamar a metodos getTops correspondientes
		// Sugerencia: se puede armar el set de datos en un array para luego crear el CActiveDataProvider
		// 
		// Este metodo se llamara desde SiteController::actionIndex
	}
}