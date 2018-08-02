<?php

namespace App\Library\Util;

class Util
{
    /**
     * @param array $toGenerate
     * @return \Generator
     */
    public static function createGenerator(array $toGenerate): \Generator
    {
        foreach ($toGenerate as $key => $item) {
            yield ['key' => $key, 'item' => $item];
        }
    }
    /**
     * @param \DateTime|string|null $dateTime
     * @throws \RuntimeException
     * @return \DateTime
     */
    public static function toDateTime($dateTime = null): \DateTime
    {
        if (is_null($dateTime)) {
            $temp = new \DateTime();

            return \DateTime::createFromFormat(
                Util::getDateTimeApplicationFormat(),
                $temp->format(Util::getDateTimeApplicationFormat())
            );
        }

        if ($dateTime instanceof \DateTime) {
            return \DateTime::createFromFormat(
                Util::getDateTimeApplicationFormat(),
                $dateTime->format(Util::getDateTimeApplicationFormat())
            );
        }

        if (is_string($dateTime)) {
            $newDateTime = \DateTime::createFromFormat(
                Util::getDateTimeApplicationFormat(),
                $dateTime
            );

            if (!$newDateTime instanceof \DateTime) {
                $message = sprintf('Invalid date time');
                throw new \RuntimeException($message);
            }

            return $newDateTime;
        }

        if (is_int($dateTime)) {
            $newDateTime = new \DateTime('@'.$dateTime);

            return $newDateTime;
        }

        throw new \RuntimeException('Util::toDateTime() cannot return null but does');
    }
    /**
     * @param string $dateTime
     * @param string|null $format
     * @return bool
     */
    public static function isValidDate(string $dateTime, string $format = null): bool
    {
        $format = (is_null($format)) ? Util::getDateTimeApplicationFormat() : $format;
        $d = \DateTime::createFromFormat($format, $dateTime);

        return $d && $d->format($format) === $dateTime;
    }
    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public static function formatFromDate(\DateTime $dateTime = null): ?string
    {
        if (!$dateTime instanceof \DateTime) {
            return null;
        }

        return $dateTime->format(Util::getDateTimeApplicationFormat());
    }
    /**
     * @param object $object
     * @param string $field
     * @return mixed
     */
    public static function extractFieldFromObject(object $object, string $field)
    {
        return $object->{'get'.ucfirst($field)}();
    }
    /**
     * @param object $object
     * @param array $fields
     * @return array
     */
    public static function extractFieldsFromObject(object $object, array $fields): array
    {
        $extractedFields = [];
        foreach ($fields as $field) {
            $extractedFields[$field] = Util::extractFieldFromObject($object, $field);
        }

        return $extractedFields;
    }
    /**
     * @param array|iterable $objects
     * @param string $field
     * @return array
     */
    public static function extractFieldFromObjects(iterable $objects, string $field): array
    {
        $fields = [];
        $objectsGenerator = static::createGenerator($objects);

        foreach ($objectsGenerator as $object) {
            $fields[] = Util::extractFieldFromObject($object['item'], $field);
        }

        return $fields;
    }
    /**
     * @param object $object
     * @param array $fields
     */
    public static function setObjectFieldsByConvention(object $object, array $fields)
    {
        foreach ($fields as $fieldName => $fieldValue) {
            $object->{'set'.ucfirst($fieldName)}($fieldValue);
        }
    }
    /**
     * @return string
     */
    public static function getDateTimeApplicationFormat(): string
    {
        return 'Y-m-d H:m:s';
    }
    /**
     * @param \Closure $closure
     * @param int $currentIteration
     * @param int $maxIterations
     * @return mixed
     */
    public static function recursiveClosureExecution(
        \Closure $closure,
        int $currentIteration = 0,
        int $maxIterations = 1000
    ) {
        if ($currentIteration === $maxIterations) {
            $message = sprintf(
                'Maximum number of %d iterations occurred. Use Util::recursiveClosureExecution() with caution because it only implements recursion for you, not the logic behind the recursion.',
                $maxIterations
            );

            throw new \RuntimeException($message);
        }

        $product = $closure();

        if (is_null($product)) {
            $product = Util::recursiveClosureExecution($closure, ++$currentIteration, $maxIterations);
        }

        return $product;
    }
    /**
     * @param \Closure $executionClosure
     * @param array $data
     * @param int $currentIteration
     * @param int $maxIterations
     */
    public static function nonStopRecursiveExecution(
        \Closure $executionClosure,
        array $data,
        int $currentIteration = 0,
        int $maxIterations = 1000
    ) {
        if ($currentIteration === $maxIterations) {
            $message = sprintf(
                'Maximum number of %d iterations occurred. Use Util::recursiveClosureExecution() with caution because it only implements recursion for you, not the logic behind the recursion.',
                $maxIterations
            );

            throw new \RuntimeException($message);
        }

        $executionClosure($executionClosure, $data);
    }
}