<?php

namespace brikdigital\hubspot\controllers;

use brikdigital\hubspot\console\controllers\HubspotController as ControllersHubspotController;
use brikdigital\hubspot\Hubspot;
use craft\web\Controller;

/**
 * Hubspot controller
 */
class HubspotController extends Controller
{
    protected array|bool|int $allowAnonymous = false;

    public function actionRun()
    {
        Hubspot::$plugin->getInstance()->controllerNamespace = 'brikdigital\hubspot\console\controllers';
        Hubspot::$plugin->getInstance()->runAction('hubspot/sync');

        return $this->redirect('admin/settings/plugins/hubspot');
    }

}
