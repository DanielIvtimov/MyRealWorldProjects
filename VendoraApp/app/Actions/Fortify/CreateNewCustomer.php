<?php 

namespace App\Actions\Fortify;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

final class CreateNewCustomer implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered customer.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input){
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(Customer::class)],
            'password' => $this->passwordRules(),
            'phone' => ['required', 'string', 'max:30'],
        ])->validate();

        $customer = Customer::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'password' => Hash::make($input['password']), 
            'is_active' => true,
         ]);

        // send the welcome email
        return $customer;
    }
}