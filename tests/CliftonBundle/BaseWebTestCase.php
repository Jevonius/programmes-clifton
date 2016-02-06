<?php

namespace Tests\BBC\CliftonBundle;

use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class BaseWebTestCase extends WebTestCase
{
    const FIXTURES_PATH = 'Tests\BBC\CliftonBundle\DataFixtures\ORM\\';

    public function assertResponseStatusCode($client, $expectedCode)
    {
        $actualCode = $client->getResponse()->getStatusCode();
        $this->assertEquals($expectedCode, $actualCode, sprintf(
            'Failed asserting that the response status code "%s" matches expected "%s"',
            $actualCode,
            $expectedCode
        ));
    }

    public function assertRedirectTo($client, $code, $expectedLocation)
    {
        $this->assertResponseStatusCode($client, $code);
        $this->assertEquals($expectedLocation, $client->getResponse()->headers->get('location'));
    }

    protected static function createAuthenticatedClient(array $options = array(), array $server = array())
    {
        $sslSubject = 'Email=dummy.testuser@bbc.co.uk, CN=dummy.testuser, OU=Business, O=(null), L=(null), C=(null)';

        $server = array_merge($server, array(
            'HTTP_SSLCLIENTCERTSUBJECT' => $sslSubject,
        ));

        return static::createClient($options, $server);
    }

    protected function loadFixtures(array $fixtureNames, $omName = null, $registryName = 'doctrine', $purgeMode = null)
    {
        $classNames = array();
        foreach ($fixtureNames as $fixtureName) {
            $className = self::FIXTURES_PATH . $fixtureName;
            array_push($classNames, $className);
        }
        parent::loadFixtures($classNames, $omName, $registryName, $purgeMode);
    }

    protected function getDecodedJsonContent($client)
    {
        $content = $client->getResponse()->getContent();

        $decodedContent = json_decode($content);
        $this->assertNotNull($decodedContent, 'Expected response content to be valid JSON but it was"' . $content . '"');

        return $decodedContent;
    }
}
