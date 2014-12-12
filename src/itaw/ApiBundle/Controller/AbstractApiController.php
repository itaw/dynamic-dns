<?php

namespace itaw\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractApiController extends Controller
{
    /**
     * @param $data
     * @return mixed
     */
    protected function serializeJson($data)
    {
        $serializer = $this->get('jms_serializer');

        return $serializer->serialize($data, 'json');
    }

    /**
     * @param $json
     * @param $type
     * @return mixed
     */
    protected function deSerializeJson($json, $type)
    {
        $serializer = $this->get('jms_serializer');

        return $data = $serializer->deserialize($json, $type, 'json');
    }
}