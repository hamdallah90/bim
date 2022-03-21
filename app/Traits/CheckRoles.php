<?php

namespace App\Traits;

use Closure;
use Illuminate\Http\Request;

trait CheckRoles
{
    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $this->checkRoles(get_class($this), $method);
    }

    /**
     * check request with specified roles in roles config.
     *
     * @param  string  $class
     * @param  string  $method
     * @return void
     */
    protected function checkRoles(string $class, string $method)
    {
        $roles = $this->getRoles($class, $method);
        $request = request();

        if ($roles && $request->user()) {
            if (!in_array($request->user()->type, $roles)) {
                response('Unauthorized.', 401)->send();
                die();
            }
        }
    }

    /**
     * Get the roles.
     *
     * @param  string  $class
     * @param  string  $method
     * @return array
     */
    protected function getRoles(string $class, string $method)
    {
        $namespace = config("users_roles.namespace");

        if (is_null($namespace)) {
            $namespace = 'App\Http\Controllers';
        }

        $class_name = substr($class, strlen($namespace) + 1);

        return config("users_roles.roles.{$class_name}.{$method}");
    }
}
