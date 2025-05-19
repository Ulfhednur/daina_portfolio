<?php
declare(strict_types=1);

namespace app\widgets;

use Yii;
use yii\bootstrap5\Widget;

class Alert extends Widget
{
    public yii\web\View $view;

    public array $alertTypes = [
        'error'   => 'danger',
        'danger'  => 'danger',
        'success' => 'success',
        'info'    => 'info',
        'warning' => 'warning'
    ];

    /**
     * {@inheritdoc}
     */
    public function run(): void
    {
        $session = Yii::$app->session;

        $alerts = [];
        foreach (array_keys($this->alertTypes) as $type) {
            $flash = $session->getFlash($type);

            foreach ((array) $flash as $message) {
                $alerts[] = 'UIkit.notification({
                            message: "'.$message.'",
                            status: "'.$this->alertTypes[$type].'",
                            pos: "top-center",
                            timeout: 5000
                        });';
            }

            $session->removeFlash($type);
        }

        if (!empty($alerts)) {
            $this->view->registerJs(
                'jQuery(document).ready(function($){
            ' . implode(PHP_EOL . PHP_EOL, $alerts) . '
            });'
            );
        }
    }
}
