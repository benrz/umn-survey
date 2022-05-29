<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'oci:dbname=//127.0.0.1:1521/PDBORCL', // Oracle
    'username' => 'OT',
    'password' => 'Orcl1234',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
