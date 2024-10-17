<?php

namespace Iliain\UserformColumns\Extensions;

use Iliain\UserformColumns\FormFields\EditableColumnEndField;
use Iliain\UserformColumns\FormFields\EditableColumnStartField;
use Iliain\UserformColumns\FormFields\EditableRowEndField;
use Iliain\UserformColumns\FormFields\EditableRowStartField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\UserForms\Form\GridFieldAddClassesButton;

class UserFormColumnExtension extends DataExtension
{
    public function updateCMSFields(FieldList $fields)
    {
        $gridfield = $fields->dataFieldByName('Fields');
        $config = $gridfield->getConfig();

        $config->addComponent((new GridFieldAddClassesButton([EditableRowStartField::class, EditableRowEndField::class]))
            ->setButtonName('Add Row')
            ->setButtonClass('btn-secondary'));

        $config->addComponent((new GridFieldAddClassesButton([EditableColumnStartField::class, EditableColumnEndField::class]))
            ->setButtonName('Add Column')
            ->setButtonClass('btn-secondary'));
    }
}
