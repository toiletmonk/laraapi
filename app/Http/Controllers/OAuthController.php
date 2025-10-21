<?php

namespace App\Http\Controllers;

use App\Services\OAuthService;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    protected OAuthService $oauthService;

    public function __construct(OAuthService $oauthService)
    {
        $this->oauthService = $oauthService;
    }

    public function redirectToProvider(Request $request, string $provider)
    {
        return $this->oauthService->redirect($request, $provider);
    }

    public function callback(string $provider)
    {
        try {
            $data = $this->oauthService->callback($provider);

            return response()->json($data);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
