<?php

namespace App\Common;


class WhereClause
{
    /*@var string */
    protected $column;
    protected $operator;
    protected $value;
    /*@var \Closure*/
    protected $function;

    protected $relation;
    protected $relationOperator;
    protected $relationCount;

    /**
     * WhereClause constructor.
     * @param $column
     * @param $operator
     * @param $value
     */
    public function __construct($column, $value = null, $operator = null)
    {
        if (is_string($column)) {
            $this->column = $column;
            $this->operator = $operator ?? '=';
            $this->value = $value;
            if (strtolower($operator) == 'like') {
                $this->value = "%$value%";
            }
            if (strtolower($operator) == 'startwith') {
                $this->operator = 'like';
                $this->value = "%$value";
            }
            if (strtolower($operator) == 'endwith') {
                $this->operator = 'like';
                $this->value = "$value%";
            }
        } else {
            if ($column instanceof \Closure) {
                $this->function = $column;
            }
        }
    }

    public static function query($column, $value = null, $operator = null)
    {
        return new WhereClause($column, $value, $operator);
    }

    public static function queryDate($column, $value, $operator = '=')
    {
        return new WhereClause($column, $value, 'fn_date_' . $operator);
    }

    public static function queryMonth($column, $value, $operator = '=')
    {
        return new WhereClause($column, $value, 'fn_month_' . $operator);
    }

    public static function queryYear($column, $value, $operator = '=')
    {
        return new WhereClause($column, $value, 'fn_year_' . $operator);
    }

    public static function queryByName($name, $column, $value = null, $operator = null)
    {
        return new WhereClause($column, $value, 'fn_' . $name . '_' . $operator);
    }

    public static function orQuery(array $clauses)
    {
        return new WhereClause(null, $clauses, 'or');
    }

    public static function queryNull($column)
    {
        return new WhereClause($column, null, 'fn_null');
    }

    public static function queryNotNull($column)
    {
        return new WhereClause($column, null, 'fn_notNull');
    }

    public static function queryDiff($column, $value = null)
    {
        return new WhereClause($column, $value, '<>');
    }

    public static function queryLike($column, $value = null)
    {
        return new WhereClause($column, $value, 'like');
    }

    public static function queryStartWith($column, $value = null)
    {
        return new WhereClause($column, $value, 'startwith');
    }

    public static function queryEndWith($column, $value = null)
    {
        return new WhereClause($column, $value, 'endwith');
    }

    public static function queryRaw(string $raw)
    {
        return new WhereClause($raw, null, 'raw');
    }

    public static function queryIn($column, $value = [])
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }
        if (is_numeric($value)) {
            $value = [$value];
        }
        return new WhereClause($column, $value, 'fn_in');
    }

    public static function queryNotIn($column, $value = [])
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }
        if (is_numeric($value)) {
            $value = [$value];
        }
        return new WhereClause($column, $value, 'fn_notin');
    }

    public static function queryRelationHas($relation, \Closure $closure = null, $operator = '>=', $count = 1)
    {
        $clause = new WhereClause(null);
        $clause->setOperator('has');
        $clause->setRelation($relation);
        $clause->setFunction($closure);
        $clause->setRelationOperator($operator);
        $clause->setRelationCount($count);
        return $clause;
    }

    public static function queryWhereHas($relation, array $clauses, $operator = '>=', $count = 1)
    {
        $clause = new WhereClause(null);
        $clause->setOperator('whereHas');
        $clause->setRelation($relation);
        $clause->setValue($clauses);
        $clause->setRelationOperator($operator);
        $clause->setRelationCount($count);
        return $clause;
    }

    public static function queryClosure(\Closure $closure)
    {
        $clause = new WhereClause(null);
        $clause->setOperator('function');
        $clause->setFunction($closure);
        return $clause;
    }

    /**
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param string $column
     */
    public function setColumn($column): void
    {
        $this->column = $column;
    }

    /**
     * @return null|string
     */
    public function getOperator(): ?string
    {
        return $this->operator;
    }

    /**
     * @param null|string $operator
     */
    public function setOperator(?string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return \Closure
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param \Closure $function
     */
    public function setFunction($function): void
    {
        $this->function = $function;
    }

    /**
     * @return mixed
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @param mixed $relation
     */
    public function setRelation($relation): void
    {
        $this->relation = $relation;
    }

    /**
     * @return mixed
     */
    public function getRelationCount()
    {
        return $this->relationCount;
    }

    /**
     * @param mixed $relationCount
     */
    public function setRelationCount($relationCount): void
    {
        $this->relationCount = $relationCount;
    }


    /**
     * @return mixed
     */
    public function getRelationOperator()
    {
        return $this->relationOperator;
    }

    /**
     * @param mixed $relationOperator
     */
    public function setRelationOperator($relationOperator): void
    {
        $this->relationOperator = $relationOperator;
    }

}
