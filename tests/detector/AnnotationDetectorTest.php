<?php

namespace grigor\tests\detector;

use grigor\generator\scanner\handler\detector\AnnotationDetector;
use grigor\generator\scanner\support\ClassInfoSupport;
use Scanner\Driver\File\FileFactory;

class AnnotationDetectorTest extends DetectorTestCase
{
    public function testAnnotationDetector()
    {
        $annotationDetector = new AnnotationDetector();
        $factory = $this->getMockFactory();

        $normal = $annotationDetector->handle(
            $factory,
            self::DATA, 'Normal.php'
        );
        self::assertInstanceOf(\ReflectionClass::class, $normal);
        self::assertEquals('grigor\tests\detector\data\Normal', $normal->getName());
        $normal2 = $annotationDetector->handle(
            $factory,
            self::DATA, 'Normal2.php'
        );
        self::assertInstanceOf(\ReflectionClass::class, $normal2);
        self::assertEquals('grigor\tests\detector\data\Normal2', $normal2->getName());

        $bad = $annotationDetector->handle(
            $factory,
            self::DATA, 'Bad.php'
        );
        self::assertNotInstanceOf(\ReflectionClass::class, $bad);

        $bad2 = $annotationDetector->handle(
            $factory,
            self::DATA, 'Bad2.php'
        );
        self::assertNotInstanceOf(\ReflectionClass::class, $bad2);
    }

    public function testAnnotationDetectorEmpty()
    {
        $annotationDetector = new AnnotationDetector();
        $factory = $this->getMockFactory();

        $empty = $annotationDetector->handle(
            $factory,
            self::DATA, 'empty.php'
        );
        self::assertNotInstanceOf(\ReflectionClass::class, $empty);

        $noClass = $annotationDetector->handle(
            $factory,
            self::DATA, 'NoClass.php'
        );
        self::assertNotInstanceOf(\ReflectionClass::class, $noClass);
    }

    private function getMockFactory()
    {
        $fileFactory = new FileFactory();
        $fileFactory->needSupportsOf([
            'FILE' => [
                ClassInfoSupport::class
            ]
        ]);
        return $fileFactory;
    }
}
