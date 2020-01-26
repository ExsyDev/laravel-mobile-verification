<?php

namespace Fouladgar\MobileVerifier\Concerns;

use Illuminate\Config\Repository;
use Fouladgar\MobileVerifier\Notifications\VerifyMobile as VerifyMobileNotification;

trait MustVerifyMobile
{
    /**
     * Determine if the user has verified their mobile number.
     *
     * @return bool
     */
    public function hasVerifiedMobile(): bool
    {
        return $this->mobile_verified_at !== null;
    }

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified(): bool
    {
        return $this->forceFill(['mobile_verified_at' => $this->freshTimestamp()])->save();
    }

    /**
     * Send the mobile verification notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendMobileVerifierNotification(string $token): void
    {
        $this->notify(new VerifyMobileNotification($token));
    }

    /**
     * Get the mobile number that should be used for verification.
     *
     * @return string
     */
    public function getMobileForVerification(): string
    {
        return $this->{$this->getMobileField()};
    }

    /**
     * Get the recipients of the given message.
     *
     * @return mixed
     */
    public function routeNotificationForVerificationMobile(): string
    {
        return $this->{$this->getMobileField()};
    }

    /**
     * @return Repository|mixed
     */
    private function getMobileField()
    {
        return config('mobile_verifier.mobile_column', 'mobile');
    }
}
