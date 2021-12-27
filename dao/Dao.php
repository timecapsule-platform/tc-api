<?php

/*
 * SpeCtre
 * Alexandros Preventis
 * Feb 14, 2016
 */

require_once "utils/SafeSelectQueryBuilder.php";
require_once "exceptions/ApplicationException.php";

/**
 * Description of Dao
 *
 * @author apreventis
 */
abstract class Dao {

    protected static $TABLE_NAME;  // must be overriden by descendants
    protected static $ALLOWED_FILTERS;  // must be overriden by descendants
    protected static $ALLOWED_OPERATORS = array(
        "\$gt" => ">",
        "\$lt" => "<",
        "\$gte" => ">=",
        "\$lte" => "<=",
        "\$ne" => "!=",
        "\$in",
        "\$nin"
    );
    protected $queryBuilder;

    public function __construct() {
        $this->queryBuilder = new SafeSelectQueryBuilder();
        $this->queryBuilder->allow(static::$TABLE_NAME, static::$ALLOWED_FILTERS);
        $this->queryBuilder->operators(static::$ALLOWED_OPERATORS);
    }

}
