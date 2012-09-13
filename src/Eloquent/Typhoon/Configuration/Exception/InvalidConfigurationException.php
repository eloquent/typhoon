<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration\Exception;

use Exception;
use LogicException;
use Typhoon\Typhoon;

final class InvalidConfigurationException extends LogicException
{
    /**
     * @param string $propertyName
     * @param string $validationErrorMessage
     * @param Exception|null $previous
     */
    public function __construct(
        $propertyName,
        $validationErrorMessage,
        Exception $previous = null
    ) {
        $this->typhoon = Typhoon::get(__CLASS__, func_get_args());
        $this->propertyName = $propertyName;
        $this->validationErrorMessage = $validationErrorMessage;

        parent::__construct(
            sprintf(
                "Invalid configuration. '%s' %s.",
                $this->propertyName,
                $this->validationErrorMessage
            ),
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function propertyName()
    {
        $this->typhoon->propertyName(func_get_args());

        return $this->propertyName;
    }

    /**
     * @return string
     */
    public function validationErrorMessage()
    {
        $this->typhoon->validationErrorMessage(func_get_args());

        return $this->validationErrorMessage;
    }

    private $propertyName;
    private $validationErrorMessage;
    private $typhoon;
}
