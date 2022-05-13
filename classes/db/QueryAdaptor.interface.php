<?php
namespace Moolty\DB;
/**
 * Interfaz que deben implementar los adaptadores de
 * sentencias SQL para cada base de datos
 * 
 * @author matias
 * @version 0.1
 */
interface QueryAdaptorInterface{
	
	/**
	 * Devuelve el string para el query SELECT
	 * @param Query $query
	 */
	static function getSelectQueryString(Query $query);
	
	/**
	 * Devuelve el string para el query INSERT
	 * @param Query $query
	 */
	static function getInsertQueryString(Query $query);
	
	/**
	 * Devuelve el string para el query UPDATE
	 * @param Query $query
	 */
	static function getUpdateQueryString(Query $query);
	
	/**
	 * Devuelve el STRING para el query DELETE
	 * @param Query $query
	 */
	static function getDeleteQueryString(Query $query);
}