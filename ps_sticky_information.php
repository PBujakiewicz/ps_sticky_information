<?php   
if (!defined('_PS_VERSION_')) {
  exit;
}

class ps_sticky_information extends Module
{
  public function __construct()
  {
    $this->name = 'ps_sticky_information';
    $this->tab = 'front_office_features';
    $this->version = '1.0.1';
    $this->author = 'Pawel Bujakiewicz';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('Sticky Information');
    $this->description = $this->l('Modul adding fixed/sticky header/banner with text.');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
 
    if (!Configuration::get('ps_sticky_information')) {
      $this->warning = $this->l('No name provided');
    }
  }

  public function install()
  {
    if (Shop::isFeatureActive()) {
      Shop::setContext(Shop::CONTEXT_ALL);
    }
   
    if (!parent::install() ||
      !$this->registerHook('displayBanner') ||
      !$this->registerHook('header') ||
      !Configuration::updateValue('ps_sticky_information', 'DEV') ||
      !Configuration::updateValue('ps_sticky_information_color', 'white') ||
      !Configuration::updateValue('ps_sticky_information_background_color', 'black')
    ) {
      return false;
    }
   
    return true;
  }

  public function uninstall()
  {
    if (!parent::uninstall() ||
      !Configuration::deleteByName('ps_sticky_information') ||
      !Configuration::deleteByName('ps_sticky_information_color') ||
      !Configuration::deleteByName('ps_sticky_information_background_color')
    ) {
      return false;
    }
   
    return true;
  }

  public function getContent()
  {
      $output = null;
   
      if (Tools::isSubmit('submit'.$this->name))
      {
          $my_module_name = strval(Tools::getValue('ps_sticky_information'));
          if (!$my_module_name
            || empty($my_module_name)
            || !Validate::isGenericName($my_module_name))
              $output .= $this->displayError($this->l('Invalid Configuration value'));
          else
          {
              Configuration::updateValue('ps_sticky_information', $my_module_name);
              $output .= $this->displayConfirmation($this->l('Settings updated'));
          }

          $my_module_name = strval(Tools::getValue('ps_sticky_information_color'));
          if (!$my_module_name
            || empty($my_module_name)
            || !Validate::isGenericName($my_module_name))
              $output .= $this->displayError($this->l('Invalid Configuration value'));
          else
          {
              Configuration::updateValue('ps_sticky_information_color', $my_module_name);
              $output .= $this->displayConfirmation($this->l('Settings updated'));
          }

          $my_module_name = strval(Tools::getValue('ps_sticky_information_background_color'));
          if (!$my_module_name
            || empty($my_module_name)
            || !Validate::isGenericName($my_module_name))
              $output .= $this->displayError($this->l('Invalid Configuration value'));
          else
          {
              Configuration::updateValue('ps_sticky_information_background_color', $my_module_name);
              $output .= $this->displayConfirmation($this->l('Settings updated'));
          }
      }
      return $output.$this->displayForm();
  }

  public function displayForm()
  { 
    // Get default language
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
     
    // Init Fields form array
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('Settings'),
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('Text to display'),
                'name' => 'ps_sticky_information',
                'size' => 50,
                'required' => true
            ),
            array(
              'type' => 'text',
              'label' => $this->l('Text color'),
              'name' => 'ps_sticky_information_color',
              'size' => 50,
              'required' => true
            ),
            array(
              'type' => 'text',
              'label' => $this->l('Text background color'),
              'name' => 'ps_sticky_information_background_color',
              'size' => 50,
              'required' => true
          )
        ),
        'submit' => array(
            'title' => $this->l('Save'),
            'class' => 'btn btn-default pull-right'
        )
    );
     
    $helper = new HelperForm();
     
    // Module, token and currentIndex
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
     
    // Language
    $helper->default_form_language = $default_lang;
    $helper->allow_employee_form_lang = $default_lang;
     
    // Title and toolbar
    $helper->title = $this->displayName;
    $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit'.$this->name;
    $helper->toolbar_btn = array(
        'save' =>
        array(
            'desc' => $this->l('Save'),
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
            '&token='.Tools::getAdminTokenLite('AdminModules'),
        ),
        'back' => array(
            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Back to list')
        )
    );
     
    // Load current value
    $helper->fields_value['ps_sticky_information'] = Configuration::get('ps_sticky_information');
    $helper->fields_value['ps_sticky_information_color'] = Configuration::get('ps_sticky_information_color');
    $helper->fields_value['ps_sticky_information_background_color'] = Configuration::get('ps_sticky_information_background_color');
     
    return $helper->generateForm($fields_form);
  }
     
  public function hookDisplayHeader()
  {
    $this->context->smarty->assign(
      array(
          'ps_sticky_information_txt' => Configuration::get('ps_sticky_information'),
          'ps_sticky_information_color' => Configuration::get('ps_sticky_information_color'),
          'ps_sticky_information_background_color' => Configuration::get('ps_sticky_information_background_color'),
      )
    );

    return $this->display(__FILE__, 'ps_sticky_information.tpl');
  }  

}

