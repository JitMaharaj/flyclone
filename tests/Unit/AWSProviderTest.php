<?php declare(strict_types=1);

namespace CloudAtlas\Flyclone\Test\Unit;

use CloudAtlas\Flyclone\Providers\S3Provider;

class AWSProviderTest extends AbstractProviderTest
{

   public function setUp()
   : void
   {
      $left_disk_name = 'aws_disk';
      $this->setLeftProviderName($left_disk_name);
      $this->working_directory = 'flyclone/flyclone'; // bucket/folder


      self::assertEquals($left_disk_name, $this->getLeftProviderName());
   }

   /**
    * @test
    */
   final public function instantiate_left_provider()
   : S3Provider
   {
      $left_side = new S3Provider($this->getLeftProviderName(), [
          'REGION'            => $_ENV[ 'AWS_REGION' ],
          'ENDPOINT'          => $_ENV[ 'AWS_ENDPOINT' ],
          'ACCESS_KEY_ID'     => $_ENV[ 'AWS_ACCESS_KEY_ID' ],
          'SECRET_ACCESS_KEY' => $_ENV[ 'AWS_SECRET_ACCESS_KEY' ],
      ]);

      self::assertInstanceOf(get_class($left_side), $left_side);

      return $left_side;
   }

}
