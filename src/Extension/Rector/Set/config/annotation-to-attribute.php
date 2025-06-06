<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(
        AnnotationToAttributeRector::class,
        [
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\Inject',
                'Reinfi\DependencyInjection\Attribute\Inject'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectConfig',
                'Reinfi\DependencyInjection\Attribute\InjectConfig'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectConstant',
                'Reinfi\DependencyInjection\Attribute\InjectConstant'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectContainer',
                'Reinfi\DependencyInjection\Attribute\InjectContainer'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectControllerPlugin',
                'Reinfi\DependencyInjection\Attribute\InjectControllerPlugin'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectDoctrineRepository',
                'Reinfi\DependencyInjection\Attribute\InjectDoctrineRepository'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectFilter',
                'Reinfi\DependencyInjection\Attribute\InjectFilter'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectFormElement',
                'Reinfi\DependencyInjection\Attribute\InjectFormElement'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectHydrator',
                'Reinfi\DependencyInjection\Attribute\InjectHydrator'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectInputFilter',
                'Reinfi\DependencyInjection\Attribute\InjectInputFilter'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectParent',
                'Reinfi\DependencyInjection\Attribute\InjectParent'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectValidator',
                'Reinfi\DependencyInjection\Attribute\InjectValidator'
            ),
            new AnnotationToAttribute(
                'Reinfi\DependencyInjection\Annotation\InjectViewHelper',
                'Reinfi\DependencyInjection\Attribute\InjectViewHelper'
            ),
        ]
    );
};
