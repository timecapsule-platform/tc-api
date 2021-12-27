<?php

/*
 * SpeCtre
 * Alexandros Preventis
 * Feb 23, 2016
 */

require_once "SqlSelectQueryBuilder.php";

/**
 * Description of SqlSafeSelectQueryBuilder
 *
 * @author apreventis
 */
class SafeSelectQueryBuilder extends SqlSelectQueryBuilder {

    private $allowedColumns;
    private $allowedOperators;

    public function __construct() {
        parent::__construct();
        $this->allowedColumns = [];
        $this->allowedOperators = [];
    }

    /**
     * 
     * @param type $columns
     * @return \SafeSelectQueryBuilder
     */
    public function allow($table, array $columns) {
        /**
         * Add the columns of the table that can be used to filter the results.
         * Note that if a column name is inserted more than once, the last
         * inserted table will remain
         */
        foreach ($columns as $col) {
            $this->allowedColumns[$col] = $table;
        }
   

        return $this;
    }

    /**
     * 
     * @param array $operators
     */
    public function operators(array $operators) {
        $this->allowedOperators = $operators;
    }

    public function filter(&$filters) {

        /**
         * To ensure that the filters contain ONLY allowed filters, we calculate 
         * the difference between the two arrays [A] - [B]. If the difference is
         * not the empty set, then there are filters that are not included in
         * the table's columns.
         */
        if (count(array_diff(array_keys($filters), array_keys($this->allowedColumns))))
            throw new ApplicationException("Invalid query parameters.", 400);

        # create limit and offset clauses if present
        if (isset($filters["limit"]) && is_numeric($filters["limit"])) {
            $this->limit($filters["limit"]);
            unset($filters["limit"]);
        }
        if (isset($filters["offset"]) && is_numeric($filters["offset"])) {
            $this->offset($filters["offset"]);
            unset($filters["offset"]);
        }

        foreach ($filters as $identifier => &$value) {

            if (empty($this->where))
                $this->where = " WHERE " . $this->getFiltersConditions($identifier, $value);
            else
                $this->where .= " AND " . $this->getFiltersConditions($identifier, $value);
        }

        return $this;
    }

    /**
     * Create the where clauses based on the type of the second argument.
     * @param type $identifier
     * @param type $value
     * @return string
     */
    protected function getFiltersConditions(&$identifier, &$value) {

        $params = $this->getExpression($identifier, $value);

        return $this->getWhereCondition($this->prefixColumn($identifier), $params->operand, $params->isPositive);
    }

    /**
     * Prefix the column with its table name
     * @param type $id
     * @return type
     */
    private function prefixColumn($id) {

        return $this->escape($this->allowedColumns[$id]) . "." . $this->escape($id);
    }

    /**
     * function that parses the value string and return two parameters:
     * 1. The operand of the expression in the appropriate format to be used by the
     * caller function.
     * 2. A boolean value that indicates that the expression is negation or not.
     * Both the returning pramaetees are wrapped into the $params variable.
     * @param type $value
     * @return type
     * @throws ApplicationException
     */
    private function getExpression($identifier, &$value) {

        # the object to be returned
        $params = new stdClass();

        # split the value string
        $valueList = explode(",", $value);

        switch (true) {
            case count($valueList) === 1:
                if ($value === "null") {
                    $params->operand = null;
                    $params->isPositive = true;
                } else {
                    $params->operand = ":$identifier";
                    $params->isPositive = true;
                }
                break;
            case count($valueList) === 2:
                if (!isset($this->allowedOperators[$valueList[0]]))
                    throw new ApplicationException("Invalid query operator: $valueList[0]", 400);

                switch ($valueList[0]) {
                    case "\$in":
                        $params->operand = [":$identifier"];
                        $params->isPositive = false;
                        break;
                    case "\$ne":
                        $params->operand = ":$identifier";
                        $params->isPositive = false;
                        break;
                    case "\$nin":
                        $params->operand = [":$identifier"];
                        $params->isPositive = false;
                        break;
                    default:
                        $params->operand->operator = $this->allowedOperators[$valueList[0]];
                        $params->operand->value = ":$identifier";
                        $params->isPositive = true;
                }

                $value = $valueList[1]; // update the value string by removing the operator
                break;
            case count($valueList) > 2:
                if (!isset($this->allowedOperators[$valueList[0]]))
                    throw new ApplicationException("Invalid query operator: $valueList[0]", 400);

                switch ($valueList[0]) {
                    case "\$in":
                        $params->operand = [];
                        for ($i = 0; $i < count($valueList) - 1; $i++) {
                            array_push($params->operand, ":$identifier");
                        }
                        $params->isPositive = true;
                        break;
                    case "\$nin":
                        $params->operand = [];
                        for ($i = 0; $i < count($valueList) - 1; $i++) {
                            array_push($params->operand, ":$identifier");
                        }
                        $params->isPositive = false;
                        break;
                    default:
                        throw new ApplicationException("Invalid query parameters: $identifier=$value", 400);
                }

                $value = implode(",", array_slice($valueList, 1, count($valueList) - 1)); // update the value string by removing the operator
                break;
            default:
                throw new ApplicationException("Invalid query parameters: $identifier=$value", 400);
        }
        return $params;
    }

}
