<?php

namespace app\commands;

use Faker\Factory;
use Faker\Generator;
use Yii;
use yii\console\Controller;

class SeedController extends Controller
{
    const MODERATOR_COUNT = 3;
    const USER_COUNT = 30;
    const POST_COUNT = 100;
    const MAX_MEDIA_FILES_IN_POST = 3;
    const MAX_GROUP_REQUESTS = 10;
    const TAG_COUNT = 45;
    const GROUP_COUNT = 7;

    private static Generator $faker;

    /**
     * @var array<string, bool> $usedCombinations Tracks used group_id and user_id combinations.
     */
    private static array $usedCombinations = [];

    public function init(): void
    {
        parent::init();
        self::$faker = Factory::create('cs_CZ');
        self::$faker->seed(1234);
    }

    public function actionIndex(): void
    {
        $this->actionAdmins();
        $this->actionModerators();
        $this->actionUsers();
        $this->addPermittedUsers();
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

    private function actionAdmins(): void
    {
        echo "Seeding of Admins...\n";

        $admin = [
            'username' => 'admin',
            'email' => 'admin' . '@' . self::$faker->safeEmailDomain,
            'password_hash' => '$2a$12$C8kpUKrS5j51gK1lu1a.OOPHIGQsOMD0V8xjIsxt6HGsp.ssxIssm',
            'password_reset_token' => self::$faker->optional()->uuid,
            'verification_token' => Yii::$app->security->generateRandomString(),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'active' => true,
            'deleted' => false,
            'banned' => false,
            'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
        ];

        Yii::$app->db->createCommand()->insert('user', $admin)->execute();
        $userId = Yii::$app->db->getLastInsertID();
        Yii::$app->db->createCommand()->insert(
            'auth_assignment',
            [
                'item_name' => 'admin',
                'user_id' => $userId,
                'created_at' => self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s')
            ]
        )->execute();
    }

    private function actionModerators(int $count = self::MODERATOR_COUNT): void
    {
        echo "Seeding of Moderators...\n";

        for ($i = 0; $i < $count; $i++) {
            $moderator = [
                'username' => 'moderator' . $i,
                'email' => 'moederatot' . $i . '@' . self::$faker->safeEmailDomain,
                'password_hash' => '$2a$12$RvTlTSObKbmCrzgD4H4lHOC7FtEYfrFcmUMw19P6ItIGkl8ZlEwjG',
                'password_reset_token' => self::$faker->optional()->uuid,
                'verification_token' => Yii::$app->security->generateRandomString(),
                'auth_key' => Yii::$app->security->generateRandomString(),
                'active' => true,
                'deleted' => false,
                'banned' => false,
                'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
            ];
            Yii::$app->db->createCommand()->insert('user', $moderator)->execute();
            $userId = Yii::$app->db->getLastInsertID();
            Yii::$app->db->createCommand()->insert(
                'auth_assignment',
                [
                    'item_name' => 'moderator',
                    'user_id' => $userId,
                    'created_at' => self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s')
                ]
            )->execute();
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
                'password_hash' => Yii::$app->security->generatePasswordHash('xPassword1242'),
                'password_reset_token' => self::$faker->optional()->uuid,
                'verification_token' => Yii::$app->security->generateRandomString(),
                'auth_key' => Yii::$app->security->generateRandomString(),
                'active' => true,
                'deleted' => false,
                'banned' => false,
                'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
                'show_activity' => self::$faker->boolean(20),
            ];
            Yii::$app->db->createCommand()->insert('user', $user)->execute();
            $userId = Yii::$app->db->getLastInsertID();
            Yii::$app->db->createCommand()->insert(
                'auth_assignment',
                [
                    'item_name' => 'user',
                    'user_id' => $userId,
                    'created_at' => self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s')
                ]
            )->execute();
        }
    }

    public function addPermittedUsers(int $count = self::USER_COUNT): void
    {
        for ($userId = 1; $userId <= $count; $userId++) {
            // Ensure the user is their own permitted user
            $permittedUserIds = [$userId];
            Yii::$app->db->createCommand()->insert('permitted_user', [
                'user_id' => $userId,
                'permitted_user_id' => $userId,
            ])->execute();

            // Determine the number of additional permitted users
            $additionalCount = self::$faker->numberBetween(0, (int) $count / 2 - 1);

            for ($i = 0; $i < $additionalCount; $i++) {
                $permittedUserId = self::$faker->numberBetween(1, $count);

                // Ensure the relationship is unique
                if (!in_array($permittedUserId, $permittedUserIds)) {
                    Yii::$app->db->createCommand()->insert('permitted_user', [
                        'user_id' => $userId,
                        'permitted_user_id' => $permittedUserId,
                    ])->execute();
                    $permittedUserIds[] = $permittedUserId;
                }
            }
        }
    }

    private function actionPosts(int $count = self::POST_COUNT): void
    {
        echo "Seeding of Posts...\n";

        for ($i = 0; $i < $count; $i++) {
            $isGroupPost = self::$faker->boolean(30);
            $post = [
                'created_by' => self::$faker->numberBetween(1, self::USER_COUNT),
                'group_id' => $isGroupPost ? self::$faker->numberBetween(1, self::GROUP_COUNT) : null,
                'is_private' => !$isGroupPost & self::$faker->boolean(),
                'upvote_count' => self::$faker->numberBetween(0, 100),
                'downvote_count' => self::$faker->numberBetween(0, 100),
                'description' => self::$faker->paragraph(3),
                'place' => self::$faker->city,
                'deleted' => self::$faker->boolean(10),
                'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
            ];

            Yii::$app->db->createCommand()->insert('post', $post)->execute();
            $postId = Yii::$app->db->getLastInsertID();
            $this->addMediaFiles($postId);
            $this->addPostTags($postId);
        }
    }

    // action might not be the best prefix
    public function addMediaFiles(string $postId): void
    {

        $count = self::$faker->numberBetween(1, self::MAX_MEDIA_FILES_IN_POST);
        $mediaFiles = [];
        for ($i = 0; $i < $count; $i++) {
            $mediaFile = [
                'path' => 'images/seederimage.jpg',
                'name' => self::$faker->word(),
                'post_id' => $postId,
            ];

            $mediaFiles[] = $mediaFile;
        }

        Yii::$app->db->createCommand()->batchInsert('media_file', ['path', 'name', 'post_id'], $mediaFiles)->execute();
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
            $group = [
                'name' => self::$faker->text(80),
                'description' => self::$faker->text(512),
                'owner_id' => $ownerId = self::$faker->numberBetween(1, self::USER_COUNT),
                'deleted' => false,
                'created_at' => $createdAt = self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => self::$faker->dateTimeBetween($createdAt)->format('Y-m-d H:i:s'),
            ];
            Yii::$app->db->createCommand()->insert('group', $group)->execute();
            $groupId = Yii::$app->db->getLastInsertID();
            $this->addGroupMembers($groupId, $ownerId);
            $this->addGroupJoinRequests((int)$groupId);
        }
    }

    public function addGroupMembers(string $groupId, int $ownerId): void
    {

        $groupMembers = [];
        $groupMember = [
            'group_id' => $groupId,
            'user_id' => $ownerId,
            'created_at' =>  self::$faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
        ];
        Yii::$app->db->createCommand()->insert('group_member', $groupMember)->execute();
        $groupMembers[] = $ownerId;

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
        $combinationKey = "{$groupId}_{$userId}";

        if (isset(self::$usedCombinations[$combinationKey])) {
            return;
        }

        self::$usedCombinations[$combinationKey] = true;

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
            $userId = self::$faker->numberBetween(1, self::USER_COUNT);
            $combinationKey = "{$groupId}_{$userId}";

            if (isset(self::$usedCombinations[$combinationKey])) {
                continue;
            }

            self::$usedCombinations[$combinationKey] = true;

            $groupJoinRequest = [
                'group_id' => $groupId,
                'created_by' => $userId,
                'pending' => self::$faker->boolean(),
                'declined' => false,
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
