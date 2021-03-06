<?php
namespace App\Utils;

use Illuminate\Support\Facades\Blade;
use Psy\Exception\FatalErrorException;

class CustomBladeCompiler
{
    public static function render($string, $data=[])
    {
        $php = Blade::compileString($string);

        $obLevel = ob_get_level();
        ob_start();
        extract($data, EXTR_SKIP);

        try {
            eval('?' . '>' . $php);
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new FatalErrorException($e);
        }

        return ob_get_clean();
    }
}
