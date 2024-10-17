<?php

namespace Iliain\UserformColumns\FormFields;

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\LabelField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\UserForms\Model\EditableFormField;

class EditableRowStartField extends EditableFormField
{
    private static $has_one = [
        'End' => EditableRowEndField::class,
    ];

    private static $owns = [
        'End',
    ];

    private static $cascade_deletes = [
        'End',
    ];

    private static $hidden = true;

    private static $literal = true;

    private static $table_name = 'EditableRowStartField';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['MergeField', 'Default', 'Validation', 'DisplayRules', 'RightTitle']);

        $titleField = $fields->fieldByName('Root.Main.Title');
        $titleField->setTitle('Row class');

        $nameField = $fields->fieldByName('Root.Main.Name');
        $fields->removeByName('Name');
        $fields->insertAfter('ExtraClass', $nameField);

        return $fields;
    }

    public function getCMSTitle()
    {
        $title = $this->getFieldNumber()
            ?: $this->Title
                ?: 'row';

        return sprintf('Row %s', $title);
    }

    public function getInlineClassnameField($row, $fieldClasses)
    {
        return LabelField::create($row, $this->CMSTitle);
    }

    public function getInlineTitleField($row)
    {
        return HiddenField::create($row);
    }

    public function showInReports()
    {
        return false;
    }

    public function getFormField()
    {
        $field = TextField::create($this->Name, $this->Title ?: false, $this->Default)
            ->setFieldHolderTemplate(EditableRowStartField::class  . '_holder')
            ->setTemplate(EditableRowStartField::class)
            ->addExtraClass($this->config()->get('default_classes'));

        return $field;
    }

    protected function updateFormField($field)
    {
        // if this field has an extra class
        if ($this->ExtraClass) {
            $field->addExtraClass($this->ExtraClass);
        }
    }
}
