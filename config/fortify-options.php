<?php

return [
    'two-factor-authentication' => [
        // Time window tolerance (in 30-second steps)
        // 2 = ±60 seconds (1 minute tolerance)
        // 4 = ±120 seconds (2 minutes tolerance)
        // Increase this if OTP validation fails due to time drift
        'window' => 4,

        // Used only during first-time setup to detect device/server clock drift.
        // The detected drift is stored per user; normal verification still uses `window`.
        'setup_drift_window' => 240,
    ],
];
