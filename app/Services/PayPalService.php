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
            return $this->accessToken;
        }

        try {
            $http = Http::withBasicAuth($this->clientId, $this->clientSecret)->asForm();
            $http = $this->prepareHttp($http);

            $response = $http->post("{$this->apiUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials'
            ]);

            if ($response->successful()) {
                $this->accessToken = $response->json()['access_token'];
                return $this->accessToken;
            }

            Log::error('PayPal: Failed to get access token', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \Exception('Failed to get PayPal access token');

        } catch (\Exception $e) {
            Log::error('PayPal: Exception getting access token', [
                'message' => $e->getMessage()
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
                        'value' => number_format((float)$price, 2, '.', ''),
                        'currency_code' => config('paypal.currency', 'EUR')
                    ],
                    'setup_fee_failure_action' => 'CANCEL',
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
     */
    public function createSubscription($planId, $returnUrl, $cancelUrl)
    {
        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Prefer' => 'return=representation',
            ]);
            $http = $this->prepareHttp($http);

            $response = $http->post("{$this->apiUrl}/v1/billing/subscriptions", [
                    'plan_id' => $planId,
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
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('PayPal: Subscription created', [
                    'subscription_id' => $data['id']
                ]);
                return $data;
            }

            Log::error('PayPal: Failed to create subscription', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \Exception('Failed to create PayPal subscription: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('PayPal: Exception creating subscription', [
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obtener detalles de una suscripción
     */
    public function getSubscription($subscriptionId)
    {
        try {
            $token = $this->getAccessToken();

            $http = Http::withToken($token);
            $http = $this->prepareHttp($http);

            $response = $http->get("{$this->apiUrl}/v1/billing/subscriptions/{$subscriptionId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal: Failed to get subscription', [
                'subscription_id' => $subscriptionId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('PayPal: Exception getting subscription', [
                'subscription_id' => $subscriptionId,
                'message' => $e->getMessage()
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
}
