<?php

namespace Midori
{
	class VersionSpec extends PHPUnit\Spec
	{
			
		/**
		 *@test
		 */
		public function itShouldHaveTheRightVersion()
		{
			$version = Version::string();
			$this->expectsThat($version)->shouldBe("0.1.0");		
		}
			
	}
}	