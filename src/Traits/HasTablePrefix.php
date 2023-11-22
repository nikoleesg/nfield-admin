<?php

namespace Nikoleesg\NfieldAdmin\Traits;

trait HasTablePrefix
{
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        //        return $this->getPrefix() . parent::getTable();
        return $this->getPrefix().'interviewers';
    }

    /**
     * Get the prefix associated with the model.
     *
     * @return string
     */
    public function getPrefix()
    {
        return config('nfield-admin.table_prefix') ?? $this->prefix ?? '';
    }
}
