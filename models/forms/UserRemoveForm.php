<?php

namespace app\models\forms;

use app\models\Comment;
use app\models\Group;
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
                $postform = new PostRemoveForm();
                /** @var Post $post */
                $postform->id = $post->id;

                if (!$postform->delete()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            foreach ($user->getcomments()->all() as $comment) {
                $commentform = new CommentRemoveForm();
                /** @var Comment $comment */
                $commentform->id = $comment->id;

                if (!$commentform->delete()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            foreach ($user->getGroups()->all() as $group) {
                $groupform = new GroupForm();
                $groupform->scenario = GroupForm::SCENARIO_DELETE;
                /** @var Group $group */
                $groupform->id = $group->id;

                if (!$groupform->delete()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            $user->generateAuthKey();

            if (!$user->save(false)) {
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
                $postform = new PostRemoveForm();
                /** @var Post $post */
                $postform->id = $post->id;

                if (!$postform->ban()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            foreach ($user->getcomments()->all() as $comment) {
                $commentform = new CommentRemoveForm();
                /** @var Comment $comment */
                $commentform->id = $comment->id;

                if (!$commentform->ban()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            foreach ($user->getGroups()->all() as $group) {
                $groupform = new GroupForm();
                $groupform->scenario = GroupForm::SCENARIO_DELETE;
                /** @var Group $group */
                $groupform->id = $group->id;

                if (!$groupform->ban()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            $user->generateAuthKey();

            if (!$user->save(false)) {
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

    /**
     * Unban user profile.
     *
     * @return bool whether the user was unbanned successfully
     */
    public function unban(): bool
    {

        $user = User::findOne($this->id);
        if ($user === null) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->banned = (int)true;

            foreach ($user->getPosts()->all() as $post) {
                $postform = new PostRemoveForm();
                /** @var Post $post */
                $postform->id = $post->id;

                if (!$postform->unban()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            foreach ($user->getcomments()->all() as $comment) {
                $commentform = new CommentRemoveForm();
                /** @var Comment $comment */
                $commentform->id = $comment->id;

                if (!$commentform->unban()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            if (!$user->save(false)) {
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
