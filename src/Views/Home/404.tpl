{extends file='Layout/base.tpl'}

{block name=body}
    <h1>404 Not Found</h1>
    <p>The page you are looking for does not exist.</p>
    <p><a href="{$router->route('homepage')}">Go back to the homepage</a></p>
{/block}
