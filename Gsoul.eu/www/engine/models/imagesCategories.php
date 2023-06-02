<?php

class DBimagesCategories extends Model {
    public function __construct() {
        $this->setTable('images_categories');
        $this->setPrimaryKey('idic');
    }
}
