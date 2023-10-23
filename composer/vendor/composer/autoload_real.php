<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitd03d8751a19ae9f182d66ed9b874a88c
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitd03d8751a19ae9f182d66ed9b874a88c', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitd03d8751a19ae9f182d66ed9b874a88c', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitd03d8751a19ae9f182d66ed9b874a88c::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
