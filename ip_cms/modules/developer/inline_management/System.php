<?php
/**
 * @package ImpressPages
 * @copyright   Copyright (C) 2011 ImpressPages LTD.
 * @license see ip_license.html
 */
namespace Modules\developer\inline_management;


class System
{
    function init()
    {
        global $site;

        $site->addCss(BASE_URL.MODULE_DIR.'developer/inline_management/public/inline_management.css');

        $site->addJavascript(BASE_URL.LIBRARY_DIR.'js/jquery/jquery.js');

        $site->addJavascript(BASE_URL.MODULE_DIR.'developer/inline_management/public/inlineManagement.js');
        $site->addJavascript(BASE_URL.MODULE_DIR.'developer/inline_management/public/inlineManagementLogo.js');

        $site->addJavascript(BASE_URL.LIBRARY_DIR.'js/plupload/plupload.full.js');
        $site->addJavascript(BASE_URL.LIBRARY_DIR.'js/plupload/plupload.browserplus.js');
        $site->addJavascript(BASE_URL.LIBRARY_DIR.'js/plupload/plupload.gears.js');

        $site->addJavascript(BASE_URL.MODULE_DIR.'developer/upload/jquery.ip.uploadImage.js');
        $site->addJavascript(BASE_URL.MODULE_DIR.'developer/upload/jquery.ip.uploadFile.js');

        $site->addJavascript(BASE_URL.MODULE_DIR.'developer/inline_management/public/jquery.fontselector.js');

        $site->addCSS(BASE_URL.LIBRARY_DIR.'js/jquery-colorpicker/colorpicker.css');
        $site->addJavascript(BASE_URL.LIBRARY_DIR.'js/jquery-colorpicker/colorpicker.js');

    }
}

