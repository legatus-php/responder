<?php

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use Http\Factory\Discovery\HttpFactory;
use Mimey\MimeTypes;

/**
 * @return BasicResponder
 */
function create_responder(): BasicResponder
{
    return new BasicResponder(
        HttpFactory::responseFactory(),
        HttpFactory::streamFactory(),
        new MimeTypes()
    );
}
