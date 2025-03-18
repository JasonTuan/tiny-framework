<?php

namespace TinyFramework;

use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;
use ReflectionException;
use Smarty\Exception;
use TinyFramework\Models\Http\Request;
use TinyFramework\Models\Http\Response;
use TinyFramework\Services\DBService;
use TinyFramework\Services\RouterService;
use Smarty\Smarty;

class App
{
    public RouterService $router;
    public Request $request;
    public Smarty $view;
    public DBService $db;

    /**
     * @throws \Exception
     */
    public function __construct(
        public bool $debug = false,
    )
    {
        Carbon::setLocale('vi');
        $this->view = new Smarty();
        $this->view->setTemplateDir(__DIR__ . '/Views');
        $this->view->setCompileDir(storePath('view_compiles'));
//        $this->view->setCacheDir(__DIR__ . '/../storages/cache');
        //$this->view->setConfigDir(__DIR__ . '/../storages/configs');
    }

    public function getConfig(): array
    {
        return require __DIR__ . '/../configs.php';
    }

    /**
     * @throws Exception|ReflectionException
     */
    #[NoReturn] public function run(): void
    {
        if (empty($this->getToken())) {
            $this->genToken();
        }
        $this->db = new DBService();
        $this->request = new Request();
        $this->router = new RouterService();
        $this->view->assign('router', $this->router);
        $this->view->assign('csrf', $this->getToken());
        $this->view->assign('app_paths', json_encode($this->router->getRoutes()));
        if ($this->router->currentRoute !== null) {
            $this->request->routerParams = $this->router->currentRoute->params;
            $this->router->currentRoute->handle();
        } else {
            render('Home/404.tpl', [], Response::HTTP_NOT_FOUND)->send();
        }
    }

    public function terminate(): void
    {
        $this->db->closeConnection();
    }

    public function genToken(): void
    {
        $token = md5(uniqid());
        $this->setSession('csrf_token', $token);
    }

    public function checkToken(?string $token): bool
    {
        return $this->getSession('csrf_token', null, true) === $token;
    }

    public function getToken(): string
    {
        return $this->getSession('csrf_token', '');
    }

    public function setSession(string $key, mixed $value): void
    {
        $_SESSION[$key]=$value;
    }

    public function getSession(string $key, mixed $defaultValue = null, bool $revoke = false): mixed
    {
        $value = $defaultValue;
        if (key_exists($key, $_SESSION)) {
            $value = $_SESSION[$key];
            if ($revoke) {
                unset($_SESSION[$key]);
            }
        }

        return $value;
    }

}
