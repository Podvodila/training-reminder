Laravel = {!! json_encode(array_merge([
    'csrfToken' => csrf_token(),
    'appName' => config('app.name'),
    'appUrl' => config('app.url'),
],
config('global_with_js')
)) !!};
