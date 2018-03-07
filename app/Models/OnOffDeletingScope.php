<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{SoftDeletingScope, Builder};
use Illuminate\Database\Eloquent\Model;

/**
 * Description of OnOffDeletingScope
 *
 * @author Skynin <sohay_ua@yahoo.com>
 */
class OnOffDeletingScope extends SoftDeletingScope
{

	public function apply ( Builder $builder, Model $model )
	{
		$builder->where($model->qualifyColumn('e_onoff'), '>', 0);
	}

    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {
			$result = [];
			$result['e_onoff'] = OnOffTools::STATUS_DELETED;

           $column = $this->getDeletedAtColumn($builder);

			if (!empty($column))
				$result[$column] = $builder->getModel()->freshTimestampString();

            return $builder->update($result);
        });
    }

    protected function addRestore(Builder $builder)
    {
        $builder->macro('restore', function (Builder $builder) {
			$result = [];
			$result['e_onoff'] = OnOffTools::STATUS_DRAFT;

           $builder->withTrashed();

			$column = $builder->getModel()->getDeletedAtColumn();
			if (!empty($column))
				$result[$column] = null;

            return $builder->update($result);
        });
    }

    protected function addWithoutTrashed(Builder $builder)
    {
        $builder->macro('withoutTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where(
                $model->qualifyColumn('e_onoff'), '>', 0
            );

            return $builder;
        });
    }

    protected function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where(
                $model->qualifyColumn('e_onoff'), '<', 0
            );

            return $builder;
        });
    }
}
