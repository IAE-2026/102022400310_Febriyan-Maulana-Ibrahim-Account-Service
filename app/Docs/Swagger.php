<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Account Service API",
    description: "API Documentation for Account Service"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Docker local server"
)]
#[OA\SecurityScheme(
    securityScheme: "ApiKeyAuth",
    type: "apiKey",
    in: "header",
    name: "X-IAE-KEY",
    description: "Masukkan API Key Anda (Contoh: 102022400310)"
)]
#[OA\Schema(
    schema: "Account",
    required: ["id", "account_number", "name", "email", "balance", "status"],
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "account_number", type: "string", example: "102022400310"),
        new OA\Property(property: "name", type: "string", example: "Febriyan Maulana Ibrahim"),
        new OA\Property(property: "email", type: "string", format: "email", example: "febriyan@example.com"),
        new OA\Property(property: "balance", type: "number", format: "float", example: 100000),
        new OA\Property(property: "status", type: "string", enum: ["active", "inactive"], example: "active"),
        new OA\Property(property: "role", type: "string", example: "customer"),
    ],
    type: "object"
)]
class Swagger
{
}
