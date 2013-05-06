<?php
/**
 * @package   ImpressPages
 * @copyright Copyright (C) 2012 ImpressPages LTD.
 * @license   GNU/GPL, see ip_license.html
 */

class ValidatorTest extends \PhpUnit\CoreTestCase
{

    public function testEmail()
    {
        $emailValidator = new \Modules\developer\form\Validator\Email();

        $result = $emailValidator->validate(array('fieldKey' => 'not-an-email'), 'fieldKey');
        $this->assertEquals($result, 'Please enter a valid email address');

        $result = $emailValidator->validate(array('fieldKey' => 'correct@email.com'), 'fieldKey');
        $this->assertEquals($result, false);


        $result = $emailValidator->validate(array('fieldKey' => ''), 'fieldKey');
        $this->assertEquals($result, false);

    }



    public function testFile()
    {
        $fileField = new \Modules\developer\form\Field\File(array());
        $result = $fileField->validate(array('fieldKey' => array('file' => array('unexisting'))), 'fieldKey');
        $this->assertEquals('Session has ended. Please remove and re-upload files.', $result);
    }

}
