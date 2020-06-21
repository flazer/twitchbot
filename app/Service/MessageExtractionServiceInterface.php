<?php
namespace App\Service;

use Minicli\App;

interface MessageExtractionServiceInterface {

    public function __construct(App $app);

    public function extract(String $raw);
}
