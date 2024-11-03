<?php

namespace app\models\forms;

use app\models\Post;
use app\models\PostVote;
use yii\base\Model;

class PostVoteForm extends Model
{
    private const int TYPE_UP = 1;
    private const int TYPE_DOWN = 0;

    public int $id;

    public int|null $type = null;

    public function rules(): array
    {
        return [
            [['id', 'type'], 'required'],
            [['id', 'type'], 'integer'],
            ['type', 'in', 'range' => [$this::TYPE_DOWN, $this::TYPE_UP]],
        ];
    }

    public function vote()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $post = Post::findOne($this->id);
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
