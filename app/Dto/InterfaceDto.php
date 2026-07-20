<?php

namespace App\Dto;

interface InterfaceDto
{
    public function rules(): array;
    public function messages(): array;
    public function validator(): \Illuminate\Contracts\Validation\Validator;
    public function validate(): array;
}