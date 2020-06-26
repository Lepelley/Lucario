<?php

namespace Lucario\Tests\Permission;

use Lucario\Permission\Permission;
use Lucario\Permission\PermissionDebuggerInterface;
use Lucario\Tests\Permission\Helpers\AlwaysNoVoter;
use Lucario\Tests\Permission\Helpers\AlwaysYesVoter;
use Lucario\Tests\Permission\Helpers\AuthorVoter;
use Lucario\Tests\Permission\Helpers\ConsoleDebugger;
use Lucario\Tests\Permission\Helpers\SpecificVoter;
use Lucario\Tests\Permission\Helpers\TestPost;
use Lucario\Tests\Permission\Helpers\TestUser;
use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{
    public function testEmptyVoters(): void
    {
        $permission = new Permission();
        $user = new TestUser();
        $this->assertFalse($permission->can($user, 'test'));
    }

    public function testWithTrueVoter(): void
    {
        $permission = (new Permission())
            ->addVoter(new AlwaysYesVoter())
        ;
        $user = new TestUser();
        $this->assertTrue($permission->can($user, 'test'));
    }

    public function testWithOneVoterVotesTrue(): void
    {
        $permission = (new Permission())
            ->addVoter(new AlwaysYesVoter())
            ->addVoter(new AlwaysNoVoter())
        ;
        $user = new TestUser();
        $this->assertTrue($permission->can($user, 'test'));
    }

    public function testWithBadSpecificPermission(): void
    {
        $permission = (new Permission())
            ->addVoter(new SpecificVoter())
        ;
        $user = new TestUser();
        $this->assertFalse($permission->can($user, 'test'));
    }

    public function testWithGoodSpecificPermission(): void
    {
        $permission = (new Permission())
            ->addVoter(new SpecificVoter())
        ;
        $user = new TestUser();
        $this->assertTrue($permission->can($user, 'specific'));
    }

    public function testWithConditionVoter(): void
    {
        $permission = (new Permission())
            ->addVoter(new AuthorVoter())
        ;
        $user = new TestUser();
        $post = new TestPost($user);
        $this->assertTrue($permission->can($user, AuthorVoter::EDIT, $post));
    }

    public function testWithBadConditionVoter(): void
    {
        $permission = (new Permission())
            ->addVoter(new AuthorVoter())
        ;
        $user = new TestUser();
        $user2 = new TestUser();
        $post = new TestPost($user);
        $this->assertFalse($permission->can($user2, AuthorVoter::EDIT, $post));
    }

    public function testCanUseADebugger(): void
    {
        $debugger = $this->getMockBuilder(PermissionDebuggerInterface::class)->getMock();
        $debugger->expects($this->exactly(5))->method('debug');

        $user = new TestUser();
        /** @var PermissionDebuggerInterface $debugger */
        $permission = (new Permission($debugger))
            ->addVoter(new AlwaysNoVoter())
            ->addVoter(new AlwaysNoVoter())
            ->addVoter(new AlwaysNoVoter())
            ->addVoter(new AlwaysNoVoter())
            ->addVoter(new AlwaysYesVoter())
            ->addVoter(new AlwaysNoVoter())
        ;
        $permission->can(new TestUser(), 'test');
    }
}
