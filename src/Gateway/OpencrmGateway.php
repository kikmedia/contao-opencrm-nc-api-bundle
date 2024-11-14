<?php

declare(strict_types=1);

/*
 * This file is part of the Contao OpenCRM Gateway extension.
 *
 * (c) kikmedia
 *
 * @license LGPL-3.0-or-later
 */

namespace Kikmedia\OpencrmNCApiBundle\Gateway;

use Codefog\HasteBundle\StringParser;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\StringUtil;
use Contao\Validator;
use Kikmedia\OpencrmNCApiBundle\Config\OpencrmMessageConfig;
use Kikmedia\OpencrmNCApiBundle\Exception\OpencrmGatewayException;
use Kikmedia\OpencrmNCApiBundle\Parcel\Stamp\OpencrmMessageStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Terminal42\NotificationCenterBundle\Config\GatewayConfig;
use Terminal42\NotificationCenterBundle\Exception\Parcel\CouldNotDeliverParcelException;
use Terminal42\NotificationCenterBundle\Gateway\GatewayInterface;
use Terminal42\NotificationCenterBundle\Parcel\Parcel;
use Terminal42\NotificationCenterBundle\Parcel\Stamp\GatewayConfigStamp;
use Terminal42\NotificationCenterBundle\Parcel\Stamp\TokenCollectionStamp;
use Terminal42\NotificationCenterBundle\Receipt\Receipt;

class OpencrmGateway implements GatewayInterface
{
    public const NAME = 'opencrm';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ContaoFramework $contaoFramework,
        private readonly LoggerInterface $contaoGeneralLogger,
        private readonly LoggerInterface $contaoErrorLogger,
        private readonly KernelInterface $kernel,
        private readonly StringParser $stringParser,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function sealParcel(Parcel $parcel): Parcel
    {
        return $parcel
            ->withStamp($this->createOpencrmMessageStamp($parcel))
            ->seal()
        ;
    }

    public function sendParcel(Parcel $parcel): Receipt
    {
        try {
            $client = $this->createClientForGateway($parcel->getStamp(GatewayConfigStamp::class)->gatewayConfig);
            $config = $parcel->getStamp(OpencrmMessageStamp::class)->opencrmMessageConfig;
            $customerId = $this->getCustomerId($client, $config);

            // Create the ticket
            $response = $client->request(
                'POST',
                '/api/v1/tickets',
                [
                    'json' => [
                        'customer_id' => $customerId,
                        'title' => $config->getTitle(),
                        'group' => $config->getGroup(),
                        'article' => [
                            'subject' => $config->getTitle(),
                            'body' => $config->getBody(),
                            'type' => 'web',
                            'internal' => false,
                        ],
                    ],
                    'headers' => [
                        'X-On-Behalf-Of' => $customerId,
                    ],
                ],
            );

            $result = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

            $this->contaoGeneralLogger->info(sprintf('OpenCRM ticket #%s ("%s") created.', $result['id'], $result['title']));

            return Receipt::createForSuccessfulDelivery($parcel);
        } catch (\Throwable $e) {
            if ($this->kernel->isDebug()) {
                throw $e;
            }

            $this->contaoErrorLogger->error('OpenCRM API Request failed: '.$e->getMessage(), ['exception' => $e]);

            return Receipt::createForUnsuccessfulDelivery(
                $parcel,
                CouldNotDeliverParcelException::becauseOfGatewayException(self::NAME, exception: $e),
            );
        }
    }

    private function createOpencrmMessageStamp(Parcel $parcel): OpencrmMessageStamp
    {
        $this->contaoFramework->initialize();

        $messageConfig = $parcel->getMessageConfig();
        $tokens = $parcel->getStamp(TokenCollectionStamp::class)->tokenCollection->forSimpleTokenParser();
        $email = $this->stringParser->recursiveReplaceTokensAndTags($messageConfig->getString('opencrm_email'), $tokens);

        if (!$email || !Validator::isEmail($email)) {
            throw new OpencrmGatewayException('Invalid email address "'.$email.'" given.');
        }

        if (!$title = $this->stringParser->recursiveReplaceTokensAndTags($messageConfig->getString('opencrm_title'), $tokens)) {
            throw new OpencrmGatewayException('No title given!');
        }

        if (!$group = $this->stringParser->recursiveReplaceTokensAndTags($messageConfig->getString('opencrm_group'), $tokens)) {
            throw new OpencrmGatewayException('No group given!');
        }

        $parameters = [];

        foreach (StringUtil::deserialize($messageConfig->getString('opencrm_params'), true) as $param) {
            $key = $this->stringParser->recursiveReplaceTokensAndTags((string) $param['key'], $tokens);
            $value = $this->stringParser->recursiveReplaceTokensAndTags((string) $param['value'], $tokens);
            $parameters[$key] = $value;
        }

        return OpencrmMessageStamp::fromArray([
            'email' => $email,
            'parameters' => $parameters,
            'title' => $title,
            'group' => $group,
            'body' => $this->stringParser->recursiveReplaceTokensAndTags($messageConfig->getString('opencrm_body'), $tokens),
        ]);
    }

    private function createClientForGateway(GatewayConfig $gatewayConfig): HttpClientInterface
    {
        $options = ['base_uri' => $gatewayConfig->getString('opencrmHost')];

        match ($gatewayConfig->getString('opencrmAuthType')) {
            'basic' => $options['auth_basic'] = [$gatewayConfig->getString('opencrmUser'), $gatewayConfig->getString('opencrmPassword')],
            'token' => $options['auth_bearer'] = $gatewayConfig->getString('opencrmToken'),
            default => throw new OpencrmGatewayException('Invalid authentication type given.'),
        };

        return $this->client->withOptions($options);
    }

    private function getCustomerId(HttpClientInterface $client, OpencrmMessageConfig $config): string
    {
        // Search for the customer first
        $response = $client->request(
            'GET',
            '/api/v1/users/search',
            [
                'query' => [
                    'query' => $config->getEmail(),
                    'limit' => 1,
                ],
            ],
        );

        if ($result = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR)) {
            // Customer already exists
            return (string) $result[0]['id'];
        }

        // Create the customer
        $parameters = $config->getParameters();
        $parameters['email'] = $config->getEmail();

        $response = $client->request('POST', '/api/v1/users', ['json' => $parameters]);

        $result = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->contaoGeneralLogger->info(sprintf('OpenCRM customer #%s (%s) created.', $result['id'], $result['email']));

        return (string) $result['id'];
    }
}
