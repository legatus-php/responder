Legatus Responder
=================

Build PSR-7 responses easily

[![Build Status](https://drone.mnavarro.dev/api/badges/legatus/responder/status.svg)](https://drone.mnavarro.dev/legatus/responder)

## Installation
You can install the Responder component using [Composer][composer]:

```bash
composer require legatus/responder
```

## Quick Start

```php
<?php

// This automatically detects installed PSR Message Factories :)
$responder = Legatus\Http\create_responder();

$response = $responder->json([
    'msg' => 'This is a cool json response',
]);

$response = $responder->redirect('/take/me/here');

$response = $responder->html('<h1>Hello World!</h1>');

$response = $responder->blob('/file/location.ext');

$response = $responder->raw();
```

For more details you can check the [online documentation here][docs].

## Community
We still do not have a community channel. If you would like to help with that, you can let me know!

## Contributing
Read the contributing guide to know how can you contribute to Quilt.

## Security Issues
Please report security issues privately by email and give us a period of grace before disclosing.

## About Legatus
Legatus is a personal open source project led by Matías Navarro Carter and developed by contributors.

[composer]: https://getcomposer.org/
[docs]: https://legatus.mnavarro.dev/components/responder