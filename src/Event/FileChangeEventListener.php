<?php
namespace App\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class FileModificationListener implements EventSubscriberInterface
{
    private $KernelInterface;
    public function __construct(KernelInterface $KernelInterface)
    {
        $this->KernelInterface = $KernelInterface;
    }
    public function onKernelRequest(RequestEvent $event)
    {
        // Check for file modifications here
        // For example, you can check if a certain file has been modified
        $this->logFileModification($event);
    }

    private function logFileModification($event)
    {
        $application = new Application($this->KernelInterface);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'asset-map:compile',
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        // return new Response(""), if you used NullOutput()
        return true;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}