<?php
include 'BasicORM/orm.php';
include 'crudui.php';

$pdo = new \PDO('mysql:host=localhost;dbname=CrudTest', 'root', '', array(
    \PDO::ATTR_PERSISTENT => true
));

class Test extends ORM\Model {
    protected $id = array('name');
    public $name;
    public $value;
}

$test = new Test();
$test->name = 'aName';
$test->value = 'aValue';

$cTest = new \CrudUI\CModel('\Test');
//$cTest->removeHeader('name')->addHeader('name');
$cTest->validate('name', '.*')->validate('value', '[a-zA-Z0-9]*');
$cTest->headerLength('name', 3)->headerType('name', \CrudUI\TypeText);
$cTest->data($test);

$cTest->checkAllData();
echo $cTest->generate();

?>
