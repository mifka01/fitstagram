<?php

if (!function_exists('d') && !function_exists('dd')) {
    /**
     * Debug function
     * d($var);
     *
     * @param mixed $var
     * @param mixed|null $caller
     */
    function d($var, $caller = null): void
    {
        if (!YII_DEBUG) {
            return;
        }

        if (!isset($caller)) {
            $debugBacktrace = debug_backtrace(1);
            $caller = array_shift($debugBacktrace);
        }
        echo '<code>File: ' . $caller['file'] . ' / Line: ' . $caller['line'] . '</code>';
        echo '<pre>';
        yii\helpers\VarDumper::dump($var, 10, true);
        echo '</pre>';
    }

    /**
     * Debug function with die() after
     * dd($var);
     *
     * @param mixed $var
     */
    function dd($var): void
    {
        if (!YII_DEBUG) {
            return;
        }

        $debugBacktrace = debug_backtrace(1);
        $caller = array_shift($debugBacktrace);
        d($var, $caller);
        die();
    }
}
