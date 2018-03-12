<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class AddressFormPlugin extends AbstractPlugin implements ManualOrderEntryFormPluginInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $formType = $this->getFactory()
            ->createAddressCollectionType();

        $dataProvider = $this->getFactory()
            ->createAddressCollectionDataProvider();

        $quoteTransfer = $builder->getData();
        $dataProvider->getData($quoteTransfer);

        $formType->buildForm(
            $builder,
            $dataProvider->getOptions()
        );
    }

}
