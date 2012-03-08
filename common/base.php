<?php
class Base {
    static $langs = array();
    private $lang;
    
    public function getClassPath() {
        return Loader::getClassPath(get_class($this));
    }
    
    public function getClassFile() {
        return Loader::getClassFile(get_class($this));
    }
    
    public function loadLang() {
        if (!$this->lang) {
            if (self::$langs[get_class($this)]) {
                $this->lang = self::$langs[get_class($this)];
            } else {
                if (is_file(BASE_PATH . $this->getClassPath() . '/' . $this->getClassFile() . '.' . DEFAULT_LANG . '.ini')) {
                    $this->lang = parse_ini_file(BASE_PATH . $this->getClassPath() . '/' . $this->getClassFile() . '.' . DEFAULT_LANG . '.ini');
                } else if (is_file(BASE_PATH . $this->getClassPath() . '/lang/' . $this->getClassFile() . '.' . DEFAULT_LANG . '.ini')) {
                    $this->lang = parse_ini_file(BASE_PATH . $this->getClassPath() . '/lang/' . $this->getClassFile() . '.' . DEFAULT_LANG . '.ini');
                } else {
                    die('not found lang file.');
                }
                self::$langs[get_class($this)] = $this->lang;
            }
        }
    }
    
    public function _() {
        $args = func_get_args();
        $this->loadLang();
        $key = array_shift($args);
        array_unshift($args, $this->lang[$key]);
        return call_user_func('sprintf', $args);
    }
}