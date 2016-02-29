<?php
namespace Actuator\Health\Indicator;

use Actuator\Health\HealthBuilder;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

class GuzzleRequestHealthIndicator extends AbstractHealthIndicator
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Actual health check logic.
     *
     * @param HealthBuilder $builder
     * @throws \Exception any Exception that should create a Status::DOWN
     * system status.
     */
    protected function doHealthCheck(HealthBuilder $builder)
    {
        /** @var Response $response */
        $response = $this->request
            ->send();

        $builder->withDetail('statusCode', $response->getStatusCode());

        if (!$response->isSuccessful()) {
            $builder->down()
                ->withDetail('body', $response->getBody(true));
            return;
        }

        $builder->up();
    }
}
