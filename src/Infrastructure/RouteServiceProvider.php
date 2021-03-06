<?php

namespace Francken\Infrastructure;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Francken\Application\Career\AcademicYear;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Francken\Infrastructure\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require base_path('src/Infrastructure/Http/routes.php');
        });

        $router->bind(
            'year',
            function (string $year) : AcademicYear {
                return AcademicYear::fromString($year);
            }
        );

    }
}
