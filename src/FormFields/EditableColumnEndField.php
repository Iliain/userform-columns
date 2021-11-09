<?php

namespace Iliain\UserformColumns\FormFields;

use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\LabelField;
use SilverStripe\Forms\TextField;
use SilverStripe\UserForms\Model\EditableFormField;

class EditableColumnEndField extends EditableFormField
{
    private static $belongs_to = [
        'Column' => EditableColumnStartField::class
    ];

    /**
     * Disable selection of group class
     *
     * @config
     * @var bool
     */
    private static $hidden = true;

    /**
     * Non-data type
     *
     * @config
     * @var bool
     */
    private static $literal = true;

    private static $table_name = 'EditableColumnEndField';

    public function getCMSTitle()
    {
        $group = $this->Column();

        return sprintf('%s end', $group->CMSTitle);
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['MergeField', 'Default', 'Validation', 'DisplayRules']);
        return $fields;
    }

    public function getInlineClassnameField($column, $fieldClasses)
    {
        return LabelField::create($column, $this->CMSTitle);
    }

    public function getInlineTitleField($column)
    {
        return HiddenField::create($column);
    }

    public function getFormField()
    {
        $field = TextField::create($this->Name, $this->Title ?: false, $this->Default)
            ->setFieldHolderTemplate(EditableColumnEndField::class . '_holder')
            ->setTemplate(EditableColumnEndField::class);

        return $field;
    }

    public function showInReports()
    {
        return false;
    }

    public function onAfterWrite()
    {
        parent::onAfterWrite();

        // If this is not attached to a group, find the first group prior to this
        // with no end attached
        $group = $this->Column();
        if (!($group && $group->exists()) && $this->ParentID) {
            $group = EditableColumnStartField::get()
                ->filter([
                    'ParentID' => $this->ParentID,
                    'Sort:LessThanOrEqual' => $this->Sort
                ])
                ->where('"EditableColumnStartField"."EndID" IS NULL OR "EditableColumnStartField"."EndID" = 0')
                ->sort('"Sort" DESC')
                ->first();

            // When a group is found, attach it to this end
            if ($group) {
                $group->EndID = $this->ID;
                $group->write();
            }
        }
    }
}
