<?php

namespace JunaidQadirB\Cray\Traits;


trait RedirectsWithFlash
{
    public function success($message, $route, ...$routeArgs)
    {
        request()->session()->flash('alert-success', $message);
        if ($route === 'back') {
            return redirect()->back();

        }

        return redirect()->route($route, ...$routeArgs);

    }

    public function error($message, $route)
    {
        request()->session()->flash('alert-danger', $message);

        return redirect()->route($route);

    }

    public function info($message, $route)
    {
        request()->session()->flash('alert-info', $message);

        return redirect()->route($route);

    }
}
