<?php

namespace app\models\forms;

use app\models\Comment;
use app\models\Post;

use Yii;
use yii\base\Model;

/**
 * PostRemoveForm is the model behind the post form.
 */
class PostRemoveForm extends Model
{
    public ?int $id = null;

    /**
     * @return array<int, array<mixed>>
     */
    public function rules(): array
    {

        return [
            [['id'], 'required'],
            [['id'], 'integer'],
        ];
    }

    public function unban(): bool
    {
        $post = Post::findOne($this->id);
        if ($post === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post->banned = (int)false;

            foreach ($post->getComments()->all() as $comment) {
                $commentform = new CommentRemoveForm();
                /** @var Comment $comment */
                $commentform->id = $comment->id;

                if (!$commentform->unban()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            if (!$post->save(false)) {
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
        $post = Post::findOne($this->id);
        if ($post === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post->banned = (int)true;

            foreach ($post->getComments()->all() as $comment) {
                $commentform = new CommentRemoveForm();
                /** @var Comment $comment */
                $commentform->id = $comment->id;

                if (!$commentform->ban()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            if (!$post->save(false)) {
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

    public function delete(): bool
    {
        $post = Post::findOne($this->id);
        if ($post === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post->deleted = (int)true;

            foreach ($post->getComments()->all() as $comment) {
                $commentform = new CommentRemoveForm();
                /** @var Comment $comment */
                $commentform->id = $comment->id;

                if (!$commentform->delete()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            if (!$post->save(false)) {
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
        $post = Post::findOne($this->id);
        if ($post === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post->deleted = (int)false;

            foreach ($post->getComments()->all() as $comment) {
                $commentform = new CommentRemoveForm();
                /** @var Comment $comment */
                $commentform->id = $comment->id;

                if (!$commentform->restore()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            if (!$post->save(false)) {
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
