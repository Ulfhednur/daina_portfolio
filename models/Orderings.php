<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */


namespace app\models;

use Exception;
use Yii;
use yii\db\Exception as dbException;

trait Orderings
{
    protected bool $disableTransactions = false;

    /**
     * @param bool  $transactional
     * @param array $condition
     *
     * @return void
     * @throws dbException
     */
    public static function reorder(bool $transactional = true, array $condition = []): void
    {
        $query = static::find()->orderBy(['ordering'=> SORT_ASC]);
        if ($condition) {
            $query->andWhere($condition);
        }
        $i = 0;
        if ($transactional) {
            $transaction = Yii::$app->db->beginTransaction();
        }
        try {
            foreach ($query->each() as $item) {
                $i++;
                /** @var static $item */
                $item->ordering = $i;
                $item->disableTransactions();
                $item->updateAttributes(['ordering']);
            }
            if ($transactional) {
                $transaction->commit();
            }
        } catch (Exception $e) {
            if ($transactional) {
                $transaction->rollBack();
            }
            throw $e;
        }
    }

    /**
     * @param array $items
     * @return void
     * @throws dbException
     */
    public static function updateOrdering(array $items): void
    {
        $query = static::find()->where(['in', 'id', array_column($items, 'id')]);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($query->each() as $item) {
                /** @var Post|Gallery $item */
                $item->disableTransactions();
                $item->ordering = $items[$item->id]['ordering'];
                $item->updateAttributes(['ordering']);
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        self::reorder();
    }

    public function disableTransactions(): static
    {
        $this->disableTransactions = true;
        return $this;
    }

    public function enableTransactions(): static
    {
        $this->disableTransactions = false;
        return $this;
    }
}