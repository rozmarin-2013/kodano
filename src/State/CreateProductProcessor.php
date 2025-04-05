<?php

namespace App\State;

use App\Entity\Product;
use App\Notification\NotificationInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\Operation;

class CreateProductProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface    $innerProcessor,
        private NotificationInterface $notification,
        private LoggerInterface       $createProductLogger,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Product
    {
        assert($data instanceof Product);

        $this->createProductLogger->info('product created', ['product' => $data->getName()]);

        $this->notification->send('Create product');
        $this->innerProcessor->process($data, $operation, $uriVariables, $context);

        return $data;
    }
}