<?php

namespace Webkul\Webfont\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Webfont Repository
 *
 */

class WebfontRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\Webfont\Contracts\Webfont';
    }
}
