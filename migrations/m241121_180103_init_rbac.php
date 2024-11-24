<?php

namespace app\migrations;

use app\rbac\CommentAuthorRule;
use app\rbac\GroupMemberRule;
use app\rbac\GroupOwnerRule;
use app\rbac\PermittedUserRule;
use app\rbac\PostAuthorRule;
use Yii;
use yii\db\Migration;

/**
 * Class m241121_180103_init_rbac
 */
class m241121_180103_init_rbac extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        if ($auth === null) {
            return;
        }

        $postAuthorRule = new PostAuthorRule();
        $auth->add($postAuthorRule);

        $commentAuthorRule = new CommentAuthorRule();
        $auth->add($commentAuthorRule);

        $groupOwnerRule = new GroupOwnerRule();
        $auth->add($groupOwnerRule);

        $groupMemberRule = new GroupMemberRule();
        $auth->add($groupMemberRule);

        $permittedUserRule = new PermittedUserRule();
        $auth->add($permittedUserRule);

        $managePost = $auth->createPermission('managePost');
        $auth->add($managePost);

        $manageOwnPost = $auth->createPermission('manageOwnPost');
        $manageOwnPost->ruleName = $postAuthorRule->name;
        $auth->add($manageOwnPost);

        $deleteComment = $auth->createPermission('deleteComment');
        $auth->add($deleteComment);

        $deleteOwnComment = $auth->createPermission('deleteOwnComment');
        $deleteOwnComment->ruleName = $commentAuthorRule->name;
        $auth->add($deleteOwnComment);

        $manageGroup = $auth->createPermission('manageGroup');
        $auth->add($manageGroup);

        $manageOwnGroup = $auth->createPermission('manageOwnGroup');
        $manageOwnGroup->ruleName = $groupOwnerRule->name;
        $auth->add($manageOwnGroup);

        $participateInGroup = $auth->createPermission('participateInGroup');
        $participateInGroup->ruleName = $groupMemberRule->name;
        $auth->add($participateInGroup);

        $permittedUser = $auth->createPermission('permittedUser');
        $permittedUser->ruleName = $permittedUserRule->name;
        $auth->add($permittedUser);

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);

        $user = $auth->createRole('user');
        $auth->add($user);

        $auth->addChild($admin, $moderator);
        $auth->addChild($moderator, $user);

        $auth->addChild($manageOwnPost, $managePost);
        $auth->addChild($deleteOwnComment, $deleteComment);
        $auth->addChild($manageOwnGroup, $manageGroup);

        $auth->addChild($moderator, $managePost);
        $auth->addChild($moderator, $deleteComment);
        $auth->addChild($moderator, $manageGroup);

        $auth->addChild($user, $manageOwnPost);
        $auth->addChild($user, $deleteOwnComment);
        $auth->addChild($user, $manageOwnGroup);
        $auth->addChild($user, $participateInGroup);
        $auth->addChild($user, $permittedUser);
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;


        if ($auth === null) {
            return;
        }

        $permissions = [
            'managePost',
            'manageOwnPost',
            'deleteComment',
            'deleteOwnComment',
            'manageGroup',
            'manageOwnGroup',
            'participateInGroup',
            'permittedUser',
        ];

        $rules = [
            'isPostAuthor',
            'isCommentAuthor',
            'isGroupOwner',
            'isGroupMember',
            'isPermittedUser',
        ];

        $roles = [
            'admin',
            'moderator',
            'user',
        ];

        foreach ($permissions as $permission) {
            if ($auth->getPermission($permission) !== null) {
                $auth->remove($auth->getPermission($permission));
            }
        }

        foreach ($rules as $rule) {
            if ($auth->getRule($rule) !== null) {
                $auth->remove($auth->getRule($rule));
            }
        }

        foreach ($roles as $role) {
            if ($auth->getRole($role) !== null) {
                $auth->remove($auth->getRole($role));
            }
        }
    }
}
