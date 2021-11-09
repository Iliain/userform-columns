<?php

namespace Iliain\UserformColumns\FormFields;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\LabelField;
use SilverStripe\Forms\TextField;
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
        $fields = parent::getCMSFields();
        $fields->removeByName(['MergeField', 'Default', 'Validation', 'DisplayRules']);
        return $fields;
    }

    public function getCMSTitle()
    {
        $title = $this->getFieldNumber()
            ?: $this->Title
                ?: 'column';

        return sprintf('Column %s', $title);
    }

    public function getInlineClassnameField($column, $fieldClasses)
    {
        return LabelField::create($column, $this->CMSTitle);
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
        // set the right title on this field
        if ($this->RightTitle) {
            // Since this field expects raw html, safely escape the user data prior
            $field->setRightTitle(Convert::raw2xml($this->RightTitle));
        }

        // if this field has an extra class
        if ($this->ExtraClass) {
            $field->addExtraClass($this->ExtraClass);
        }
    }

    public function getFieldNumber()
    {
        // Check if exists
        if (!$this->exists()) {
            return null;
        }
        // Check parent
        $form = $this->Parent();
        if (!$form || !$form->exists() || !($fields = $form->Fields())) {
            return null;
        }

        $prior = 0; // Number of prior group at this level
        $stack = []; // Current stack of nested groups, where the top level = the page
        foreach ($fields->map('ID', 'ClassName') as $id => $className) {
            if ($className === EditableColumnStartField::class) {
                $stack[] = $prior + 1;
                $prior = 0;
            } elseif ($className === EditableColumnEndField::class) {
                $prior = array_pop($stack);
            }
            if ($id == $this->ID) {
                return implode('.', $stack);
            }
        }
        return null;
    }
}
