<?php

require_once dirname(__FILE__).'\..\src\backup.php';
require_once dirname(__FILE__).'\..\src\BackupException.php';
require_once 'PHPUnit/Extensions/Database/TestCase.php';

/**
 * Test class for BACKUP.
 * Generated by PHPUnit on 2012-02-09 at 16:51:21.
 */
class BACKUPTest extends PHPUnit_Extensions_Database_TestCase
{
    /** @var BACKUP */
    protected $object,
    /** @var array - test options */
        $options=array(
        'host'=>'localhost',
        'user'=>'root',
        'password'=>'',
        'base'=>'test',
    );
    /** @var PDO */
    protected $pdo=null;

    protected function getConnection()
    {
        $this->pdo = new PDO("mysql:dbname=".$this->options['base'].";host=".$this->options['host'], $this->options['user'], $this->options['password']);
        return $this->createDefaultDBConnection($this->pdo, $this->options['base']);
    }

    protected function getDataSet()
    {
        return $this->createXMLDataSet(dirname(__FILE__)."/Zodiak.xml");
    }

    /**
     * проверка разных режимов функционирования функции options
     * @covers BACKUP::options
     */
    public function testOptions()
    {
        $class = new ReflectionClass('BACKUP');
        $opt = $class->getProperty('opt');
        $opt->setAccessible(true);

        // check  options(sttr,value)
        $this->object = new BACKUP($this->options);

        $this->object->options('myoption',1);
        $x=$opt->getValue($this->object);
        $this->assertEquals($x['myoption'],1);

        // check  options(sttr,value)
        $pastvalue=$x['progressdelay'];
        $this->object->options('progressdelay',$pastvalue+1);
        $x=$opt->getValue($this->object);
        $this->assertEquals($pastvalue+1,$x['progressdelay']);

        // check options(aray)
        $method=$x['method'];
        $this->object->options(array('progressdelay'=>$pastvalue));
        $x=$opt->getValue($this->object);
        $this->assertEquals($pastvalue,$x['progressdelay']); // new value been set
        $this->assertEquals($method,$x['method']); // old value still a same
    }

    /**
     * So create dataset. Make backup. Clear dataset. Restore from backup and compare
     * @covers BACKUP::restore
     * @covers BACKUP::make_backup
     */
    public function testComplex()
    {
        // Remove the following lines when you implement this test.
        $connection=$this->getConnection();
        $this->object = new BACKUP($this->options);

        // restore from
        $this->pdo->query('set NAMES "utf8";');
        $this->pdo->query('drop table if exists `zodiak`;');
        $options=array('file'=>dirname(__FILE__).'\zodiak.phpmyadmin.sql');
        $this->assertTrue(is_readable($options['file']));
        $this->object->options($options);
        $this->object->restore(); // so zodiak table been created

        $dataSet = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($connection);
        $dataSet->addTable('Zodiak', 'SELECT * FROM zodiak'); // additional tables
        $expectedDataSet = $this->createXMLDataSet(dirname(__FILE__)."/Zodiak.xml");

        $this->assertDataSetsEqual($expectedDataSet, $dataSet);

        // so look at `make backup`
        $this->object->options(array('include'=>'Zodiak','exclude'=>'','dir'=>'.','file'=>dirname(__FILE__).'/db-backup-' . date('Ymd') . '.sql.gz'));
        $this->object->make_backup();
      //  $options=array('file'=>dirname(__FILE__).'/db-backup-' . date('Ymd') . '.sql.gz');
        $this->object->options($options);
        $this->assertTrue(is_readable($options['file']));
        $this->object->restore();

        $dataSet = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($connection);
        $dataSet->addTable('Zodiak', 'SELECT * FROM zodiak'); // additional tables
        $expectedDataSet = $this->createXMLDataSet(dirname(__FILE__)."/Zodiak.xml");

        $this->assertDataSetsEqual($expectedDataSet, $dataSet);
    }

    /**
     * Chеcking explicit situations
     * @covers BACKUP::restore
     * @expectedException BackupException
     */
    public function testWrongdatabaseUses()
    {
        $this->object = new BACKUP($this->options);
        $this->object->options('sql','uses `wrong base name`;');
        $this->object->restore();
    }

    /**
     * Chеcking wrong file name
     * @covers BACKUP::restore
     * @expectedException BackupException
     */
    public function testWrongFileNameGz()
    {
        $this->object = new BACKUP($this->options);
        $this->object->options('file','xx:/xx/xx/xx.sql.gz');
        $this->object->restore();
    }

    /**
     * Chеcking wrong file name
     * @covers BACKUP::restore
     * @expectedException BackupException
     */
    public function testWrongFileNameSql()
    {
        $this->object = new BACKUP($this->options);
        $this->object->options('file','xx:/xx/xx/xx.sql');
        $this->object->restore();
    }
    /**
     * Chеcking wrong file name
     * @covers BACKUP::restore
     * @expectedException BackupException
     */
    public function testWrongFileNameBz2l()
    {
        $this->object = new BACKUP($this->options);
        $this->object->options('file','xx:/xx/xx/xx.sql.bz2');
        $this->object->restore();
    }

}
?>
