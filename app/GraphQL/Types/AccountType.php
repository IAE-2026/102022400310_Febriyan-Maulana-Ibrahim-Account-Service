<?php

namespace App\GraphQL\Types;

use App\Models\Account;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AccountType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Account',
        'model' => Account::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::int(),
            ],
            'account_number' => [
                'type' => Type::string(),
            ],
            'name' => [
                'type' => Type::string(),
            ],
            'email' => [
                'type' => Type::string(),
            ],
            'balance' => [
                'type' => Type::float(),
            ],
            'status' => [
                'type' => Type::string(),
            ],
        ];
    }
}