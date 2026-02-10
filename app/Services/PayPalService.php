<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private $apiUrl;
    private $clientId;
    private $clientSecret;
    private $accessToken;

    public function __construct()
    {
        $this->apiUrl = config('paypal.api_url');
        $this->clientId = config('paypal.client_id');
        $this->clientSecret = config('paypal.client_secret');
    }

    /**
     * Preparar HTTP client con opciones de SSL según el entorno
     */
    private function prepareHttp($http)
    {
        // En sandbox y local, deshabilitar verificación SSL para evitar errores de certificado
        if (config('paypal.mode') === 'sandbox' || config('app.env') === 'local') {
            return $http->withOptions(['verify' => false]);
        }
        return $http;
    }

    /**
     * Obtener token de acceso de PayPal
     */
    private function getAccessToken()
    {
        if ($this->accessToken) {
            Log::debug('PAYPAL SERVICE: Usando token en caché');
            return $this->accessToken;
        }

        Log::info('PAYPAL SERVICE: Solicitando nuevo access token', [
            'api_url' => $this->apiUrl,
            'client_id' => substr($this->clientId, 0, 20) . '...',
            'mode' => config('paypal.mode')
        ]);

        try {
            $http = Http::withBasicAuth($this->clientId, $this->clientSecret)->asForm();
            $http = $this->prepareHttp($http);

            $response = $http->post("{$this->apiUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials'
            ]);

            if ($response->successful()) {
                $this->accessToken = $response->json()['access_token'];
                Log::info('PAYPAL SERVICE: Access token obtenido correctamente');
                return $this->accessToken;
            }

            Log::error('PAYPAL SERVICE ERROR: No se pudo obtener access token', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \Exception('Failed to get PayPal access token');

        } catch (\Exception $e) {
            Log::error('PAYPAL SERVICE EXCEPCIÓN: Error obteniendo access token', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    /**
     * Crear un producto en PayPal (requerido antes de crear planes)
     */
    public function createProduct($name, $description)
    {
        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);
            $http = $this->prepareHttp($http);

            $response = $http->post("{$this->apiUrl}/v1/catalogs/products", [
                'name' => $name,
                'description' => $description,
                'type' => 'SERVICE',
                'category' => 'SOFTWARE',
                'image_url' => config('app.url') . '/images/logo.png',
                'home_url' => config('app.url'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('PayPal: Product created', ['product_id' => $data['id']]);
                return $data;
            }

            Log::error('PayPal: Failed to create product', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \Exception('Failed to create PayPal product: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('PayPal: Exception creating product', [
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Crear un plan de facturación en PayPal
     */
    public function createBillingPlan($productId, $planName, $description, $price, $interval = 'MONTH')
    {
        try {
            $token = $this->getAccessToken();

            $intervalCount = $interval === 'YEAR' ? 1 : 1;

            $http = Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Prefer' => 'return=representation',
            ]);
            $http = $this->prepareHttp($http);

            $planData = [
                'product_id' => $productId,
                'name' => $planName,
                'description' => $description,
                'status' => 'ACTIVE',
                'billing_cycles' => [
                    [
                        'frequency' => [
                            'interval_unit' => $interval,
                            'interval_count' => $intervalCount
                        ],
                        'tenure_type' => 'REGULAR',
                        'sequence' => 1,
                        'total_cycles' => 0,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'value' => number_format((float)$price, 2, '.', ''),
                                'currency_code' => config('paypal.currency', 'EUR')
                            ]
                        ]
                    ]
                ],
                'payment_preferences' => [
                    'auto_bill_outstanding' => true,
                    'setup_fee' => [
                        'value' => '0',
                        'currency_code' => config('paypal.currency', 'EUR')
                    ],
                    'setup_fee_failure_action' => 'CONTINUE',
                    'payment_failure_threshold' => 3
                ]
            ];

            Log::info('PayPal: Creating billing plan with data', [
                'price_raw' => $price,
                'price_formatted' => number_format((float)$price, 2, '.', ''),
                'plan_data' => $planData
            ]);

            $response = $http->post("{$this->apiUrl}/v1/billing/plans", $planData);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('PayPal: Billing plan created', [
                    'plan_id' => $data['id'],
                    'name' => $planName
                ]);
                return $data;
            }

            Log::error('PayPal: Failed to create billing plan', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \Exception('Failed to create PayPal billing plan: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('PayPal: Exception creating billing plan', [
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obtener detalles de un plan
     */
    public function getPlan($planId)
    {
        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token);
            $http = $this->prepareHttp($http);

            $response = $http->get("{$this->apiUrl}/v1/billing/plans/{$planId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (\Exception $e) {
            Log::error('PayPal: Exception getting plan', [
                'plan_id' => $planId,
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Crear una suscripción
     *
     * IMPORTANTE: Para que el usuario vea el precio real (no 0,00€), eliminamos
     * el start_time futuro y usamos el inicio inmediato del primer ciclo de facturación.
     * Esto hace que PayPal muestre el precio correcto durante la aprobación.
     */
    public function createSubscription($planId, $returnUrl, $cancelUrl, $price = null)
    {
        Log::info('=== PAYPAL SERVICE: CREAR SUSCRIPCIÓN ===', [
            'plan_id' => $planId,
            'return_url' => $returnUrl,
            'cancel_url' => $cancelUrl,
            'price' => $price
        ]);

        try {
            $token = $this->getAccessToken();

            // ============================================================
            // FIX: No usar start_time futuro para que el primer cobro sea inmediato
            // y el usuario vea el precio real durante la aprobación
            // ============================================================
            $requestData = [
                'plan_id' => $planId,
                // NO incluir 'start_time' para que comience inmediatamente
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'locale' => 'es-ES',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'payment_method' => [
                        'payer_selected' => 'PAYPAL',
                        'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED'
                    ],
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl
                ]
            ];

            // ============================================================
            // FIX PRECIO 0,00€: En lugar de usar setup_fee (que se muestra separado),
            // NO modificamos el plan aquí. El precio correcto ya viene del plan
            // configurado en PayPal. Al no usar start_time futuro, el primer
            // ciclo de facturación se cobra inmediatamente y se muestra al usuario.
            // ============================================================

            Log::info('PAYPAL SERVICE: Request configurado para cobro inmediato del primer ciclo', [
                'plan_id' => $planId,
                'precio_esperado' => $price
            ]);

            Log::info('PAYPAL SERVICE: Enviando request a PayPal API', [
                'endpoint' => "{$this->apiUrl}/v1/billing/subscriptions",
                'request_data' => $requestData
            ]);

            $http = Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Prefer' => 'return=representation',
            ]);
            $http = $this->prepareHttp($http);

            $response = $http->post("{$this->apiUrl}/v1/billing/subscriptions", $requestData);

            Log::info('PAYPAL SERVICE: Respuesta recibida', [
                'status_code' => $response->status(),
                'successful' => $response->successful()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('PAYPAL SERVICE: Suscripción creada exitosamente', [
                    'subscription_id' => $data['id'] ?? 'N/A',
                    'status' => $data['status'] ?? 'N/A',
                    'links' => $data['links'] ?? [],
                    'full_response' => $data
                ]);
                return $data;
            }

            Log::error('PAYPAL SERVICE ERROR: Fallo al crear suscripción', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json()
            ]);

            throw new \Exception('Failed to create PayPal subscription: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('PAYPAL SERVICE EXCEPCIÓN: Error creando suscripción', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'plan_id' => $planId
            ]);
            throw $e;
        }
    }

    /**
     * Obtener detalles de una suscripción
     */
    public function getSubscription($subscriptionId)
    {
        Log::info('=== PAYPAL SERVICE: OBTENER SUSCRIPCIÓN ===', [
            'subscription_id' => $subscriptionId
        ]);

        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token);
            $http = $this->prepareHttp($http);

            Log::info('PAYPAL SERVICE: Consultando suscripción en PayPal', [
                'endpoint' => "{$this->apiUrl}/v1/billing/subscriptions/{$subscriptionId}"
            ]);

            $response = $http->get("{$this->apiUrl}/v1/billing/subscriptions/{$subscriptionId}");

            Log::info('PAYPAL SERVICE: Respuesta de getSubscription', [
                'status_code' => $response->status(),
                'successful' => $response->successful()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('PAYPAL SERVICE: Suscripción obtenida', [
                    'subscription_id' => $subscriptionId,
                    'status' => $data['status'] ?? 'N/A',
                    'plan_id' => $data['plan_id'] ?? 'N/A',
                    'subscriber_email' => $data['subscriber']['email_address'] ?? 'N/A',
                    'create_time' => $data['create_time'] ?? 'N/A',
                    'billing_info' => $data['billing_info'] ?? [],
                    'full_response' => $data
                ]);
                return $data;
            }

            Log::error('PAYPAL SERVICE ERROR: Fallo al obtener suscripción', [
                'subscription_id' => $subscriptionId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('PAYPAL SERVICE EXCEPCIÓN: Error obteniendo suscripción', [
                'subscription_id' => $subscriptionId,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return null;
        }
    }

    /**
     * Cancelar una suscripción
     */
    public function cancelSubscription($subscriptionId, $reason = 'User requested cancellation')
    {
        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json',
            ]);
            $http = $this->prepareHttp($http);

            $response = $http->post("{$this->apiUrl}/v1/billing/subscriptions/{$subscriptionId}/cancel", [
                    'reason' => $reason
                ]);

            if ($response->status() === 204) {
                Log::info('PayPal: Subscription cancelled', [
                    'subscription_id' => $subscriptionId
                ]);
                return true;
            }

            Log::error('PayPal: Failed to cancel subscription', [
                'subscription_id' => $subscriptionId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('PayPal: Exception cancelling subscription', [
                'subscription_id' => $subscriptionId,
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Desactivar un plan de facturación en PayPal
     */
    public function deactivatePlan($planId)
    {
        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json',
            ]);
            $http = $this->prepareHttp($http);

            $response = $http->post("{$this->apiUrl}/v1/billing/plans/{$planId}/deactivate");

            if ($response->status() === 204 || $response->successful()) {
                Log::info('PayPal: Plan deactivated', ['plan_id' => $planId]);
                return true;
            }

            Log::error('PayPal: Failed to deactivate plan', [
                'plan_id' => $planId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('PayPal: Exception deactivating plan', [
                'plan_id' => $planId,
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Obtener transacciones de una suscripción
     */
    public function getSubscriptionTransactions($subscriptionId, $startTime = null, $endTime = null)
    {
        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token);
            $http = $this->prepareHttp($http);

            $params = [];
            if ($startTime) {
                $params['start_time'] = $startTime;
            } else {
                // Por defecto, últimos 30 días
                $params['start_time'] = now()->subDays(30)->toIso8601String();
            }
            if ($endTime) {
                $params['end_time'] = $endTime;
            } else {
                $params['end_time'] = now()->toIso8601String();
            }

            $response = $http->get("{$this->apiUrl}/v1/billing/subscriptions/{$subscriptionId}/transactions", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal: Failed to get subscription transactions', [
                'subscription_id' => $subscriptionId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('PayPal: Exception getting subscription transactions', [
                'subscription_id' => $subscriptionId,
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Listar todas las suscripciones
     */
    public function listSubscriptions($planId = null, $status = null, $pageSize = 20)
    {
        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token);
            $http = $this->prepareHttp($http);

            $params = [
                'page_size' => $pageSize,
                'total_required' => 'true'
            ];

            if ($planId) {
                $params['plan_id'] = $planId;
            }

            $response = $http->get("{$this->apiUrl}/v1/billing/subscriptions", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal: Failed to list subscriptions', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('PayPal: Exception listing subscriptions', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Listar todos los planes de PayPal
     */
    public function listPlans($pageSize = 20)
    {
        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token);
            $http = $this->prepareHttp($http);

            $response = $http->get("{$this->apiUrl}/v1/billing/plans", [
                'page_size' => $pageSize,
                'total_required' => 'true'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (\Exception $e) {
            Log::error('PayPal: Exception listing plans', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Listar todas las transacciones de la cuenta PayPal
     */
    public function listTransactions($startDate, $endDate, $pageSize = 100)
    {
        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token);
            $http = $this->prepareHttp($http);

            $response = $http->get("{$this->apiUrl}/v1/reporting/transactions", [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'page_size' => $pageSize,
                'fields' => 'all'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['transaction_details'] ?? [];
            }

            Log::error('PayPal: Failed to list transactions', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];

        } catch (\Exception $e) {
            Log::error('PayPal: Exception listing transactions', [
                'message' => $e->getMessage()
            ]);
            return [];
        }
    }
}
