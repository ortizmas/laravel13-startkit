<?php
namespace App\Dto;

use Illuminate\Contracts\Validation\Validator;


class ProductDto extends AbstractDto implements InterfaceDto
{
    public function __construct(
        public readonly string $title,
        public readonly float $price,
        public readonly int $stock = 0
    ) {
      $this->validate();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.',
            'stock.required' => 'The stock field is required.',
            'stock.integer' => 'The stock must be an integer.',
            'stock.min' => 'The stock must be at least 0.',
        ];
    }

    public function validator(): Validator
    {
      return validator($this->toArray(), $this->rules(), $this->messages());
    }

    public function validate(): array
    {
      return $this->validator()->validate();
    }
}