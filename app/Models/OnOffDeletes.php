<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * @author Skynin <sohay_ua@yahoo.com>
 */
trait OnOffDeletes
{
	use SoftDeletes {
		SoftDeletes::bootSoftDeletes as _bootSoftDeletes;
		SoftDeletes::runSoftDelete as _runSoftDelete;
		SoftDeletes::restore as _restore;
		SoftDeletes::getDeletedAtColumn as _getDeletedAtColumn;
		SoftDeletes::trashed as _trashed;
		SoftDeletes::getQualifiedDeletedAtColumn as _getQualifiedDeletedAtColumn;
	}

    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new OnOffDeletingScope);
    }

    /**
     * Get the name of the "deleted at" column when $deleted_at_column isn't exists or true
     *
     * @return string
     */
    public function getDeletedAtColumn ()
	{
		if ( isset($this->deleted_at_column) )
			return $this->deleted_at_column ? $this->_getDeletedAtColumn() : null;

		return $this->_getDeletedAtColumn();
	}

	/**
     * Perform the actual delete query on this model instance.
     *
     * @return void
     */
    protected function runSoftDelete()
    {
       $query = $this->newQueryWithoutScopes()->where($this->getKeyName(), $this->getKey());

		$time = $this->freshTimestamp();

		$deletedAtColumn = $this->getDeletedAtColumn();
		if ( !empty($deletedAtColumn) ) {
			$columns[$deletedAtColumn] = $this->fromDateTime($time);

			$this->{$deletedAtColumn} = $time;
		}

		$this->e_onoff = OnOffTools::STATUS_DELETED;
		$columns['e_onoff'] = $this->e_onoff;

		if ( $this->timestamps && !is_null($this->getUpdatedAtColumn()) ) {
			$this->{$this->getUpdatedAtColumn()} = $time;

			$columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
		}

		$query->update($columns);
	}

    /**
     * Restore a soft-deleted model instance.
     *
     * @return bool|null
     */
	public function restore() {

        // If the restoring event does not return false, we will proceed with this
        // restore operation. Otherwise, we bail out so the developer will stop
        // the restore totally. We will clear the deleted timestamp and save.
        if ($this->fireModelEvent('restoring') === false) {
            return false;
        }

		if ($this->e_onoff < 0) $this->e_onoff = OnOffTools::STATUS_DRAFT;

		$deletedAtColumn = $this->getDeletedAtColumn();

        if (!empty($deletedAtColumn)) $this->{$this->getDeletedAtColumn()} = null;

        // Once we have saved the model, we will fire the "restored" event so this
        // developer will do anything they need to after a restore operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('restored', false);

        return $result;
	}

    public function getQualifiedDeletedAtColumn()
    {
		$deletedAtColumn = $this->getDeletedAtColumn();
		if (!empty($deletedAtColumn)) return $this->qualifyColumn($deletedAtColumn);

		// TODO HasManyThrough $query->whereNull($this->throughParent->getQualifiedDeletedAtColumn());
		return null;
    }

    /**
     * Determine if the model instance has been soft-deleted.
     *
     * @return bool
     */
    public function trashed()
    {
        return $this->e_onoff < 0;
    }
}
