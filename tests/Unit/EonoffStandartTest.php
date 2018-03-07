<?php

namespace Tests\Unit;

use App\Models\EOnOffStandart;

class EonoffStandartTest extends EonoffBaseClass
{
	protected function setUp()
	{
		$this->eonoffClass = EOnOffStandart::class;

		parent::setUp();
	}
}
