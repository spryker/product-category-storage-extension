<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlueBackendApiApplicationAuthorizationConnector;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class GlueBackendApiApplicationAuthorizationConnectorConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Returns a list of protected endpoints.
     * - Structure example:
     * [
     *      '/example' => [
     *          'isRegularExpression' => false,
     *      ],
     *      '/\/example\/.+/' => [
     *          'isRegularExpression' => true,
     *          'methods' => [
     *              'patch',
     *              'delete',
     *          ],
     *      ],
     * ]
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getProtectedPaths(): array
    {
        return [];
    }
}
