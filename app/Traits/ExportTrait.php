<?php
namespace App\Traits;

use Carbon\Carbon;

trait ExportTrait
{   
    public function getHeaderNames($fields) {
        $headers = [];
        if(gettype($fields) == 'string') {
            $fields = json_decode($fields);
        }

        foreach($fields as $index => $field) {
            if(isset($field->name) && isset($field->sortField)) {
                $headers[] = $field->title; 
            }
        }

        return $headers;
    }

    public function getColumnNames($fields) {
        $columns = [];
        
        if(gettype($fields) == 'string') {
            $fields = json_decode($fields);
        }

        foreach($fields as $index => $field) {
            if(isset($field->name) && isset($field->sortField)) {
                if(isset($field->callback)) {
                    $columns[] = [
                        'callback' => $field->callback,
                        'column' => $field->name
                    ];
                } else {
                    $columns[] = $field->name; 
                }
            }
        }
        
        return $columns;
    }

    public function getColumnValue($record, $field) {
        if(is_array($field)) {
            $callback = $field['callback'] ?? null;
            $column = $field['column'];
            switch ($callback) {
                case 'formatCustomer':
                    $relationships = explode('.', $column);

                    $value = null;
                    
                    foreach($relationships as $relationship) {
                         $value = (is_null($value)) ? $record->{$relationship} : $value->{$relationship};
                    }

                    return !is_null($value) ? $value->full_name : null;
                    break;
                case 'formatNumber':
                    return  number_format($record->{$column}, 2);
                    break;
                case 'allcap':
                    return strtoupper($record->{$column});
                    break;   
                case 'formatDate':
                    return Carbon::parse($record->{$column})->format(config('constants.au_date_format'));
                    break;   
                case 'customerStatusFormat':
                    return strtoupper(str_replace('_', ' ', $record->{$column}));
                    break;     
                default:
                    return $record->{$column};
                    break;
            }

        } else {
            return $record->{$field};
        }
        
    }
}