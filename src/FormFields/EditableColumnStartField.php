<?php

namespace Iliain\UserformColumns\FormFields;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\LabelField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\UserForms\Model\EditableFormField;

class EditableColumnStartField extends EditableFormField
{
    private static $has_one = [
        'End' => EditableColumnEndField::class,
    ];

    private static $owns = [
        'End',
    ];

    private static $cascade_deletes = [
        'End',
    ];

    private static $hidden = true;

    private static $literal = true;

    private static $table_name = 'EditableColumnStartField';

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName(['Title']);

            $fields->addFieldToTab('Root.Main', DropdownField::create('Title', 'Column width', $this->config()->get('css_classes') ?? [])
                ->setEmptyString('Select column width'));
        });

        return parent::getCMSFields();
    }

    public function getCMSTitle()
    {
        $title = $this->getFieldNumber()
            ?: $this->Title
                ?: 'column';

        return sprintf('Column %s', $title);
    }

    public function getInlineClassnameField($row, $fieldClasses)
    {
        return LabelField::create($row, $this->CMSTitle);
    }

    public function getInlineTitleField($column)
    {
        $cssClasses = $this->config()->get('css_classes');

        return DropdownField::create($column, false, $cssClasses ?? [])
            ->setEmptyString('Select column width');
    }

    public function showInReports()
    {
        return false;
    }

    public function getFormField()
    {
        $field = TextField::create($this->Name, $this->Title ?: false, $this->Default)
            ->setFieldHolderTemplate(EditableColumnStartField::class  . '_holder')
            ->setTemplate(EditableColumnStartField::class);

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
