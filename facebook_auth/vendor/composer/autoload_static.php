<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5a8f200e4ae29d001485a2261c8f0a8f
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Facebook\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Facebook\\' => 
        array (
            0 => __DIR__ . '/..' . '/facebook/graph-sdk/src/Facebook',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5a8f200e4ae29d001485a2261c8f0a8f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5a8f200e4ae29d001485a2261c8f0a8f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5a8f200e4ae29d001485a2261c8f0a8f::$classMap;

        }, null, ClassLoader::class);
    }
}
