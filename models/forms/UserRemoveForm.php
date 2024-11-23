<?php

namespace app\models\forms;

use app\models\Comment;
use app\models\Post;
use app\models\User;
use Yii;
use yii\base\Model;

class UserRemoveForm extends Model
{
    public ?int $id = null;

    /**
     * {@inheritDoc}
     *
     * @return array<int, mixed>
     */
    public function rules(): array
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],

        ];
    }

    /**
     * Delete user profile.
     *
     * @return bool whether the user was deleted successfully
     */
    public function delete(): bool
    {
        $user = User::findOne($this->id);
        if ($user === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->deleted = (int)true;

            foreach ($user->getPosts()->all() as $post) {
                /** @var Post $post */
                $post->deleted = (int)true;
                if (!$post->save()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            foreach ($user->getComments()->all() as $comment) {
                /** @var Comment $comment */
                $comment->deleted = (int)true;
                if (!$comment->save()) {
                    $transaction->rollBack();
                    return false;
                }
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

    /**
     * Ban user profile.
     *
     * @return bool whether the user was banned successfully
     */
    public function ban(): bool
    {

        $user = User::findOne($this->id);
        if ($user === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->banned = (int)true;

            foreach ($user->getPosts()->all() as $post) {
                /** @var Post $post */
                $post->banned = (int)true;
                if (!$post->save()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            foreach ($user->getComments()->all() as $comment) {
                /** @var Comment $comment */
                $comment->banned = (int)true;
                if (!$comment->save()) {
                    $transaction->rollBack();
                    return false;
                }
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
