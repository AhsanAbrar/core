<?php

namespace Spanvel\Support;

use Illuminate\Support\HtmlString;
use RuntimeException;

class AppDataDirective
{
    /**
     * Invoke the directive with a fully-qualified class name.
     *
     * @param string $class Raw class name passed from Blade. May contain ::class or quotes.
     */
    public function __invoke(string $class): HtmlString
    {
        $class = trim($class, " \t\n\r\0\x0B'\"");

        if (str_ends_with($class, '::class')) {
            $class = substr($class, 0, -7);
        }

        if (! class_exists($class)) {
            throw new RuntimeException("AppData class [{$class}] not found.");
        }

        $instance = app($class);

        $json = json_encode($instance, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return new HtmlString(
            "<script>window.AppData={$json};</script>"
        );
    }
}
