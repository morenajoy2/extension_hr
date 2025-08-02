protected $routeMiddleware = [
    // ... other middleware
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
