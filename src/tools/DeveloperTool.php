<?php

namespace grigor\generator\tools;

use grigor\generator\forms\DevDirectoriesDto;
use grigor\generator\repository\DefaultSettingRepository;
use grigor\generator\scanner\AnnotationSearchSettings;
use grigor\generator\scanner\handler\detector\AnnotationDetector;
use grigor\generator\services\ApiManageService;
use grigor\generator\writer\DefaultSettingsManager;
use grigor\generator\writer\factory\FileSettingFactory;
use Psr\Container\ContainerInterface;
use Scanner\Scanner;

class DeveloperTool
{
    public static function beforeAppRunScanDevDirectories(array $config): void
    {
        $serviceFolder = rtrim($config['params']['serviceDirectoryPath'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $rulesPath = $config['params']['rulesPath'];
        $directories = $config['params']['devDirectories'];

        $annotationDetector = new AnnotationDetector();
        $apiManageService = new ApiManageService(
            new Scanner([
                'factories' => [
                    AnnotationDetector::class => function (ContainerInterface $container, $requestedName)
                    use ($annotationDetector) {
                        return $annotationDetector;
                    },
                ]
            ]),
            new DefaultSettingsManager(
                new FileSettingFactory($serviceFolder),
                new DefaultSettingRepository(),
                $rulesPath
            ),
            new AnnotationSearchSettings(),
            $annotationDetector
        );
        self::cleanServiceDirectory($serviceFolder);
        $apiManageService->scanDevDirectories(new DevDirectoriesDto($directories));
    }

    public static function cleanServiceDirectory(string $serviceFolder): void
    {
        $files = glob($serviceFolder . "/*");
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}