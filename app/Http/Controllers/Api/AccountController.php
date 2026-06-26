<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;
use App\Services\SoapService;
use App\Services\RabbitMqService;
use Throwable;

class AccountController extends Controller
{
    protected $soapService;
    protected $rabbitMqService;
    
    public function __construct(
        SoapService $soapService,
        RabbitMqService $rabbitMqService
    ) {
        $this->soapService = $soapService;
        $this->rabbitMqService = $rabbitMqService;
    }
    #[OA\Get(
        path: "/api/v1",
        summary: "Get all accounts",
        tags: ["Accounts"],
        security: [["ApiKeyAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Accounts retrieved successfully",
                content: new OA\JsonContent(
                    required: ["status", "message", "data", "meta"],
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Accounts retrieved successfully"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/Account")
                        ),
                        new OA\Property(
                            property: "meta",
                            properties: [
                                new OA\Property(property: "service_name", type: "string", example: "Account-Service"),
                                new OA\Property(property: "api_version", type: "string", example: "v1"),
                            ],
                            type: "object"
                        ),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Unauthorized"),
                        new OA\Property(property: "errors", nullable: true, example: null),
                    ],
                    type: "object"
                )
            ),
        ]
    )]
    public function index()
    {
        return $this->successResponse(
            'Accounts retrieved successfully',
            Account::query()->orderBy('id')->get()
        );
    }

    #[OA\Get(
        path: "/api/v1/{id}",
        summary: "Get account detail",
        tags: ["Accounts"],
        security: [["ApiKeyAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Account ID",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Account retrieved successfully",
                content: new OA\JsonContent(
                    required: ["status", "message", "data", "meta"],
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Account retrieved successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Account"),
                        new OA\Property(
                            property: "meta",
                            properties: [
                                new OA\Property(property: "service_name", type: "string", example: "Account-Service"),
                                new OA\Property(property: "api_version", type: "string", example: "v1"),
                            ],
                            type: "object"
                        ),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Unauthorized"),
                        new OA\Property(property: "errors", nullable: true, example: null),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 404,
                description: "Account not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Account not found"),
                        new OA\Property(property: "errors", nullable: true, example: null),
                    ],
                    type: "object"
                )
            ),
        ]
    )]
    public function show(string $id)
    {
        $account = Account::query()
            ->where('id', $id)
            ->orWhere('account_number', $id)
            ->first();

        if (!$account) {
            return $this->errorResponse('Account not found', 404);
        }

        return $this->successResponse('Account retrieved successfully', $account);
    }

    #[OA\Post(
        path: "/api/v1",
        summary: "Create new account",
        tags: ["Accounts"],
        security: [["ApiKeyAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["account_number", "name", "email"],
                properties: [
                    new OA\Property(property: "account_number", type: "string", example: "102022400310"),
                    new OA\Property(property: "name", type: "string", example: "Febriyan Maulana Ibrahim"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "febriyan@example.com"),
                    new OA\Property(property: "balance", type: "number", format: "float", example: 50000),
                    new OA\Property(property: "status", type: "string", enum: ["active", "inactive"], example: "active"),
                    new OA\Property(property: "role", type: "string", example: "customer"),
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Account created successfully",
                content: new OA\JsonContent(
                    required: ["status", "message", "data", "meta"],
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Account created successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Account"),
                        new OA\Property(
                            property: "meta",
                            properties: [
                                new OA\Property(property: "service_name", type: "string", example: "Account-Service"),
                                new OA\Property(property: "api_version", type: "string", example: "v1"),
                            ],
                            type: "object"
                        ),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Unauthorized"),
                        new OA\Property(property: "errors", nullable: true, example: null),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation failed",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Validation failed"),
                        new OA\Property(property: "errors", type: "object"),
                    ],
                    type: "object"
                )
            ),
        ]
    )]
    public function store(Request $request)
    {
        $ssoUser = $request->attributes->get('sso_user');
        $isSso = false;

        if ($ssoUser) {
            $profile = $ssoUser['profile'] ?? [];
            $email = $profile['email'] ?? null;
            $nim = $profile['nim'] ?? null;
            $name = $profile['name'] ?? null;
            $isSso = true;

            if (!$email || !$nim || !$name) {
                return $this->errorResponse('Validation failed', 422, [
                    'sso_user' => ['SSO token profile must contain email, nim, and name.'],
                ]);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'account_number' => ['required', 'string', 'max:255', 'unique:accounts,account_number'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:accounts,email'],
                'balance' => ['nullable', 'numeric', 'min:0'],
                'status' => ['nullable', Rule::in(['active', 'inactive'])],
                'role' => ['nullable', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }
        }

        try {
            if ($isSso) {
                $account = Account::updateOrCreate(
                    ['email' => $email],
                    [
                        'account_number' => $nim,
                        'name' => $name,
                        'balance' => $request->input('balance', 0),
                        'status' => $request->input('status', 'active'),
                        'role' => $request->input('role', 'customer')
                    ]
                );
            } else {
                $account = Account::create([
                    'account_number' => $request->input('account_number'),
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'balance' => $request->input('balance', 0),
                    'status' => $request->input('status', 'active'),
                    'role' => $request->input('role', 'customer')
                ]);
            }

            if ($isSso) {
                try {
                    $this->soapService->sendAuditLog($account);
                } catch (Throwable $e) {
                    report($e);
                }

                try {
                    $this->rabbitMqService->publishAccountEvent($account);
                } catch (Throwable $e) {
                    report($e);
                }
            }

            return $this->successResponse(
                $isSso ? 'Account synchronized successfully' : 'Account created successfully',
                $account,
                201
            );
        } catch (Throwable $e) {
            report($e);

            return $this->errorResponse('Internal server error', 500);
        }
    }

    private function successResponse(string $message, mixed $data, int $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'meta' => [
                'service_name' => 'Account-Service',
                'api_version' => 'v1'
            ]
        ], $statusCode);
    }

    private function errorResponse(string $message, int $statusCode, mixed $errors = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}
