<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ViewGenerator
{
    /**
     * Dot separated path for view
     * @var string $viewPath
     */
    protected $viewPath = '';

    /**
     * @param array $data
     * @param string $viewName
     * @param array $mergeData
     * @return \Illuminate\Http\Response
     */
    protected function view($data = [], $viewName = '', $mergeData = [])
    {
        $viewName = $viewName ?: debug_backtrace()[1]['function'];

        if (!$this->viewPath) {
            $controllerName = Str::afterLast(get_called_class(), '\\');
            $controllerType = Str::beforeLast($controllerName, 'Controller');

            $path = array_map(function ($item) {
                return Str::kebab($item);
            }, explode('\\', $controllerType));

            if ($viewName == '__invoke') {
                return view(implode('.', $path), $data, $mergeData);
            }

            $path[] = Str::plural(array_pop($path));
            $this->viewPath = implode('.', $path);
        }

        return view("{$this->viewPath}.{$viewName}", $data, $mergeData);
    }
}
