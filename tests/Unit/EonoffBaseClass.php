<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Description of EonoffBaseClass
 *
 * @author Skynin <sohay_ua@yahoo.com>
 */
class EonoffBaseClass extends TestCase
{
	use RefreshDatabase;

	protected $eonoffClass = '', $eNameTestRec = 'second';

	protected function setUp()
	{
		parent::setUp();

		foreach ( ['first', 'second', 'third'] as $value ) {
			$eloqRec = new $this->eonoffClass(['e_name' => $value]);
			$eloqRec->save();
		}
	}

	protected function deleteTestRec()
	{
		$delRec = $this->eonoffClass::where('e_name', $this->eNameTestRec)->first();
		$idRec = $delRec->id;
		$delRec->delete();

		return $idRec;
	}

	/**
     * @return void
     */
    public function testSoftDeletes()
    {
		$delRec = $this->eonoffClass::where('e_name', $this->eNameTestRec)->first();
		$idRec = $delRec->id;
		$delRec->delete();

		foreach ( $this->eonoffClass::all() as $eachRec ) {
			$this->assertFalse($eachRec->e_name == $this->eNameTestRec );
		}

		$delRec = $this->eonoffClass::find($idRec);
		$this->assertEmpty($delRec);

		$delRec = $this->eonoffClass::withTrashed()->find($idRec);
		$this->assertEquals($delRec->e_name, $this->eNameTestRec);
    }
}
