<?php

namespace App\Http\Requests;

use App\DTOs\CustomerDTO;
use App\DTOs\OrderDTO;
use App\DTOs\OrderItemDTO;
use Illuminate\Validation\Rule;

/**
 * @property string $customer_uuid
 * @property string $customer_name
 * @property string $customer_email
 * @property array $products
 */
class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //TODO:: Consider using nested objects like customer.name and customer.email 
            //       to avoid using prefixes and improve data structure clarity.
            'customer_uuid' => [
                'nullable',
                'uuid',
                Rule::exists('customers', 'uuid')
            ],

            //TODO:: Customer name is always required, even if it’s ignored when the customer already exists by email.
            //       We should handle this case properly. I’d prefer not to make an extra DB query to check if the email exists,
            //       purely for performance reasons. Instead, consider sending only the customer_uuid.
            //       There might be a better approach here, but I’m not sure at the moment.
            'customer_name' => [
                'nullable',
                'required_without:customer_uuid',
                'string',
                'max:255'
            ],
            'customer_email' => [
                'nullable',
                'required_without:customer_uuid',
                'email',
                'max:255',
            ],

            'products' => 'required|array|min:1',
            //TODO:: Remove the product_id prefix, since it's already nested inside the products array.
            //TODO:: We may need to refactor existence checks. 
            //       Since we’ll later need to send a request to fetch prices, it becomes an extra query,
            //       which could negatively impact performance.
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ];
    }

    public function toDTO(): OrderDTO
    {
        $customerDTO = new CustomerDTO($this->customer_uuid, $this->customer_name, $this->customer_email);
        
        $productsDTO = array_map(
            fn(array $item) => new OrderItemDTO(
                productId: $item['product_id'],
                quantity: $item['quantity'],
            ),
            $this->products
        );

        return new OrderDTO($customerDTO, $productsDTO);
    }
}
