<?php

namespace tests\unit;

use OAuth2\Storage\ScopeInterface;
use sweelix\oauth2\server\exceptions\DuplicateKeyException;
use sweelix\oauth2\server\models\Scope;
use Yii;

/**
 * ManagerTestCase
 */
class OauthScopeStorageTestCase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication([
        ]);
        $this->cleanDatabase();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testInsert()
    {
        $scope = Yii::createObject('sweelix\oauth2\server\models\Scope');
        /* @var Scope $scope */
        $this->assertInstanceOf(Scope::class, $scope);
        $scope->id = 'basic';
        $scope->isDefault = false;
        $scope->definition = 'Basic Scope';
        $this->assertTrue($scope->save());

        $insertedScope = Scope::findOne('basic');
        $this->assertInstanceOf(Scope::class, $insertedScope);
        $this->assertEquals($scope->id, $insertedScope->id);
        $this->assertEquals($scope->isDefault, $insertedScope->isDefault);
        $this->assertEquals($scope->definition, $insertedScope->definition);

        $availableScopes = Scope::findAvailableScopeIds();
        $defaultScopes = Scope::findDefaultScopeIds();

        $this->assertEquals(1, count($availableScopes));
        $this->assertContains('basic', $availableScopes);

        $this->assertEquals(0, count($defaultScopes));


        $scope = Yii::createObject('sweelix\oauth2\server\models\Scope');
        /* @var Scope $scope */
        $this->assertInstanceOf(Scope::class, $scope);
        $scope->id = 'extended';
        $scope->definition = 'Extended Scope';
        $this->assertFalse($scope->save());


        $scope = Yii::createObject('sweelix\oauth2\server\models\Scope');
        /* @var Scope $scope */
        $this->assertInstanceOf(Scope::class, $scope);
        $scope->id = 'basic';
        $scope->isDefault = false;
        $scope->definition = 'Basic Scope';
        $this->expectException(DuplicateKeyException::class);
        $scope->save();
    }

    public function testUpdate()
    {
        $basicScope = Yii::createObject('sweelix\oauth2\server\models\Scope');
        /* @var Scope $basicScope */
        $this->assertInstanceOf(Scope::class, $basicScope);
        $basicScope->id = 'basic';
        $basicScope->isDefault = true;
        $basicScope->definition = 'Basic Scope';
        $this->assertTrue($basicScope->save());

        $emailScope = Yii::createObject('sweelix\oauth2\server\models\Scope');
        /* @var Scope $emailScope */
        $this->assertInstanceOf(Scope::class, $emailScope);
        $emailScope->id = 'email';
        $emailScope->isDefault = false;
        $emailScope->definition = 'Email Scope';
        $this->assertTrue($emailScope->save());

        $availableScopes = Scope::findAvailableScopeIds();
        $defaultScopes = Scope::findDefaultScopeIds();

        $this->assertEquals(2, count($availableScopes));
        $this->assertContains('basic', $availableScopes);
        $this->assertContains('email', $availableScopes);

        $this->assertEquals(1, count($defaultScopes));
        $this->assertContains('basic', $defaultScopes);

        $emailScope = Scope::findOne('email');
        $emailScope->isDefault = true;
        $this->assertTrue($emailScope->save());

        $availableScopes = Scope::findAvailableScopeIds();
        $defaultScopes = Scope::findDefaultScopeIds();

        $this->assertEquals(2, count($availableScopes));
        $this->assertContains('basic', $availableScopes);
        $this->assertContains('email', $availableScopes);

        $this->assertEquals(2, count($defaultScopes));
        $this->assertContains('basic', $defaultScopes);
        $this->assertContains('email', $defaultScopes);

        $newScope = Yii::createObject('sweelix\oauth2\server\models\Scope');
        /* @var Scope $newScope */
        $newScope->id = 'newScope';
        $newScope->isDefault = false;
        $newScope->definition = 'New scope';
        $this->assertTrue($newScope->save());

        $alteredScope = Scope::findOne('newScope');
        $alteredScope->id = 'alteredScope';
        $alteredScope->definition = 'Altered scop';
        $this->assertTrue($alteredScope->save());

        $alteredScope = Scope::findOne('alteredScope');
        $alteredScope->definition = null;
        $this->assertTrue($alteredScope->save());

        $emailScope = Scope::findOne('email');
        $emailScope->id = 'basic';
        $this->expectException(DuplicateKeyException::class);
        $emailScope->save();
    }

    public function testDelete()
    {
        $basicScope = Yii::createObject('sweelix\oauth2\server\models\Scope');
        /* @var Scope $basicScope */
        $this->assertInstanceOf(Scope::class, $basicScope);
        $basicScope->id = 'basic';
        $basicScope->isDefault = true;
        $basicScope->definition = 'Basic Scope';
        $this->assertTrue($basicScope->save());

        $emailScope = Yii::createObject('sweelix\oauth2\server\models\Scope');
        /* @var Scope $emailScope */
        $this->assertInstanceOf(Scope::class, $emailScope);
        $emailScope->id = 'email';
        $emailScope->isDefault = false;
        $emailScope->definition = 'Email Scope';
        $this->assertTrue($emailScope->save());

        $availableScopes = Scope::findAvailableScopeIds();
        $defaultScopes = Scope::findDefaultScopeIds();

        $this->assertEquals(2, count($availableScopes));
        $this->assertContains('basic', $availableScopes);
        $this->assertContains('email', $availableScopes);

        $this->assertEquals(1, count($defaultScopes));
        $this->assertContains('basic', $defaultScopes);

        $emailScope->delete();

        $availableScopes = Scope::findAvailableScopeIds();
        $defaultScopes = Scope::findDefaultScopeIds();

        $this->assertEquals(1, count($availableScopes));
        $this->assertContains('basic', $availableScopes);

        $this->assertEquals(1, count($defaultScopes));
        $this->assertContains('basic', $defaultScopes);
    }

    public function testStorage()
    {
        $storage = Yii::createObject('sweelix\oauth2\server\storage\OauthStorage');
        /* @var ScopeInterface $storage */
        $this->assertInstanceOf(ScopeInterface::class, $storage);
        $defaultScope = $storage->getDefaultScope();
        $this->assertNull($defaultScope);

        $basicScope = Yii::createObject('sweelix\oauth2\server\models\Scope');
        /* @var Scope $basicScope */
        $this->assertInstanceOf(Scope::class, $basicScope);
        $basicScope->id = 'basic';
        $basicScope->isDefault = true;
        $basicScope->definition = 'Basic Scope';
        $this->assertTrue($basicScope->save());

        $defaultScope = $storage->getDefaultScope();
        $this->assertEquals('basic', $defaultScope);

        $this->assertFalse($storage->scopeExists('fail'));
        $this->assertTrue($storage->scopeExists('basic'));

        $emailScope = Yii::createObject('sweelix\oauth2\server\models\Scope');
        /* @var Scope $basicScope */
        $this->assertInstanceOf(Scope::class, $emailScope);
        $emailScope->id = 'email';
        $emailScope->isDefault = true;
        $emailScope->definition = 'Email Scope';
        $this->assertTrue($emailScope->save());
        $this->assertTrue($storage->scopeExists('basic email'));
        $this->assertTrue($storage->scopeExists('email basic'));
    }
}
