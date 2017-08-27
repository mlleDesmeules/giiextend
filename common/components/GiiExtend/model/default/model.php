<?php
/**
 *  This is the template to generate the model class of a specified table
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

	echo "<?php";
?>

namespace <?php echo "$generator->ns"; ?>;

use <?php echo "$generator->ns\\base\\{$className}Base"; ?> as Base;

/**
 * Class <?php echo "$className \n"; ?>
 * This is the base model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
 * @package <?php echo "$generator->ns \n"; ?>
 */
class <?php echo $className; ?> extends Base
{
}
