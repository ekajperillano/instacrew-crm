<?php
namespace App\Traits;

trait FilterTrait
{
    public function applyFilter($query, $filters) {
        foreach($filters as $column => $filter) {
            $operator = $filter->operator ?? null ;
            $value = $filter->value ?? null;
            
            switch($operator) {
                case 'with':
                    if(!is_null($value) && $value == true) {
                        $query->whereHas($column);
                    }
                break;
                case 'without':
                    if(!is_null($value) && $value == true) {
                        $query->whereDoesntHave(str_replace('__', '', $column));
                    }
                break;
                case 'in':
                    if(count($value) > 0) {
                        $query->whereIn($column, $value);
                    }
                break;
                case 'between':
                    if(!is_null($value->from)) {
                        $query->where($column, '>=', $value->from);
                    }
                    if(!is_null($value->to)) {
                        $query->where($column, '<=', $value->to);
                    }
                break;
                default:
                    if(!is_null($value)) {
                        $query->where($column, $value);
                    }
                break;
            }
        }

        return $query;
    }
}