<?php

namespace Fouladgar\MobileVerifier\Http\Controllers;

use Fouladgar\MobileVerifier\Concerns\VerifiesMobiles;
use Fouladgar\MobileVerifier\Contracts\TokenBrokerInterface;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use VerifiesMobiles;

    /**
     * @var TokenBrokerInterface
     */
    protected $tokenBroker;

    /**
     * Create a new controller instance.
     *
     * @param TokenBrokerInterface $tokenBroker
     * @return void
     */
    public function __construct(TokenBrokerInterface $tokenBroker)
    {
        $this->middleware('auth');

        $throttle = config('mobile_verifier.throttle', 10);

        $this->middleware("throttle:$throttle,1")->only('verify', 'resend');

        $this->tokenBroker = $tokenBroker;
    }
}