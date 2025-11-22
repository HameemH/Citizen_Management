<?php

return [
    'default_rate' => 0.008, // 0.8%

    'rates' => [
        'residential' => 0.008,
        'commercial' => 0.012,
        'industrial' => 0.015,
    ],

    'due_month' => 6,
    'due_day' => 30,

    'valuation_cycle_days' => 365,
    'valuation_upcoming_threshold_days' => 60,
];
