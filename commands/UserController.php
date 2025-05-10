<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

namespace app\commands;

use app\models\User;
use Throwable;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;

/**
 * User manager
 */
class UserController extends Controller
{
    /**
     * Creates new user
     * @return int Exit code
     * @throws Exception
     */
    public function actionCreate(string $login): int
    {
        echo 'Enter the password: ' . PHP_EOL;
        $handle = fopen ("php://stdin","r");
        $password = trim(fgets($handle));
        fclose($handle);

        $user = new User();
        $user->login = $login;
        $user->user_password = User::hashPassword($password);
        $user->save();

        echo "User {$login} created" . PHP_EOL;
        return ExitCode::OK;
    }

    /**
     * Changes user password
     * @param string $login
     * @return int
     * @throws Exception
     */
    public function actionChangePassword(string $login): int
    {
        echo 'Enter the password: ' . PHP_EOL;
        $handle = fopen ("php://stdin","r");
        $password = trim(fgets($handle));
        fclose($handle);

        $user = User::findOne(['login' => $login]);
        if (!empty($user)) {
            $user->user_password = User::hashPassword($password);
            $user->save();

            echo "Password changed" . PHP_EOL;
        } else {
            echo "User not found" . PHP_EOL;
        }
        return ExitCode::OK;
    }

    /**
     * Removes user
     * @param string $login
     * @return int
     * @throws Exception|Throwable
     */
    public function actionRemove(string $login): int
    {
        $user = User::findOne(['login' => $login]);
        if (!empty($user)) {
            $user->delete();

            echo "User removed" . PHP_EOL;
        } else {
            echo "User not found" . PHP_EOL;
        }
        return ExitCode::OK;
    }
}
