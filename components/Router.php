<?php
if(!defined('ROOT')){header('Location: /');exit;}
class Router
{
    private $routes;
    public function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
    public function run()
    {
        $uri = $this->getURI();
        if(empty($uri)) 
        {
            $controllerName = "SiteController";
            $controllerFile = ROOT.'/controllers/'.$controllerName.'.php';
            if (file_exists($controllerFile)) include_once($controllerFile);
            $controllerObject = new $controllerName;
            $result = call_user_func(array($controllerObject, "actionIndex"));
            if ($result != null) return;
        }
        //var_dump(user::userInfo());
        $user = user::userInfo();
        if($user !== false && $user['settings'][2] == "") $test = user::update(['last_activity'],[date('Y-m-d H:i:s')]);
        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri)) {
                
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                // Определить контроллер, action, параметры
 
                $segments = explode('/', $internalRoute);
                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = 'action' . ucfirst(array_shift($segments));
                $parameters = $segments;

                // Подключить файл класса-контроллера
                $controllerFile = ROOT.'/controllers/'.$controllerName . '.php';
                if (file_exists($controllerFile)) include_once($controllerFile);

                // Создать объект, вызвать метод (т.е. action)
                $controllerObject = new $controllerName;

                /* Вызываем необходимый метод ($actionName) у определенного 
                 * класса ($controllerObject) с заданными ($parameters) параметрами
                 */
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);

                // Если метод контроллера успешно вызван, завершаем работу роутера
                
                if ($result != null) break;
            }
        }
    }

}
