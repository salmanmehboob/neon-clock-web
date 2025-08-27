<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiTesterController extends Controller
{
    public function index()
    {
        // example endpoints to show in UI
        $examples = [
            ['title' => 'List categories', 'method' => 'GET', 'endpoint' => '/api/categories'],
            ['title' => 'Get category', 'method' => 'GET', 'endpoint' => '/api/categories/{id}'],
            ['title' => 'List category images', 'method' => 'GET', 'endpoint' => '/api/category-images'],
            ['title' => 'List wallpaper', 'method' => 'GET', 'endpoint' => '/api/wallpapers'],
            ['title' => 'Get wallpaper', 'method' => 'GET', 'endpoint' => '/api/wallpapers/{id}'],
            // add more examples as you wish
        ];

        return view('apis.index', compact('examples'));
    }

    public function createToken(Request $request)
    {
        $user = $request->user();

        // ensure HasApiTokens trait is present
        if (! method_exists($user, 'createToken')) {
            return response()->json([
                'success' => false,
                'message' => 'Personal access tokens not configured on User model. Add HasApiTokens to User.',
            ], 422);
        }

        // create a short-lived token (you can add scopes if needed)
        $token = $user->createToken('api-tester')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function call(Request $request)
    {
        $request->validate([
            'endpoint'     => 'required|string',
            'method'       => 'required|string',
            'headers'      => 'nullable|array',
            'body'         => 'nullable|string',
            'bearer_token' => 'nullable|string',
        ]);

        $method   = strtoupper($request->method);
        $endpoint = $request->endpoint;
        $headers  = $request->input('headers', []); // ✅ always array, not HeaderBag
        $body     = $request->body;
        $bearer   = $request->bearer_token;

        // build full URL
//        $host = $request->getSchemeAndHttpHost();
        $host = rtrim(env('API_URL', $request->getSchemeAndHttpHost()), '/');

        if (Str::startsWith($endpoint, '/')) {
            $url = $host . $endpoint;
        } elseif (Str::startsWith($endpoint, 'http')) {
            $url = $endpoint;
        } else {
            $url = $host . '/' . $endpoint;
        }

         try {
            $client = Http::withHeaders((array)$headers)->timeout(30);

            if ($bearer) {
                $client = $client->withToken($bearer);
            }

            // detect if body is JSON
            $isJsonBody = false;
            if (!empty($body)) {
                $trim = trim($body);
                $isJsonBody = (Str::startsWith($trim, '{') || Str::startsWith($trim, '['));
            }

            // perform request
            if (in_array($method, ['GET', 'DELETE'])) {
                $response = $client->{$method}($url);
            } else {
                if ($isJsonBody) {
                    $response = $client
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->{$method}($url, json_decode($body, true));
                } elseif (!empty($body)) {
                    $response = $client->withBody($body, 'application/json')->{$method}($url);
                } else {
                    $response = $client->{$method}($url);
                }
            }

            $out = [
                'ok'      => $response->successful(),
                'status'  => $response->status(),
                'headers' => $response->headers(),
                'url' => $url, // <--- Add this line
            ];

            // try decode JSON
            $bodyText = $response->body();
            $decoded  = null;
            try {
                $decoded = json_decode($bodyText, true, 512, JSON_THROW_ON_ERROR);
            } catch (\Throwable $e) {
                $decoded = null;
            }

            $out['body'] = $decoded ?? $bodyText;

            return response()->json($out);
        } catch (\Throwable $e) {
            Log::error('API tester call failed: ' . $e->getMessage());
            return response()->json([
                'ok'    => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
