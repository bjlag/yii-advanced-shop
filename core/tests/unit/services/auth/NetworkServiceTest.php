<?php namespace core\tests\services\auth;

use common\fixtures\UserFixture;
use common\fixtures\UserNetworkFixture;
use core\entities\User\User;
use core\repositories\UserRepository;
use core\services\auth\NetworkService;

class NetworkServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \core\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $this->tester->haveFixtures([
            'user_network' => [
                'class' => UserNetworkFixture::class,
                'dataFile' => codecept_data_dir() . 'user_network.php'
            ],
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testAuthExistNetwork()
    {
        $service = new NetworkService(new UserRepository());
        /** @var User $user */
        $user = $service->auth('vk', '123456');

        expect($user->username)->equals('bayer.hudson');
    }

    public function testNewNetwork()
    {
        $repository = $this->getMockBuilder(UserRepository::class)
            ->setMethods(['save'])
            ->getMock();

        $repository->expects($this->once())
            ->method('save')
            ->willReturn(true);

        /** @var UserRepository $repository */
        $service = new NetworkService($repository);
        /** @var User $user */
        $user = $service->auth(
            $network = 'new_network',
            $identity = 'new_identity'
        );

        $networkModel = $user->networks[0];

        expect('Должен добавить новый пользователь с социальной сетью', $networkModel)->notEmpty();
        expect('Должно установиться название социальной сети', $networkModel->attributes['network'])->equals($network);
        expect('Должен установиться идентификатор социальной сети', $networkModel->attributes['identity'])->equals($identity);
    }

    public function testAttachNetwork()
    {
        $repository = $this->getMockBuilder(UserRepository::class)
            ->setMethods(['save'])
            ->getMock();

        $repository->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $user = User::findByUsername('bayer.hudson');

        /** @var UserRepository $repository */
        $service = new NetworkService($repository);
        $service->attach(
            $user,
            $network = 'new_network',
            $identity = 'new_identity'
        );

        $networkModel = $user->networks[1];

        expect('Должен добавиться новый пользователь с социальной сетью', $networkModel)->notEmpty();
        expect('Должно установиться название социальной сети', $networkModel->attributes['network'])->equals($network);
        expect('Должен установиться идентификатор социальной сети', $networkModel->attributes['identity'])->equals($identity);
    }

    public function testAttachExistNetwork()
    {
        $repository = $this->getMockBuilder(UserRepository::class)
            ->setMethods(['save'])
            ->getMock();

        $repository->method('save')->willReturn(true);

        $this->expectException(\DomainException::class);

        $user = User::findByUsername('bayer.hudson');

        /** @var UserRepository $repository */
        $service = new NetworkService($repository);
        $service->attach($user, 'vk', '123456');
    }
}