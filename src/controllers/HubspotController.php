<?php

namespace brikdigital\hubspot\controllers;

use brikdigital\hubspot\console\controllers\HubspotController as ControllersHubspotController;
use brikdigital\hubspot\Hubspot;
use craft\web\Controller;
use yii\web\Response;

/**
 * Hubspot controller
 */
class HubspotController extends Controller
{
    protected array|bool|int $allowAnonymous = false;

    public function actionRun(): Response
    {
        Hubspot::$plugin->getInstance()->controllerNamespace = 'brikdigital\hubspot\console\controllers';
        Hubspot::$plugin->getInstance()->runAction('hubspot/sync');

        return $this->redirect('admin/settings/plugins/brik-hubspot');
    }

}
