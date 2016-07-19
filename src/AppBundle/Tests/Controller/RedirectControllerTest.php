<?php

namespace AppBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
class RedirectControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Kernel
     */
    private $app;

    /**
     * @before
     */
    protected function initialize()
    {
        $this->app = new \AppKernel('test', true);
        $this->app->boot();
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageAccess($url, $isRedirect)
    {
        $request = Request::create($url);
        $response = $this->app->handle($request);

        if ($isRedirect) {
            self::assertTrue($response->isRedirection());
        } else {
            self::assertFalse($response->isRedirection());
        }
    }

    /**
     * @return array
     */
    public function urlProvider()
    {
        $this->initialize();

        return [
            'Not to redirect' => ['/some-page', false],
            'To redirect' => ['/some-page/', true],
        ];
    }
}
