<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantSalesReturnGui\Communication\MerchantSalesReturnGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesReturnGui\MerchantSalesReturnGuiConfig getConfig()
 */
class MerchantSalesReturnCreateForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_RETURN_MERCHANT_ORDERS = 'returnMerchantOrders';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateForm::OPTION_RETURN_REASONS
     *
     * @var string
     */
    protected const OPTION_RETURN_REASONS = 'option_return_reasons';

    /**
     * @var string
     */
    protected const TEMPLATE_PATH = '@MerchantSalesReturnGui/SalesReturn/Create/_partials/return-create-merchant-order.twig';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([static::OPTION_RETURN_REASONS]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->addReturnMerchantOrdersField($builder, $options);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addReturnMerchantOrdersField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_RETURN_MERCHANT_ORDERS,
            CollectionType::class,
            [
                'entry_type' => MerchantOrderReturnCreateSubForm::class,
                'entry_options' => [
                    static::OPTION_RETURN_REASONS => $options[static::OPTION_RETURN_REASONS],
                ],
                'label' => false,
                'attr' => [
                    'template_path' => static::TEMPLATE_PATH,
                ],
            ],
        );

        return $this;
    }
}
