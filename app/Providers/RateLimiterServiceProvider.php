<?php

namespace App\Providers;

use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimiterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureGoogleApiRateLimit();
    }

    private function configureGoogleApiRateLimit(): void
    {
        RateLimiter::for('google_map_api', function (Request $request) {
            if (RateLimiter::tooManyAttempts('google_map_api'.$request->user()->id, 5)) {
                $seconds = RateLimiter::availableIn('google_map_api'.$request->user()->id);
                throw new ThrottleRequestsException('Too many Attempts,Please try again in '.$seconds.' seconds.');
            }
            RateLimiter::increment('google_map_api'.$request->user()->id);
        });
    }
}
