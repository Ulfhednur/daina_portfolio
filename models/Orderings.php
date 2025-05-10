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
     * @return void
     * @throws dbException
     */
    protected function reorder(bool $transactional = true, array $condition = []): void
    {
        $disableTransactions = $this->disableTransactions;
        $this->disableTransactions = true;
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
        $this->disableTransactions = $disableTransactions;
    }

    /**
     * @param array $items
     * @return void
     * @throws dbException
     */
    public function updateOrdering(array $items): void
    {
        $disableTransactions = $this->disableTransactions;
        $this->disableTransactions = true;
        $query = static::find()->where(['in', 'id', array_keys($items)]);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($query->each() as $item) {
                /** @var static $item */
                $item->ordering = $items[$item->id];
                $item->updateAttributes(['ordering']);
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $this->reorder();
        $this->disableTransactions = $disableTransactions;
    }

    public function moveUp(int $id): void
    {
        $disableTransactions = $this->disableTransactions;
        $this->disableTransactions = true;

        $item = static::findOne(['id' => $id]);
        $neighbour = static::findOne(['ordering' => ($item->ordering - 1)]);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $item->ordering--;
            $neighbour->ordering++;
            $item->updateAttributes(['ordering']);
            $neighbour->updateAttributes(['ordering']);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        $this->disableTransactions = $disableTransactions;
    }

    public function moveDown(int $id): void
    {
        $disableTransactions = $this->disableTransactions;
        $this->disableTransactions = true;

        $item = static::findOne(['id' => $id]);
        $neighbour = static::findOne(['ordering' => ($item->ordering + 1)]);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $item->ordering++;
            $neighbour->ordering--;
            $item->updateAttributes(['ordering']);
            $neighbour->updateAttributes(['ordering']);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        $this->disableTransactions = $disableTransactions;
    }
}