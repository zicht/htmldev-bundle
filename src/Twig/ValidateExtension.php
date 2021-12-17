<?php
/**
 * @copyright Zicht Online <http://zicht.nl>
 */
namespace Zicht\Bundle\HtmldevBundle\Twig;

use Rakit\Validation\Validator;
use Twig\Error\Error;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig extensions for validating data.
 */
class ValidateExtension extends AbstractExtension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('validate_context', [$this, 'validateContext'])
        ];
    }

    /**
     * @param array $context
     * @param array $rules
     * @throws \Twig\Error\Error
     */
    public function validateContext(array $context, array $rules)
    {
        $validator = new Validator();
        $validation = $validator->validate($context, $rules);
        if ($validation->fails()) {
            throw new Error(sprintf("validate_context error (%d errors)\n%s", $validation->errors()->count(), json_encode($validation->errors()->firstOfAll())));
        }
    }

    /**
     * The name of this twig extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'htmldev_twig_validate_extension';
    }
}
