<?php   
if (!defined('_PS_VERSION_')) {
  exit;
}

class ps-sticky-information extends Module
{
  public function __construct()
  {
    $this->name = 'ps-sticky-information';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'Pawel Bujakiewicz';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('Sticky Information');
    $this->description = $this->l('Modul adding fixed/sticky header/banner with text.');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
 
    if (!Configuration::get('ps-sticky-information')) {
      $this->warning = $this->l('No name provided');
    }
  }

  public function install()
  {
    if (Shop::isFeatureActive()) {
      Shop::setContext(Shop::CONTEXT_ALL);
    }
   
    if (!parent::install() ||
      !$this->registerHook('leftColumn') ||
      !$this->registerHook('header') ||
      !Configuration::updateValue('ps-sticky-information', 'DEV')
    ) {
      return false;
    }
   
    return true;
  }

  public function uninstall()
  {
    if (!parent::uninstall() ||
      !Configuration::deleteByName('ps-sticky-information')
    ) {
      return false;
    }
   
    return true;
  }

}

