<?php
/**
 * This is the template for generating the model class of a specified table.
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

namespace <?php echo $generator->ns ?>\base;

use Yii;
<?php
$hasDone = [];
foreach ($relations as $name => $relation) {
	if (!in_array($relation[1], $hasDone)) {
		echo "use $generator->ns\\$relation[1];\n";
	}
	$hasDone[] = $relation[1];
}
?>

/**
 * Class <?php echo "$className \n"; ?>
 * This is the base model class for table "<?php echo $generator->generateTableName($tableName) ?>".
 *
 * @package <?php echo $generator->ns ?>\base
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?php echo "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
 * Relations :
<?php foreach ($relations as $name => $relation): ?>
 * @property <?php echo $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?php echo $className ?>Base extends <?php echo '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
	/**
	 * @inheritdoc
	 */
	public static function tableName () { return '<?php echo $generator->generateTableName($tableName) ?>'; }

<?php if ($generator->db !== 'db'): ?>
	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb () { return Yii::$app->get('<?php echo $generator->db ?>'); }
<?php endif; ?>

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [<?php echo "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels ()
	{
		return [
		<?php foreach ($labels as $name => $label): ?>
	<?php echo "'$name' => " . $generator->generateString($label) . ",\n" ?>
		<?php endforeach; ?>];
	}
<?php foreach ($relations as $name => $relation): ?>
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function get<?php echo $name ?>()
	{
		<?php echo $relation[0] . "\n" ?>
	}
<?php endforeach; ?>
<?php if ($queryClassName): ?>
	<?php
	$queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
	echo "\n";
	?>
	/**
	 * @inheritdoc
	 * @return <?php echo $queryClassFullName ?> the active query used by this AR class.
	 */
	public static function find()
	{
		return new <?php echo $queryClassFullName ?>(get_called_class());
	}
<?php endif; ?>
}
