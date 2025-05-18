<?php

namespace App\Http\Requests;

abstract class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    abstract public function toDTO();
}