<?php

namespace app\models\forms;

use app\models\Post;
use app\models\PostVote;
use app\services\PostPermissionService;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class PostVoteForm extends Model
{
    private const int TYPE_UP = 1;
    private const int TYPE_DOWN = 0;

    public int $postId = -1;

    public int $type = -1;

    /**
     * @return array<int, array<mixed>>
     */
    public function rules(): array
    {
        return [
            [['postId', 'type'], 'required'],
            [['postId', 'type'], 'integer'],
            ['type', 'in', 'range' => [$this::TYPE_DOWN, $this::TYPE_UP]],
        ];
    }

    public function vote() : bool
    {
        if (!PostPermissionService::checkPostPermission(Yii::$app->user->id, $this->postId)) {
            throw new NotFoundHttpException(Yii::t('app/error', 'Post not found.'));
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $post = Post::findOne($this->postId);
            if (!$post) {
                return false;
            }

            $postVote = $post->currentUserVote;
            if ($postVote) {
                // post already voted by user
                if ($postVote->up == $this->type) {
                    //clicking the same -> remove vote
                    if ($this->type == self::TYPE_UP) {
                        $post->updateCounters(['upvote_count' => -1]);
                    } else {
                        $post->updateCounters(['downvote_count' => -1]);
                    }
                    $postVote->delete();
                } else {
                    //clicking different -> change vote
                    if ($this->type == self::TYPE_UP) {
                        $post->updateCounters(['upvote_count' => 1, 'downvote_count' => -1]);
                    } else {
                        $post->updateCounters(['upvote_count' => -1, 'downvote_count' => 1]);
                    }
                    $postVote->up = $this->type;
                    $postVote->save();
                }
            } else {
                // new vote is added
                if ($this->type == self::TYPE_UP) {
                    $post->upvote_count++;
                } else {
                    $post->downvote_count++;
                }
                $post->link('votes', new PostVote(['up' => $this->type, 'voted_by' => \Yii::$app->user->id]));
            }

            if ($post->save()) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return false;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
