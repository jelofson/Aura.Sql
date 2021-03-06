<?php
namespace Aura\Sql\Connection;

/**
 * Test class for Mysql.
 * Generated by PHPUnit on 2011-06-21 at 16:49:57.
 */
class MysqlTest extends AbstractConnectionTest
{
    protected $extension = 'pdo_mysql';
    
    protected $connect_type = 'mysql';
    
    protected $expect_class = 'Aura\Sql\Connection\Mysql';    
    
    protected $create_table = "CREATE TABLE aura (
        id int NOT NULL AUTO_INCREMENT,
        name VARCHAR(50),
        PRIMARY KEY (id)
    )";
    
    protected $expect_fetch_table_cols = [
        'id' => [
            'name' => 'id',
            'type' => 'int',
            'size' => 11,
            'scope' => NULL,
            'default' => NULL,
            'require' => true,
            'primary' => true,
            'autoinc' => true,
        ],
        'name' => [
            'name' => 'name',
            'type' => 'varchar',
            'size' => 50,
            'scope' => NULL,
            'default' => NULL,
            'require' => false,
            'primary' => false,
            'autoinc' => false,
        ],
    ];
}
