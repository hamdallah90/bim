<?php

namespace App\Http\Controllers;

use App\Traits\CheckRoles;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Bekusc\Validation\Traits\AutoValidation;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use AutoValidation {
        AutoValidation::callAction as autoValidationCallAction;
    }
    use CheckRoles {
        CheckRoles::callAction as checkRolesCallAction;
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $this->checkRolesCallAction($method, $parameters);
        return $this->autoValidationCallAction($method, $parameters);
    }
}
