<?php
/**
 * @Filename:  ViewBase.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2019-07-18 11:00:45
 * @Synopsis:  View基类，Twig
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-09 00:18:40
 */

namespace Ycommon;

use Yaf\Registry;
use Yaf\View_Interface;

class ViewBase implements View_Interface {

    protected $loader;
    protected $twig;
    protected $variables = array();

    public function __construct($templateDir, array $options = array()) {
        $this->loader = new \Twig\Loader\FilesystemLoader($templateDir);
        $this->twig = new \Twig\Environment($this->loader, $options);
    }

    public function __isset($name) {
        return isset($this->variables[$name]);
    }

    public function __set($name, $value) {
        $this->variables[$name] = $value;
    }

    public function __get($name) {
        return $this->variables[$name];
    }

    public function __unset($name) {
        unset($this->variables[$name]);
    }

    public function getTwig() {
        return $this->twig;
    }

    public function assign($name, $value = null) {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->variables[$k] = $v;
            }
        } else {
            $this->variables[$name] = $value;
        }
    }

    public function render($template, $variables = []) {
        if (is_array($variables)) {
            $this->variables = array_merge($this->variables, $variables);
        }
        return $this->twig->load($template)->render($this->variables);
    }

    public function display($template, $variables = []) {
        $html = $this->render($template, $variables);
        echo $html;
        exit(0);
    }

    public function fetch($template, $variables = []) {
        return $this->render($template, $variables);
    }

    public function getScriptPath($request = null) {
        $paths = $this->loader->getPaths();
        return reset($paths);
    }

    public function setScriptPath($templateDir) {
        $this->loader->setPaths($templateDir);
    }
}
