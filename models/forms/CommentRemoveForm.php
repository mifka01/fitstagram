<?php

namespace app\models\forms;

use app\models\Comment;
use Yii;
use yii\base\Model;

/**
 * CommentDeleteForm is the model behind the comment form.
 */
class CommentRemoveForm extends Model
{
    public ?int $id = null;

    /**
     * @return array<int, array<mixed>>
     */
    public function rules(): array
    {

        return [

            [['id'], 'integer'],
            [['id'], 'required'],
        ];
    }

    public function delete(): bool
    {
        $comment = Comment::findOne($this->id);
        if ($comment === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $comment->deleted = (int)true;

            if (!$comment->save(false)) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function ban(): bool
    {
        $comment = Comment::findOne($this->id);
        if ($comment === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $comment->banned = (int)true;

            if (!$comment->save(false)) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function unban(): bool
    {
        $comment = Comment::findOne($this->id);
        if ($comment === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $comment->banned = (int)false;

            if (!$comment->save(false)) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function restore(): bool
    {
        $comment = Comment::findOne($this->id);
        if ($comment === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $comment->deleted = (int)false;

            if (!$comment->save(false)) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
