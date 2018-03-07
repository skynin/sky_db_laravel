<?php

namespace Tests\Unit;

use App\Models\{EOnOffWithDate, OnOffTools};

class EonoffWithDateTest extends EonoffBaseClass
{
	protected function setUp()
	{
		$this->eonoffClass = EOnOffWithDate::class;

		parent::setUp();
	}

    public function testMinusOne()
	{
		$idRec = $this->deleteTestRec();

		$allCount = $this->eonoffClass::where('id', '>', 0)->count();
		$this->assertEquals($allCount, 2);

		$delRec = $this->eonoffClass::withTrashed()->find($idRec);
		$this->assertEquals($delRec->e_name, $this->eNameTestRec);
		$this->assertEquals($delRec->e_onoff, OnOffTools::STATUS_DELETED);

		$delRec->restore();
		$this->assertEquals($delRec->e_onoff, OnOffTools::STATUS_DRAFT);
	}
}
