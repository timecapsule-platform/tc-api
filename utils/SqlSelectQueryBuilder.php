<?php

/*
 * SpeCtre
 * Alexandros Preventis
 * Feb 22, 2016
 */

/**
 * Description of SqlQueryBuilder
 *
 * @author apreventis
 */
class SqlSelectQueryBuilder {

    protected $joins;
    protected $selections;
    protected $table;
    protected $where;
    protected $limit;
    protected $offset;

    public function __construct() {

        $this->selectionsArray = array();
        $this->selections = "";
        $this->table = "";
        $this->joins = "";
        $this->where = "";
        $this->limit = "";
        $this->offset = "";
    }

    /**
     * 
     * @param string $table
     * @param string $column
     * @param array $columns
     * @return \SqlQueryBuilder
     */
    public function select($table, $column, ...$columns) {

        # if there is no other column with the given name
        if (!isset($this->selectionsArray[$column]))
            $this->selectionsArray[$column][$table] = $this->escape("$table.$column");
        # if a column with the given name has already been added, use an alias for 
        # the next occurences of this name
        else {
            if ($column === "*") # handle the star differently
                $this->selectionsArray[$column][$table] = $this->escape("$table.$column");
            else
                $this->selectionsArray[$column][$table] = $this->escape("$table.$column") . " AS " . $this->escape($table . "_" . $column);
        }

        # create the selections string
        if (empty($this->selections))
            $this->selections = "SELECT " . $this->selectionsArray[$column][$table];
        else
            $this->selections .= ", " . $this->selectionsArray[$column][$table];

        # repeat the process for all the columns
        for ($i = 0; $i < count($columns); $i++) {
            # if there is no other column with the given name
            if (!isset($this->selectionsArray[$columns[$i]]))
                $this->selectionsArray[$columns[$i]][$table] = $this->escape("$table.$columns[$i]");
            else {
                if ($columns[$i] === "*") # handle the star differently
                    $this->selectionsArray[$columns[$i]][$table] = $this->escape("$table.$columns[$i]");
                else
                    $this->selectionsArray[$columns[$i]][$table] = $this->escape("$table.$columns[$i]") . " AS " . $this->escape($table . "_" . $columns[$i]);
            }

            $this->selections .= ", " . $this->selectionsArray[$columns[$i]][$table];
        }

        return $this;
    }

    /**
     * 
     * @param string $table
     * @return \SqlQueryBuilder
     */
    public function from($table) {
        # insert the table to the array
        $this->table = " FROM " . $this->escape($table);

        return $this;
    }

    /**
     * 
     * @param type $table
     * @param type $on
     * @param type $type
     * @return \SqlQueryBuilder
     */
    public function join($table, $on, $type = "inner") {

        $this->joins .= " " . strtoupper($type) . " JOIN `$table` ON " . $this->escape($on[0]) . " = " . $this->escape($on[1]);

        return $this;
    }

    /**
     * 
     * @param type $identifier
     * @param type $arg
     * @param type $isPositive
     * @return \SqlQueryBuilder
     */
    public function where($identifier, $arg, $isPositive = true) {

        if (empty($this->where))
            $this->where = " WHERE " . $this->getWhereCondition($this->escape($identifier), $arg, $isPositive);
        else
            $this->where .= " AND " . $this->getWhereCondition($this->escape($identifier), $arg, $isPositive);

        return $this;
    }

    /**
     * 
     * @param type $val
     * @return \SqlQueryBuilder
     */
    public function limit($val) {

        $this->limit = " LIMIT $val";

        return $this;
    }

    /**
     * 
     * @param type $val
     * @return \SqlQueryBuilder
     */
    public function offset($val) {

        $this->offset = " OFFSET $val";

        return $this;
    }

    /**
     * 
     * @return type
     */
    public function build() {

        return $this->selections
                . $this->table
                . $this->joins
                . $this->where
                . $this->limit
                . $this->offset;
    }

    /**
     * Create the where clauses based on the type of the second argument.
     * @param type $identifier
     * @param type $arg
     * @param type $isPositive 
     * @return string
     */
    protected function getWhereCondition($identifier, $arg, $isPositive = true) {

        $condition = "";
        # if null
        if ($arg === null && !$isPositive)
            $condition = "$identifier IS NOT NULL";
        # if array
        else if (gettype($arg) === "array" && !$isPositive) {

            if (!empty($arg)) {
                $condition = "$identifier NOT IN (";

                for ($i = 0; $i < count($arg) - 1; $i++)
                    $condition .= "\"$arg[$i]\", ";
                $condition .= "\"" . $arg[count($arg) - 1] . "\")";
            }
        } else if (gettype($arg) === "string" && !$isPositive)
            $condition = "$identifier != $arg";
        else if (gettype($arg) === "integer" && !$isPositive)
            $condition = "$identifier != $arg";
        else if ($arg === null && $isPositive)
            $condition = "$identifier IS NULL";
        else if (gettype($arg) === "array" && $isPositive) {

            if (!empty($arg)) {
                $condition = "$identifier IN (";

                for ($i = 0; $i < count($arg) - 1; $i++)
                    $condition .= "\"$arg[$i]\", ";
                $condition .= "\"" . $arg[count($arg) - 1] . "\")";
            }
        } else if (gettype($arg) === "string" && $isPositive)
            $condition = "$identifier = $arg";
        else if (gettype($arg) === "integer" && $isPositive)
            $condition = "$identifier = $arg";
        else if (gettype($arg) === "object")
            $condition = "$identifier $arg->operator $arg->value";
        else
            throw new ApplicationException ();

        return $condition;
    }

    /**
     * Escape he identifier provided and return the escaped string.
     * @param type $arg
     * @return string
     * @throws Exception
     */
    protected function escape($arg) {

        if (gettype($arg) !== "string")
            throw new Exception("Not string identifier used: " . $arg, 500);

        $parts = explode(".", $arg);

        if (count($parts) > 2 || !count($parts))
            throw new ApplicationException("Invalid identifier used: " . $arg, 500);

        foreach ($parts as &$part)
            $part = "`$part`";

        return implode(".", $parts);
    }

}
