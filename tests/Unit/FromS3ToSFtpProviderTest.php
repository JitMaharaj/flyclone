<?php

declare(strict_types=1);

namespace CloudAtlas\Flyclone\Test\Unit;

use CloudAtlas\Flyclone\Providers\S3Provider;
use CloudAtlas\Flyclone\Providers\SFtpProvider;
use CloudAtlas\Flyclone\Rclone;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class FromS3ToSFtpProviderTest extends AbstractTwoProvidersTest
{
  public function setUp (): void
  {
	 $left_disk_name = 's3_disk';
	 $this->setLeftProviderName($left_disk_name);
	 $this->setLeftWorkingDirectory('flyclone/flyclone');  // bucket/folder

	 self::assertEquals($left_disk_name, $this->getLeftProviderName());

	 $right_disk_name = 'sftp_disk';
	 $this->setRightProviderName($right_disk_name);
	 $working_directory = $_ENV[ 'SFTP_USER' ] === 'root' ? "/root" : "/home/{$_ENV['SFTP_USER']}";
	 $this->setRightWorkingDirectory($working_directory . '/' . $this->random_string());
	 self::assertEquals($right_disk_name, $this->getRightProviderName());
  }

  /**
	* @test
	* @throws ExpectationFailedException
	* @throws Exception
	* @throws InvalidArgumentException
	*/
  final public function instantiate_left_provider (): S3Provider
  {
	 $left_side = new S3Provider($this->getRightProviderName(), [
		  'REGION'            => $_ENV[ 'S3_REGION' ],
		  'ENDPOINT'          => $_ENV[ 'S3_ENDPOINT' ],
		  'ACCESS_KEY_ID'     => $_ENV[ 'S3_ACCESS_KEY_ID' ],
		  'SECRET_ACCESS_KEY' => $_ENV[ 'S3_SECRET_ACCESS_KEY' ],
	 ]);

	 self::assertInstanceOf(get_class($left_side), $left_side);

	 return $left_side;
  }

  /**
	* @test
	* @throws ExpectationFailedException
	* @throws Exception
	* @throws InvalidArgumentException
	*/
  final public function instantiate_right_provider (): SFtpProvider
  {
	 $right_side = new SFtpProvider($this->getLeftProviderName(), [
		  'HOST' => $_ENV[ 'SFTP_HOST' ],
		  'USER' => $_ENV[ 'SFTP_USER' ],
		  'PASS' => Rclone::obscure($_ENV[ 'SFTP_PASS' ]),
		  'PORT' => $_ENV[ 'SFTP_PORT' ],
	 ]);

	 self::assertInstanceOf(get_class($right_side), $right_side);

	 return $right_side;
  }
}
