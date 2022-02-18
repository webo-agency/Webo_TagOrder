<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminWeboTagOrderController extends ModuleAdminController
{
    protected $max_image_size = null;
    public $theme_name;
    public $img_path;
    public $img_url;

    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
        $this->id_lang = $this->context->language->id;
        $this->default_form_language = $this->context->language->id;

        Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminModules').'&configure=webo_tagorder');
    }

}
