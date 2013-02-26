<?php
namespace foo;

abstract class TypeCheck
{
    public static function get($className, array $arguments = null)
    {
        if (static::dummyMode()) {
            return new DummyValidator();
        }
        if (!\array_key_exists($className, static::$instances)) {
            static::install($className, static::createValidator($className));
        }
        $validator = static::$instances[$className];
        if (null !== $arguments) {
            $validator->validateConstruct($arguments);
        }
        return $validator;
    }

    public static function install($className, $validator)
    {
        static::$instances[$className] = $validator;
    }

    public static function setDummyMode($dummyMode)
    {
        static::$dummyMode = $dummyMode;
    }

    public static function dummyMode()
    {
        return static::$dummyMode;
    }

    public static function setRuntimeGeneration($runtimeGeneration)
    {
        static::$runtimeGeneration = $runtimeGeneration;
    }

    public static function runtimeGeneration()
    {
        return static::$runtimeGeneration;
    }

    protected static function createValidator($className)
    {
        $validatorClassName = '\\foo\\Validator\\' . $className . 'TypeCheck';
        if (static::runtimeGeneration() && !\class_exists($validatorClassName)) {
            $dummyMode = static::dummyMode();
            static::setDummyMode(true);
            static::defineValidator($className);
            static::setDummyMode($dummyMode);
        }
        return new $validatorClassName;
    }

    protected static function defineValidator($className, \Eloquent\Typhoon\Generator\ValidatorClassGenerator $classGenerator = null)
    {
        if (null === $classGenerator) {
            $classGenerator = new \Eloquent\Typhoon\Generator\ValidatorClassGenerator;
        }
        eval('?>' . $classGenerator->generateFromClass(static::configuration(), new \ReflectionClass($className)));
    }

    protected static function configuration()
    {
        return new \Eloquent\Typhoon\Configuration\RuntimeConfiguration(\Eloquent\Cosmos\ClassName::fromString('\\foo'), true);
    }

    private static $instances = array();
    private static $dummyMode = false;
    private static $runtimeGeneration = false;
}
