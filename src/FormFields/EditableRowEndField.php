<?php

namespace Iliain\UserformColumns\FormFields;

use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\LabelField;
use SilverStripe\Forms\TextField;
use SilverStripe\UserForms\Model\EditableFormField;

class EditableRowEndField extends EditableFormField
{
    private static $belongs_to = [
        'Row' => EditableRowStartField::class
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

    private static $table_name = 'EditableRowEndField';

    public function getCMSTitle()
    {
        $group = $this->Row();

        return sprintf('%s end', $group->CMSTitle);
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['MergeField', 'Default', 'Validation', 'DisplayRules']);
        return $fields;
    }

    public function getInlineClassnameField($row, $fieldClasses)
    {
        return LabelField::create($row, $this->CMSTitle);
    }

    public function getInlineTitleField($row)
    {
        return HiddenField::create($row);
    }

    public function getFormField()
    {
        $field = TextField::create($this->Name, $this->Title ?: false, $this->Default)
            ->setFieldHolderTemplate(EditableRowEndField::class . '_holder')
            ->setTemplate(EditableRowEndField::class);

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
        $group = $this->Row();
        if (!($group && $group->exists()) && $this->ParentID) {
            $group = EditableRowStartField::get()
                ->filter([
                    'ParentID' => $this->ParentID,
                    'Sort:LessThanOrEqual' => $this->Sort
                ])
                ->where('"EditableRowStartField"."EndID" IS NULL OR "EditableRowStartField"."EndID" = 0')
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
