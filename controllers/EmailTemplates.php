<?php namespace System\Controllers;

use Str;
use Lang;
use File;
use Flash;
use Backend;
use Redirect;
use BackendMenu;
use Backend\Classes\Controller;
use System\Models\EmailTemplate;
use System\Classes\ApplicationException;
use Exception;

/**
 * Email templates controller
 *
 * @package october\system
 * @author Alexey Bobkov, Samuel Georges
 *
 */
class EmailTemplates extends Controller
{

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $requiredPermissions = ['system.manage_email_templates'];

    public $listConfig = ['templates' => 'config_templates_list.yaml', 'layouts' => 'config_layouts_list.yaml'];
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
    }

    public function index()
    {
        /* @todo Remove line if year >= 2015 */ if (!\System\Models\EmailLayout::whereCode('default')->count()) { \Eloquent::unguard(); with(new \System\Database\Seeds\SeedSetupEmailLayouts)->run(); }

        EmailTemplate::syncAll();
        $this->getClassExtension('Backend.Behaviors.ListController')->index();
        $this->bodyClass = null;
    }

    public function formBeforeSave($model)
    {
        $model->is_custom = true;
    }

}