<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class DoctorsFilter extends ApiFilter{
    protected $safeParams=[
        'name'=>['eq'],
        'gender'=>['eq'],
        'speciality'=>['eq'],
        'typeConsultation'=>['eq'],
        'city'=>['eq'],
        'street'=>['eq'],
        'rating'=>['eq','gt','lt'],
        'status'=>['eq']
    ];
    protected $columnMap=[
        'typeConsultation'=>'type_consultation'
    ];
    protected $operatorMap=[
        'eq'=>'=',
        'gt'=>'>',
        'lt'=>'<',
    ];
}