<?php

namespace Tests\Unit;

use App\Http\Middleware\EnsureApiKey;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class EnsureApiKeyMiddlewareTest extends TestCase
{
    private EnsureApiKey $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new EnsureApiKey();
    }

    public function test_returns_503_when_api_key_not_configured(): void
    {
        config(['app.api_key' => null]);

        $request = Request::create('/api/test', 'GET');

        $response = $this->middleware->handle($request, fn () => new Response('OK'));

        $this->assertEquals(503, $response->getStatusCode());
        $this->assertStringContainsString(
            __('messages.api.key_not_configured'),
            $response->getContent()
        );
    }

    public function test_returns_503_when_api_key_is_empty_string(): void
    {
        config(['app.api_key' => '']);

        $request = Request::create('/api/test', 'GET');

        $response = $this->middleware->handle($request, fn () => new Response('OK'));

        $this->assertEquals(503, $response->getStatusCode());
    }

    public function test_returns_401_when_api_key_missing_from_request(): void
    {
        config(['app.api_key' => 'valid-key']);

        $request = Request::create('/api/test', 'GET');

        $response = $this->middleware->handle($request, fn () => new Response('OK'));

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertStringContainsString(
            __('messages.api.key_invalid'),
            $response->getContent()
        );
    }

    public function test_returns_401_when_api_key_is_invalid(): void
    {
        config(['app.api_key' => 'valid-key']);

        $request = Request::create('/api/test', 'GET');
        $request->headers->set('X-API-Key', 'wrong-key');

        $response = $this->middleware->handle($request, fn () => new Response('OK'));

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_allows_request_with_valid_x_api_key_header(): void
    {
        config(['app.api_key' => 'valid-key']);

        $request = Request::create('/api/test', 'GET');
        $request->headers->set('X-API-Key', 'valid-key');

        $response = $this->middleware->handle($request, fn () => new Response('OK'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    public function test_allows_request_with_valid_bearer_token(): void
    {
        config(['app.api_key' => 'valid-key']);

        $request = Request::create('/api/test', 'GET');
        $request->headers->set('Authorization', 'Bearer valid-key');

        $response = $this->middleware->handle($request, fn () => new Response('OK'));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_x_api_key_header_takes_precedence_over_bearer(): void
    {
        config(['app.api_key' => 'valid-key']);

        $request = Request::create('/api/test', 'GET');
        $request->headers->set('X-API-Key', 'valid-key');
        $request->headers->set('Authorization', 'Bearer wrong-key');

        $response = $this->middleware->handle($request, fn () => new Response('OK'));

        $this->assertEquals(200, $response->getStatusCode());
    }
}
