<?php

namespace common\components\GiiExtend\model;

use Yii;
use yii\gii\CodeFile;

/**
 * Class Generator
 *
 * @package app\common\components\GiiExtend\generators\model
 */
class Generator extends \yii\gii\generators\model\Generator
{
	public function getName ()
	{
		return "Model generator with Basic model";
	}
	
	public function getDescription ()
	{
		return "This generator will generate 2 ActiveRecord class for the specified database table. The first one will contain all the basic information (tablename, rules, attribute labels, relations, ...) and the second will only contain your code.";
	}
	
	public function requiredTemplates ()
	{
		return [ "model.php", "model-base.php" ];
	}
	
	public function stickyAttributes ()
	{
		return array_merge(parent::stickyAttributes(), [ "generateQuery" ]);
	}
	
	public function generate ()
	{
		$db    = $this->getDbConnection();
		$files = [];
		
		$modelRelations = $this->generateRelations();
		
		foreach ( $this->getTableNames() as $tablename ) {
			//  build class name
			$modelClassName = $this->generateClassName($tablename);
			$queryClassName = ($this->generateQuery) ? $this->generateQueryClassName($modelClassName) : null;
			$tableSchema    = $db->getTableSchema($tablename);
			
			//  model params
			$params = [
				"tableName" => $tablename,
				"className" => $modelClassName,
				"queryClassName" => $queryClassName,
				"tableSchema"    => $tableSchema,
				"labels"         => $this->generateLabels($tableSchema),
				"rules"          => $this->generateRules($tableSchema),
				"relations"      => (isset($modelRelations[ $tablename ])) ? $modelRelations[ $tablename ] : [],
			];
			
			//  define model path
			$alias = Yii::getAlias("@" . str_replace('\\', '/', $this->ns));
			$path  = "$alias/$modelClassName.php";
			$base  = "$alias/base/{$modelClassName}Base.php";
			
			//  create both models
			$files[] = new CodeFile($path, $this->render("model.php", $params));
			$files[] = new CodeFile($base, $this->render("model-base.php", $params));
			
			//  create query model if necessary
			if ($queryClassName) {
				$params[ "className" ]      = $queryClassName;
				$params[ "modelClassName" ] = $modelClassName;
				
				$alias = Yii::getAlias("@" . str_replace('\\', '/', $this->queryNs));
				$path  = "$alias/$queryClassName.php";
				
				$files[] = new CodeFile($path, $this->render("query.php", $params));
			}
		}
		
		return $files;
	}
}