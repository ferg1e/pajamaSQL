<?php

namespace purple;

class ModelFactory extends \pjsql\AdapterFactory {
    protected static function databaseHandle() {
        return new \pjsql\Mysql(
            MYSQL_HOST,
            MYSQL_USERNAME,
            MYSQL_PASSWORD,
            MYSQL_DATABASE);
    }
}
