<?php
class InstallAdminController extends Controller {
    public function exec() {
        if (!INSTALL_MODE) {
            die('access denied.');
        }
    }
}