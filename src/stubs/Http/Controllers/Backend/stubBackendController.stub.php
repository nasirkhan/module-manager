<?php

namespace {{namespace}}\{{moduleName}}\Http\Controllers\Backend;

use Nasirkhan\Admin\Traits\Authorizable;
use Nasirkhan\Admin\Http\Controllers\BackendBaseController;

class {{moduleNamePlural}}Controller extends BackendBaseController
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = '{{moduleNamePlural}}';

        // module name
        $this->module_name = '{{moduleNameLowerPlural}}';

        // directory path of the module
        $this->module_path = '{{moduleNameLower}}::backend';

        // module icon
        $this->module_icon = 'fa-regular fa-sun';

        // module model name, path
        $this->module_model = "{{namespace}}\{{moduleName}}\Models\{{moduleName}}";
    }

}
