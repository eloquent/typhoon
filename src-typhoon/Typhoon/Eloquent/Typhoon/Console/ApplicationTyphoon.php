<?php
namespace Typhoon\Eloquent\Typhoon\Console;

class ApplicationTyphoon
{
    public function validateConstructor(array $arguments)
    {
        $argumentCount = count($arguments);

        if ($argumentCount > 0) {
            $check = function($argument, $index) {
                $check = function($value) {
                    return true;
                };
                if (!$check($argument)) {
                    throw new \InvalidArgumentException("Unexpected argument for parameter 'undefined' at index ".$index.".");
                }
            };
            for ($i = 0; $i < $argumentCount; $i ++) {
                $check($arguments[$i], $i);
            }
        }
    }
}
