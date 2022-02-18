<?php
/*
* 2021 Webo Agency
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2021 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Webo_TagOrder extends Module
{

  public $tabs = [
    [
        'name' => 'Tag Highlight', // One name for all langs
        'class_name' => 'AdminWebo_TagOrder',
        'visible' => true,
        'route_name' => 'webo_tagorder_config',
        'parent_class_name' => 'ShopParameters',
    ],
  ];

  public function __construct()
  {
      $this->name = 'webo_tagorder';
      $this->author = 'Webo Agency';
      $this->version = '1.0.0';
      $this->ps_versions_compliancy = array(
        'min' => '1.7.2.0', 
        'max' => _PS_VERSION_
      );
      $this->bootstrap = true;
      
      parent::__construct();

      $this->tabs = [
        [
            'tab' => 'AdminWeboTagOrder',
            'class_name' => 'AdminWeboTagOrder',
            'name' => 'Tag Highlight', // One name for all langs
            'visible' => true
        ],
      ];

      $this->displayName = $this->getTranslator()->trans('Tag Order Product List', array(), 'Modules.Webo_TagOrder.Admin');
      $this->description = $this->getTranslator()->trans('Tag Order Product List', array(), 'Modules.Webo_TagOrder.Admin');
  }

  public function install()
  {
    $tab = new Tab();
    $tab->class_name = 'AdminWeboTagOrder';
    $tab->module = 'webo_tagorder';
    $tab->icon = 'label_important';
    $tab->id_parent = (int) Tab::getIdFromClassName('AdminCatalog');
    foreach(Language::getLanguages(false) as $lang){
      $tab->name[(int) $lang['id_lang']] = 'Tag Highlight';
    }

    $tab->active = 1;
    if (!$tab->save()) {
        return false;
    }

    return parent::install();
  }

  public function uninstall()
  {
      $id_tab = (int)Tab::getIdFromClassName('AdminWeboTagOrder');
      $tab = new Tab($id_tab);

      if (Validate::isLoadedObject($tab)) {
          if (!$tab->delete()) {
              return false;
          }
      } else {
          return false;
      }
      return parent::uninstall();
  }
  
  /**
 * Builds the configuration form
 * @return string HTML code
 */
public function displayForm()
{
    // Init Fields form array
    $form = [
        'form' => [
            'legend' => [
                'title' => $this->l('Ustawienia'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Wybrany tag'),
                    'name' => 'WEBO_TAGORDER_TAG',
                    'size' => 20,
                    'required' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ],
        ],
    ];

    $helper = new HelperForm();

    // Module, token and currentIndex
    $helper->table = $this->table;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
    $helper->submit_action = 'submit' . $this->name;

    // Default language
    $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

    // Load current value into the form
    $helper->fields_value['WEBO_TAGORDER_TAG'] = Tools::getValue('WEBO_TAGORDER_TAG', Configuration::get('WEBO_TAGORDER_TAG'));

    return $helper->generateForm([$form]);
}

  /**
  * Loads the configuration form.
  * @return string Module Layout
  */
  public function getContent()
  {

    $output = '';

    // this part is executed only when the form is submitted
    if (Tools::isSubmit('submit' . $this->name)) {
        // retrieve the value set by the user
        $configValue = (string) Tools::getValue('WEBO_TAGORDER_TAG');

        // check that the value is valid
        if (empty($configValue) || !Validate::isGenericName($configValue)) {
            // invalid value, show an error
            $output = $this->displayError($this->l('Invalid Configuration value'));
        } else {
            // value is ok, update it and display a confirmation message
            Configuration::updateValue('WEBO_TAGORDER_TAG', $configValue);
            $output = $this->displayConfirmation($this->l('Settings updated'));
        }
    }

    // display any message, then the form
    return $output . $this->displayForm();

  }
}
