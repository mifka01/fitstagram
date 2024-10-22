<?php

namespace app\commands;

use Faker\Factory;
use Faker\Generator;
use Yii;
use yii\console\Controller;

class SeedController extends Controller
{
    const USER_COUNT = 30;
    const POST_COUNT = 100;
    const MAX_MEDIA_FILES_IN_POST = 3;
    const MAX_GROUP_REQUESTS = 10;
    const TAG_COUNT = 45;
    const GROUP_COUNT = 7;

    private static Generator $faker;

    public function init(): void
    {
        parent::init();
        self::$faker = Factory::create('cs_CZ');
    }

    public function actionIndex(): void
    {
        $this->actionUsers();
        $this->actionGroups();
        $this->actionTags();
        $this->actionPosts();
        $this->actionPostVote();
        $this->actionComment();

        echo "\e[32mDatabase seeded succesfully.\e[0m\n";
    }

    public function actionClean(): void
    {
        if (YII_ENV_DEV) {
            $this->actionTruncate();
            $this->actionIndex();
        } else {
            echo "This command can only be used in development environment!\n";
        }
    }

    private function actionUsers(int $count = self::USER_COUNT): void
    {
        echo "Seeding of Users...\n";

        for ($i = 0; $i < $count; $i++) {
            $userName = self::$faker->userName;
            $user = [
                'username' =>  $userName,
                'email' => $userName . '@' . self::$faker->safeEmailDomain,
                'password_hash' => Yii::$app->security->generatePasswordHash('password'),
                'password_reset_token' => self::$faker->optional()->uuid,
                'verification_token' => Yii::$app->security->generateRandomString(),
                'auth_key' => Yii::$app->security->generateRandomString(),
                'active' => self::$faker->boolean(90),
                'deleted' => self::$faker->boolean(15),
                'banned' => self::$faker->boolean(5),
                'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
            ];
            Yii::$app->db->createCommand()->insert('user', $user)->execute();
            $userId = Yii::$app->db->getLastInsertID();
            $this->addPermittedUsers((int) $userId);
        }
    }

    public function addPermittedUsers(int $userId): void
    {

        $permittedUserIds[] = $userId;
        $count = self::$faker->numberBetween(0, (int) self::USER_COUNT / 2 - 1);
        for ($i = 0; $i < $count; $i++) {
            $permittedUserId = self::$faker->numberBetween(1, self::USER_COUNT);
            if (!in_array($permittedUserId, $permittedUserIds)) {
                $permittedUser = [
                    'user_id' => $userId,
                    'permitted_user_id' => $permittedUserId,
                ];
                Yii::$app->db->createCommand()->insert('permitted_user', $permittedUser)->execute();
                $permittedUserIds[] = $permittedUserId;
            }
        }
    }

    private function actionPosts(int $count = self::POST_COUNT): void
    {
        echo "Seeding of Posts...\n";

        for ($i = 0; $i < $count; $i++) {
            $post = [
                'created_by' => self::$faker->numberBetween(1, self::USER_COUNT),
                'is_group_post' => $isGroupPost = self::$faker->boolean(30),
                'is_private' => !$isGroupPost & self::$faker->boolean(),
                'upvote_count' => self::$faker->numberBetween(0, 100),
                'downvote_count' => self::$faker->numberBetween(0, 100),
                'description' => self::$faker->paragraph(3),
                'place' => self::$faker->city,
                // 10% chance to be deleted
                'deleted' => self::$faker->boolean(0),
                'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
            ];

            Yii::$app->db->createCommand()->insert('post', $post)->execute();
            $postId = Yii::$app->db->getLastInsertID();
            $this->addMediaFiles($postId);
            $this->addPostTags($postId);
            if ($isGroupPost) {
                $this->addGroupPosts($postId);
            }
        }
    }

    // action might not be the best prefix
    public function addMediaFiles(string $postId): void
    {

        $count = self::$faker->numberBetween(1, self::MAX_MEDIA_FILES_IN_POST);
        $mediaFiles = [];
        for ($i = 0; $i < $count; $i++) {
            $mediaFile = [
                'media_path' => '/images/medium.webp',
                'name' => self::$faker->word(),
                'post_id' => $postId,
            ];

            $mediaFiles[] = $mediaFile;
        }

        Yii::$app->db->createCommand()->batchInsert('media_file', ['media_path', 'name', 'post_id'], $mediaFiles)->execute();
    }

    public function addPostTags(string $postId): void
    {
        $tagIds = [];
        $count = self::$faker->numberBetween(0, (int) self::TAG_COUNT / 2);

        for ($i = 0; $i < $count; $i++) {
            $tagId = self::$faker->numberBetween(1, self::TAG_COUNT);
            if (!in_array($tagId, $tagIds)) {
                $groupMember = [
                    'post_id' => $postId,
                    'tag_id' => $tagId,
                    'created_at' =>  self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                ];
                $tagIds[] = $tagId;

                Yii::$app->db->createCommand()->insert('post_tag', $groupMember)->execute();
            }
        }
    }

    public function actionTags(int $count = self::TAG_COUNT): void
    {
        echo "Seeding of Tags...\n";

        for ($i = 0; $i < $count; $i++) {
            $tag = [
                'created_by' => self::$faker->numberBetween(1, self::USER_COUNT),
                'name' => self::$faker->unique()->word(),
                'created_at' => self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            ];
            Yii::$app->db->createCommand()->insert('tag', $tag)->execute();
        }
    }

    public function actionGroups(int $count = self::GROUP_COUNT): void
    {
        echo "Seeding of Groups...\n";


        for ($i = 0; $i < $count; $i++) {
            $status = self::$faker->randomElement(['active', 'deleted', 'banned']);
            $group = [
                'name' => self::$faker->unique()->word . ' Group',
                'owner_id' => self::$faker->numberBetween(1, self::USER_COUNT),
                'active' => $status === 'active' ? true : false,
                'deleted' => $status === 'deleted' ? true : false,
                'banned' => $status === 'banned' ? true : false,
                'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
            ];
            Yii::$app->db->createCommand()->insert('group', $group)->execute();
            $groupId = Yii::$app->db->getLastInsertID();
            $this->addGroupMembers($groupId);
            $this->addGroupJoinRequests((int)$groupId);
        }
    }

    public function addGroupMembers(string $groupId): void
    {

        $groupMembers = [];
        $count = self::$faker->numberBetween(0, self::USER_COUNT / 2);

        for ($i = 0; $i < $count; $i++) {
            $userId = self::$faker->numberBetween(1, self::USER_COUNT);
            if (!in_array($userId, $groupMembers)) {
                $groupMember = [
                    'group_id' => $groupId,
                    'user_id' => $userId,
                    'created_at' =>  self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                ];

                $groupMembers[] = $userId;
                $this->addAcceptedGroupJoinRequests((int) $groupId, $userId);

                Yii::$app->db->createCommand()->insert('group_member', $groupMember)->execute();
            }
        }
    }

    public function addGroupPosts(string $postId): void
    {

        $groupPost = [
            'group_id' => self::$faker->numberBetween(1, self::GROUP_COUNT),
            'post_id' => $postId,
        ];

        Yii::$app->db->createCommand()->insert('group_post', $groupPost)->execute();
    }

    public function addAcceptedGroupJoinRequests(int $groupId, int $userId): void
    {

        $groupJoinRequest = [
            'group_id' => $groupId,
            'created_by' => $userId,
            'pending' => false,
            'declined' => false,
            'accepted' => true,
            'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
        ];

        Yii::$app->db->createCommand()->insert('group_join_request', $groupJoinRequest)->execute();
    }

    public function addGroupJoinRequests(int $groupId): void
    {

        for ($i = 0; $i < self::MAX_GROUP_REQUESTS; $i++) {
            $groupJoinRequest = [
                'group_id' => $groupId,
                'created_by' => self::$faker->numberBetween(1, self::USER_COUNT),
                'pending' => $pending = self::$faker->boolean(),
                'declined' => !$pending,
                'accepted' => false,
                'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
            ];
            Yii::$app->db->createCommand()->insert('group_join_request', $groupJoinRequest)->execute();
        }
    }

    public function actionPostVote(): void
    {
        echo "Seeding of PostVotes...\n";

        $compositeKeys = [];
        $data = [];
        $count = (int)min(self::USER_COUNT, self::POST_COUNT) / 2;

        for ($i = 0; $i < $count; $i++) {
            $userId = self::$faker->numberBetween(1, self::USER_COUNT);
            $postId = self::$faker->numberBetween(1, self::POST_COUNT);
            $compositeKey = $userId . '-' . $postId;

            // Check if the composite key is unique
            if (!isset($compositeKeys[$compositeKey])) {
                // Mark this key as used
                $compositeKeys[$compositeKey] = true;

                $data[] = [
                    'up' => self::$faker->boolean(),
                    'voted_by' => $userId,
                    'post_id' => $postId,
                    'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                    'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),

                ];
            }
        }
        Yii::$app->db->createCommand()->batchInsert('post_vote', ['up', 'voted_by', 'post_id', 'created_at', 'updated_at'], $data)->execute();
    }

    public function actionComment(): void
    {
        echo "Seeding of Comments...\n";

        $count = self::POST_COUNT * 5;

        for ($i = 0; $i < $count; $i++) {
            $data[] = [
                'content' => self::$faker->text(200),
                'created_by' => self::$faker->numberBetween(1, self::USER_COUNT),
                'post_id' => self::$faker->numberBetween(1, self::POST_COUNT),
                'deleted' => self::$faker->boolean(10),
                'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
            ];
        }
        Yii::$app->db->createCommand()->batchInsert('comment', ['content', 'created_by', 'post_id', 'deleted', 'created_at', 'updated_at'], $data)->execute();
    }



    // delete all data from database usage: php yii seed/truncate
    public function actionTruncate(): void
    {
        if (YII_ENV_DEV) {
            $db = Yii::$app->db;

            $transaction = $db->beginTransaction();
            try {
                $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
                $tables = $db->schema->getTableNames();

                foreach ($tables as $table) {
                    if ($table != 'migration') {
                        $db->createCommand()->truncateTable($table)->execute();
                    }
                }

                $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
                $transaction->commit();
                echo  "\e[32mAll tables truncated succesfully.\e[0m\n";
            } catch (\Exception $e) {
                $transaction->rollBack();
                echo "Error truncating tables: " . $e->getMessage() . "\n";
            }
        } else {
            echo "This command can only be used in development environment!\n";
        }
    }
}
