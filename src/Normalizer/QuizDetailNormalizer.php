<?php

declare(strict_types=1);

namespace App\Normalizer;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Quiz;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Webmozart\Assert\Assert;

class QuizDetailNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'app_quiz_detail_normalizer_already_called';

    public function __construct(
        // private NormalizerInterface $normalizer,
        private IriConverterInterface $iriConverter
    ) {
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        Assert::isInstanceOf($object, Quiz::class);
        Assert::keyNotExists($context, self::ALREADY_CALLED);

        $context[self::ALREADY_CALLED] = true;
        $context['groups'] = 'app:quiz:read';

        $data = $this->normalizer->normalize($object, $format, $context);
        // if (array_key_exists('parent', $data) && $data['parent']) {
        //     $parentiri = $data['parent'];

        //     $parentresource = $this->iriconverter->getresourcefromiri($parentiri);
        //     $data['parent'] = $parentresource;
        // }

        return $data;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Quiz && $context['groups'] === 'app:quiz:read';
    }

    public function getSupportedTypes($format)
    {
        return ['application/json', 'application/ld+json'];
    }
}
