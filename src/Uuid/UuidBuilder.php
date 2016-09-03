<?php

namespace Uuid;

use Ramsey\Uuid\Builder\UuidBuilderInterface;
use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\Converter\NumberConverterInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class UuidBuilder implements UuidBuilderInterface
{
    /**
     * @var NumberConverterInterface
     */
    private $converter;

    /**
     * @param NumberConverterInterface $converter
     */
    public function __construct(NumberConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function build(CodecInterface $codec, array $fields)
    {
        return new Uuid($fields, $this->converter, $codec);
    }
}
