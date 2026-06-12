<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Services\SoapService;
use App\Services\RabbitMqService;

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
    path: "/api/v1/accounts",
    summary: "Get all accounts",
    tags: ["Accounts"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Success"
        )
    ]
)]
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Accounts retrieved successfully',
            'data' => Account::all(),
            'meta' => [
                'service_name' => 'Account-Service',
                'api_version' => 'v1'
            ]
        ]);
    }

    #[OA\Get(
    path: "/api/v1/accounts/{accountNumber}",
    summary: "Get account detail",
    tags: ["Accounts"],
    parameters: [
        new OA\Parameter(
            name: "accountNumber",
            in: "path",
            required: true,
            description: "Account Number",
            schema: new OA\Schema(type: "string")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Success"
        )
    ]
)]
    public function show($accountNumber)
    {
        $account = Account::where('account_number', $accountNumber)->first();

        if (!$account) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account not found',
                'errors' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Account retrieved successfully',
            'data' => $account,
            'meta' => [
                'service_name' => 'Account-Service',
                'api_version' => 'v1'
            ]
        ]);
    }

    #[OA\Post(
    path: "/api/v1/accounts",
    summary: "Create new account",
    tags: ["Accounts"],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["account_number", "name", "email"],
            properties: [
                new OA\Property(property: "account_number", type: "string"),
                new OA\Property(property: "name", type: "string"),
                new OA\Property(property: "email", type: "string"),
                new OA\Property(property: "balance", type: "number"),
                new OA\Property(property: "status", type: "string")
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: "Account created successfully"
        )
    ]
)]
    public function store(Request $request)
{
    $ssoUser = $request->attributes->get('sso_user');

    if (!$ssoUser) {
        return response()->json([
            'status' => 'error',
            'message' => 'SSO user not found'
        ], 401);
    }

    $profile = $ssoUser['profile'];

    try {

        $account = Account::updateOrCreate(
            [
                'email' => $profile['email']
            ],
            [
                'account_number' => $profile['nim'],
                'name' => $profile['name'],
                'balance' => 0,
                'status' => 'active',
                'role' => 'customer'
            ]
        );

        $soapResult = $this->soapService->sendAuditLog($account);

        $rabbitResult = $this->rabbitMqService->publishAccountEvent($account);

        return response()->json([
            'status' => 'success',
            'message' => 'Account synchronized successfully',
            'soap' => $soapResult,
            'rabbitmq' => $rabbitResult,
            'data' => $account,
            'meta' => [
                'service_name' => 'Account-Service',
                'api_version' => 'v1'
            ]
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
}