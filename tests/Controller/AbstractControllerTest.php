<?php

namespace App\Controller;

use Lucario\Controller\AbstractController;
use Lucario\Session\SessionInterface;
use Lucario\Tests\Session\SessionArray;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class AbstractControllerTest extends TestCase
{
    public function testAddFlash(): void
    {
        $session = new SessionArray();
        define('TEMPLATE_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR.'templates');
        $controller = new class extends AbstractController {
            public function testFlash(SessionInterface $session): void
            {
                $this->generateCsrfToken();
                $this->setSession($session);
                $this->addFlash('error', 'Test error');
            }
        };
        $controller->testFlash($session);
        $this->assertSame(['error' => ['Test error']], $session->get('_flashbag'));
    }

    public function testAddFlashWithTwoMessages(): void
    {
        $session = new SessionArray();
        define('TEMPLATE_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR.'templates');
        $controller = new class extends AbstractController {
            public function testFlash(SessionInterface $session): void
            {
                $this->setSession($session);
                $this->addFlash('error', 'Test error');
                $this->addFlash('info', 'Test info error');
            }
        };
        $controller->testFlash($session);
        $this->assertSame(['error' => ['Test error'], 'info' => ['Test info error']], $session->get('_flashbag'));
    }

//    public function testRedirectToRoute(): void
//    {
//        define('TEMPLATE_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR.'templates');
//        $controller = new class extends AbstractController {
//            public function testRedirect(): void
//            {
//                $this->redirectToRoute('/test-redirect');
//            }
//        };
//        $controller->testRedirect();
//        $this->assertContains('Location: /test-redirect', xdebug_get_headers());
//    }

    public function testIsSubmittedReturnFalseIfNoPostMethodUsedOrEmpty(): void
    {
        define('TEMPLATE_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR.'templates');
        $controller = new class extends AbstractController {
            public function testPost(): bool
            {
                return $this->isSubmitted();
            }
        };
        $this->assertFalse($controller->testPost());
    }

    public function testIsSubmittedReturnFalseIfPostButNoCsrfToken(): void
    {
        define('TEMPLATE_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR.'templates');
        $controller = new class extends AbstractController {
            public function testPost(): bool
            {
                $_POST['name'] = 'Test';
                return $this->isSubmitted();
            }
        };
        $this->assertFalse($controller->testPost());
    }

    public function testIsSubmittedReturnTrueIfPostAndCsrfTokenPassed(): void
    {
        define('TEMPLATE_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR.'templates');
        $controller = new class extends AbstractController {
            public function testPost(): bool
            {
                $_POST['csrf_token'] = $this->generateCsrfToken();
                return $this->isSubmitted();
            }
        };
        $this->assertTrue($controller->testPost());
    }

    public function testEncodePassword(): void
    {
        define('TEMPLATE_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR.'templates');
        $controller = new class extends AbstractController{
            public function testEncodePassword(string $password): string
            {
                return $this->encodePassword($password);
            }
        };
        $password = 'test_password';
        $passwordEncoded = $controller->testEncodePassword($password);
        $this->assertTrue(password_verify($password, $passwordEncoded));
    }
}
