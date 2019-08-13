<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Collection;

use Spryker\Glue\Testify\OpenApi3\Object\Example;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

class Examples extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    public function getElementDefinition(): PropertyDefinition
    {
        return new PropertyDefinition(Example::class);
    }
}
