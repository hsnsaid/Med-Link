<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class DoctorsFilter extends ApiFilter{
    protected $safeParams=[
        'name'=>['eq'],
        'gender'=>['eq'],
        'speciality'=>['eq'],
        'formations'=>['eq'],
        'typeConsultation'=>['eq'],
        'city'=>['eq'],
        'street'=>['eq'],
        'localisation'=>['eq'],
        'rating'=>['eq','gt','lt'],
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