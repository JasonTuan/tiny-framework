<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{block name=title}Tiny Framework{/block}</title>
    <link rel="stylesheet" href="/compile/css/common.css">
    {block name=styles}{/block}
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{$router->route('homepage')}">Tiny Framework</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{$router->route('homepage')}">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main role="main" class="container">
        {block name=body}{/block}
    </main>
    <script>
        var token = "{$csrf}";
        var app_paths = {$app_paths};
    </script>
    <script type="module" src="/compile/js/common.js"></script>
    {block name=scripts}{/block}
</body>
</html>
