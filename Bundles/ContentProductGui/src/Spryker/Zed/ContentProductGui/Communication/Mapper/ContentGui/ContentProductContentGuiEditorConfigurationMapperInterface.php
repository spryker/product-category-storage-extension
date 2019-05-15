<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Mapper\ContentGui;

interface ContentProductContentGuiEditorConfigurationMapperInterface
{
    /**
     * @return array
     */
    public function getTemplates(): array;

    /**
     * @return string
     */
    public function getTwigFunctionTemplate(): string;
}