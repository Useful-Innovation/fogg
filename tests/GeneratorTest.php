<?php

use GoBrave\Fogg\Config;
use GoBrave\Fogg\Util\Generator;
use GoBrave\Util\Renderer;
use GoBrave\Util\CaseConverter;
use GoBrave\Util\SingPlur;

class GeneratorTest extends PHPUnit_Framework_TestCase
{
  const ROOT = __DIR__ . '/root';

  public static function setUpBeforeClass() {
    system('rm -r ' . self::ROOT . '/*');
  }

  public function setUp() {
    global $config;
    $this->g = new Generator(
      $config,
      new Renderer(BASE_PATH . '/src/Util/templates', 'tpl'),
      new CaseConverter(),
      new SingPlur()
    );
  }



  public function testGenerateModel() {
    $this->g->model('Language');

    $this->assertTrue(file_exists(self::ROOT . '/Models/Language.php'));
  }


  /**
   * @expectedException GoBrave\Fogg\Util\FileAlreadyExistsException
   */
  public function testGenerateAlreadyExistingModel() {
    $this->g->model('Language');
    $this->g->model('Language');
  }

  public function testGenerateAlreadyExistingModelAndOverwrite() {
    $this->g->model('Language', true);

    $this->assertTrue(file_exists(self::ROOT . '/Models/Language.php'));
  }







  //
  //      Controller
  //

  public function testGenerateController() {
   $this->g->controller('LanguageController');

   $this->assertTrue(file_exists(self::ROOT . '/Controllers/LanguageController.php'));
  }


  /**
  * @expectedException GoBrave\Fogg\Util\FileAlreadyExistsException
  */
  public function testGenerateAlreadyExistingController() {
   $this->g->controller('LanguageController');
   $this->g->controller('LanguageController');
  }

  public function testGenerateAlreadyExistingControllerAndOverwrite() {
   $this->g->controller('LanguageController', true);

   $this->assertTrue(file_exists(self::ROOT . '/Controllers/LanguageController.php'));
  }





  //
  //      AdminController
  //

  public function testGenerateAdminController() {
   $this->g->adminController('LanguageController');

   $this->assertTrue(file_exists(self::ROOT . '/Controllers/Admin/LanguageController.php'));
  }


  /**
  * @expectedException GoBrave\Fogg\Util\FileAlreadyExistsException
  */
  public function testGenerateAlreadyExistingCAdminontroller() {
   $this->g->adminController('LanguageController');
   $this->g->adminController('LanguageController');
  }

  public function testGenerateAlreadyExistingAdminControllerAndOverwrite() {
   $this->g->adminController('LanguageController', true);

   $this->assertTrue(file_exists(self::ROOT . '/Controllers/Admin/LanguageController.php'));
  }







  //
  //        Views
  //

  public function testGenerateAdminViews() {
    $this->g->adminViews('product');

    foreach(['index', 'create', 'edit', '_form'] as $template) {
      $this->assertTrue(file_exists(self::ROOT . '/Views/admin/products/' . $template . '.html.php'));
    }
  }
}
