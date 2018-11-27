<?php namespace core\tests\entities\User;

use core\entities\User\Network;
use core\entities\User\User;

class UserNetworkTest extends \Codeception\Test\Unit
{
    private $network;
    private $identity;
    /** @var User */
    private $user;

    protected function _before()
    {
        $this->user = User::networkSignup(
            $this->network = 'vk',
            $this->identity = '123456'
        );
    }

    public function testUserCreated()
    {
        expect('Должна установиться дата создания', $this->user->created_at)->notEmpty();
        expect('Должна установиться дата обновления', $this->user->updated_at)->notEmpty();
        expect('Дата создания должна быть ровна дате обновления', $this->user->created_at)->equals($this->user->updated_at);

        expect('auth_key не должен быть пустым', $this->user->auth_key)->notEmpty();

        expect('Статус пользователя должен быть ACTIVE', $this->user->isActive())->true();
    }

    public function testNetworkAdded()
    {
        /** @var Network $networkModel */
        $networkModel = $this->user->networks[0];

        expect('У пользователя должна добавиться социальная сеть', $networkModel)->notEmpty();
        expect('Должно совпадать имя социальной сети', $networkModel->attributes['network'])->equals($this->network);
        expect('Должен совпадать идентификатор социальной сети', $networkModel->attributes['identity'])->equals($this->identity);
    }

    public function testAttachedNetworkExistUser()
    {
        /** @var User $user */
        $user = $this->user->attachNetwork(
            $network = 'facebook',
            $identity = '654321'
        );

        /** @var Network $networkModel */
        $networkModel = $user->networks[1];

        expect('Должна добавиться новая социальная сеть', $user->networks)->count(2);
        expect('Должно совпадать имя социальной сети', $networkModel->attributes['network'])->equals($network);
        expect('Должен совпадать идентификатор социальной сети', $networkModel->attributes['identity'])->equals($identity);
    }

    public function testAddExistNetwork()
    {
        $this->expectException(\DomainException::class);

        $this->user->attachNetwork(
            $this->network,
            $this->identity
        );
    }
}