<?php

namespace AppBundle\Serializer\Normalizer;

use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormView;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class FormViewNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormView $form
     * @param null     $format
     * @param array    $context
     */
    public function normalize($form, $format = null, array $context = [])
    {
        $context = array_merge([
            'form_name_simple' => false,
        ], $context);

        return [
            'action' => $form->vars['action'],
            'method' => $form->vars['method'],
            'fields' => $this->normalizeForm($form, $format, $context),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FormView;
    }

    /**
     * @param FormView $form
     * @param          $format
     * @param          $context
     *
     * @return array
     */
    private function normalizeForm(FormView $form, $format, $context, $deep = 0)
    {
        $name = $context['form_name_simple'] ? $form->vars['name'] : $form->vars['full_name'];

        $prefixes = $form->vars['block_prefixes'];
        while (0 === strpos($type = array_pop($prefixes), '_')) {
            continue;
        }

        if (true === $form->vars['compound']) {
            $fields = [];
            foreach ($form->children as $child) {
                $fields[] = $this->normalizeForm($child, $format, $context, $deep + 1);
            }

            if (0 !== $deep && !is_numeric($form->vars['name'])) {
                return [$form->vars['name'] => [
                    'label' => $form->vars['label'],
                    'name' => $name,
                    'type' => $type,
                    'fields' => $fields,
                ]];
            }

            return call_user_func_array('array_merge', $fields);
        }

        $formData = [
            'label' => $this->translator->trans($form->vars['label'], [], $form->vars['translation_domain']),
            'name' => $name,
            'type' => $type,
            'value' => $this->normalizer->normalize($form->vars['data'], $format, $context),
            'required' => $form->vars['required'],
            'disabled' => $form->vars['disabled'],
            'read_only' => $form->vars['read_only'],
        ];

        if (array_key_exists('choices', $form->vars)) {
            foreach ((array) $form->vars['choices'] as $choice) {
                /* @var ChoiceView $choice */
                // $formData['choices'][$choice->label] = $choice->value; // Replace after upgrade symfony
                $formData['choices'][$choice->value] = $choice->label;
            }
        }

        return [
            $form->vars['name'] => $formData,
        ];
    }
}
