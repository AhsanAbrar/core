<?php

namespace Spanvel\Directive;

use Illuminate\Support\HtmlString;

class AppDataDirective
{
    /**
     * Handle the invocation of the class.
     */
    public function __invoke($class): HtmlString
    {
        if (! class_exists($class)) {
            throw new \RuntimeException("AppData class [{$class}] not found.");
        }

        $data = json_encode(new $class);

        return new HtmlString(
            sprintf('<script>window.AppData = %s</script>', $data)
        );
    }
}
