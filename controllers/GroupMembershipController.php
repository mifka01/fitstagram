<?php

namespace app\controllers;

use app\models\forms\GroupMembershipForm;
use app\models\GroupJoinRequest;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class GroupMembershipController extends Controller
{
    /**
     * {@inheritDoc}
     *
     * @return array<string, array<string, mixed>>
     */

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['request-join', 'request-cancel', 'leave-group'],
                        'allow' => true,
                        // Only authenticated users
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['approve-request', 'reject-request'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => static function (): bool {
                            // Use RBAC rule to check group ownership
                            return true;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Action to request joining a group
     */
    public function actionRequestJoin(int $id): Response|string
    {
        $model = new GroupMembershipForm(['group_id' => $id]);
        $user_id = Yii::$app->user->id;

        if ($user_id === null) {
            return $this->redirect(['site/login']);
        }
        $model->load(Yii::$app->request->post());
        // Set current user
        $model->user_id = (int)$user_id;
        if ($model->requestJoin()) {
            Yii::$app->session->setFlash('success', Yii::t('app/group', 'Request to join group submitted.'));
            return $this->redirect(['group/index']);
        }

        Yii::$app->session->setFlash('error', Yii::t('app/group', 'Error requesting to join group.'));
        return $this->redirect(['group/index']);
    }

    /**
     * Action to request joining a group
     */
    public function actionRequestCancel(int $id): Response|string
    {
        $model = new GroupMembershipForm(['group_id' => $id]);
        $user_id = Yii::$app->user->id;

        if ($user_id === null) {
            return $this->redirect(['site/login']);
        }
        $model->load(Yii::$app->request->post());
        // Set current user
        $model->user_id = (int)$user_id;
        if ($model->requestCancel()) {
            Yii::$app->session->setFlash('success', Yii::t('app/group', 'Request to join group cancelled.'));
            return $this->redirect(['group/index']);
        }

        Yii::$app->session->setFlash('error', Yii::t('app/group', 'Error cancelling request to join group.'));
        return $this->redirect(['group/index']);
    }

    /**
     * Action to approve membership request
     */
    public function actionApproveRequest(int $id): Response|string
    {
        $model = new GroupMembershipForm();
        $model->scenario = GroupMembershipForm::SCENARIO_APPROVE_MEMBER;
        $model->group_request_id = $id;

        $groupJoinRequest = GroupJoinRequest::findOne($model->group_request_id);
        if ($groupJoinRequest === null) {
            Yii::$app->session->setFlash('error', Yii::t('app/group', 'Membership request not found.'));
            return $this->redirect(['group/index']);
        }


        $model->load(Yii::$app->request->post());

        if ($model->approveMembershipRequest()) {
            Yii::$app->session->setFlash('success', Yii::t('app/group', 'Membership request approved.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/group', 'Error approving membership request.'));
        }
        return $this->redirect(['group/join-requests', 'id' => $groupJoinRequest->group_id]);
    }

    /**
     * Action to reject membership request
     */
    public function actionRejectRequest(int $id): Response|string
    {
        $model = new GroupMembershipForm();
        $model->scenario = GroupMembershipForm::SCENARIO_REJECT_MEMBER;
        $model->approved = false;
        $model->group_request_id = $id;

        $groupJoinRequest = GroupJoinRequest::findOne($model->group_request_id);
        if ($groupJoinRequest === null) {
            Yii::$app->session->setFlash('error', Yii::t('app/group', 'Membership request not found.'));
            return $this->redirect(['group/index']);
        }

        $model->load(Yii::$app->request->post());

        if ($model->rejectMembershipRequest()) {
            Yii::$app->session->setFlash('success', Yii::t('app/group', 'Membership request decllined.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/group', 'Error declining membership request.'));
        }
        return $this->redirect(['group/join-requests', 'id' => $groupJoinRequest->group_id]);
    }

    /**
     * Action to leave a group
     */
    public function actionLeaveGroup(int $id): Response|string
    {
        $model = new GroupMembershipForm(['group_id' => $id]);
        $model->scenario = GroupMembershipForm::SCENARIO_LEAVE_GROUP;
        $user_id = Yii::$app->user->id;

        if ($user_id === null) {
            return $this->redirect(['site/login']);
        }


        $model->load(Yii::$app->request->post());
        // Set current user
        $model->user_id = (int)$user_id;

        if ($model->leaveGroup()) {
            Yii::$app->session->setFlash('success', Yii::t('app/group', 'You have left the group.'));
            return $this->redirect(['group/index']);
        }

        Yii::$app->session->setFlash('error', Yii::t('app/group', 'Error leaving group.'));
        return $this->render('group/view', [
            'id' => $id,
        ]);
    }
}
