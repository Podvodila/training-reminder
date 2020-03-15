Laravel = {!! json_encode([
    'csrfToken' => csrf_token(),
    'appName' => config('app.name'),
    'appUrl' => config('app.url'),
]
) !!};
