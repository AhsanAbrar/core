<?php

namespace Spanvel\Directive;

use Illuminate\Support\HtmlString;

class AppDataDirective
{
    /**
     * Handle the invocation of the class.
     *
     * @param string $class FQCN from Blade (either 'Foo\Bar' or Foo\Bar::class)
     */
    public function __invoke(string $class): HtmlString
    {
        $class = trim((string) $class, " \t\n\r\0\x0B'\"");

        if (! class_exists($class)) {
            throw new \RuntimeException("AppData class [{$class}] not found.");
        }

        $instance = new $class;

        $json = json_encode($instance, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return new HtmlString("<script>window.AppData={$json}</script>");
    }
}
