<?php
namespace app\models\forms;

use app\models\Group;
use app\models\GroupJoinRequest;
use app\models\GroupMember;
use Yii;
use yii\base\Model;

class GroupMembershipForm extends Model
{
    public ?int $group_id = null;

    public ?int $user_id = null;

    public ?bool $approved = null;

    // Scenario constants
    public const SCENARIO_APPROVE_MEMBER = 'approve_member';
    public const SCENARIO_REJECT_MEMBER = 'reject_member';
    public const SCENARIO_LEAVE_GROUP = 'leave_group';

    /**
     * @return array<int, array<mixed>>
     */
    public function rules(): array
    {
        return [
            // Common rules for all scenarios
            [['group_id', 'user_id'], 'required'],
            [['group_id', 'user_id'], 'integer'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'id']],
            
            // Rules specific to approval/rejection scenarios
            [['approved'], 'required', 'on' => [self::SCENARIO_APPROVE_MEMBER, self::SCENARIO_REJECT_MEMBER]],

            // Additional validation for leaving group
            [['group_id'], 'validateGroupOwnership', 'on' => self::SCENARIO_LEAVE_GROUP],
        ];
    }

    /**
     * Validate that user is not trying to leave a group they own
     *
     * @param string $attribute
     */
    public function validateGroupOwnership(string $attribute): void
    {
        if (!$this->hasErrors()) {
            $isOwner = Group::find()
                ->where(['id' => $this->group_id, 'owner_id' => $this->user_id])
                ->exists();

            if ($isOwner) {
                $this->addError($attribute, 'Group owners cannot leave their own group.');
            }
        }
    }

    /**
     * Request to join a group
     *
     * @return bool
     */
    public function requestJoin(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->group_id === null) {
                $this->addError('group_id', 'Group ID is required.');
                return false;
            }
            if ($this->user_id === null) {
                $this->addError('user_id', 'User ID is required.');
                return false;
            }

            // Check if request already exists
            $existingRequest = GroupJoinRequest::findOne([
                'group_id' => $this->group_id,
                'created_by' => $this->user_id,
                'pending' => true,
            ]);

            if ($existingRequest) {
                $this->addError('user_id', 'You have already requested to join this group.');
                return false;
            }

            if (!Group::findOne($this->group_id)) {
                $this->addError('group_id', 'Group not found.');
                return false;
            }

            // Create new membership request
            $groupJoinRequest = new GroupJoinRequest();
            $groupJoinRequest->group_id = $this->group_id;
            $groupJoinRequest->created_by = $this->user_id;
            $groupJoinRequest->pending = (int)true;
            
            if (!$groupJoinRequest->save()) {
                $this->addErrors($groupJoinRequest->errors);
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error('Group join request error: ' . $e->getMessage());
            $this->addError('user_id', 'An error occurred while processing your request.');
            return false;
        }
    }

        /**
     * Request to join a group
     *
     * @return bool
     */
    public function requestCancel(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->group_id === null) {
                $this->addError('group_id', 'Group ID is required.');
                return false;
            }
            if ($this->user_id === null) {
                $this->addError('user_id', 'User ID is required.');
                return false;
            }

            // Check if request already exists
            $existingRequest = GroupJoinRequest::findOne([
                'group_id' => $this->group_id,
                'created_by' => $this->user_id,
                'pending' => true,
            ]);

            if (!$existingRequest) {
                $this->addError('user_id', 'You have not requested to join this group.');
                return false;
            }

            $existingRequest->delete();

            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error('Group join request error: ' . $e->getMessage());
            $this->addError('user_id', 'An error occurred while processing your request.');
            return false;
        }
    }

    /**
     * Approve a group membership request
     *
     * @return bool
     */
    public function approveMembershipRequest(): bool
    {
        return $this->processMembershipRequest(true);
    }

    /**
     * Reject a group membership request
     *
     * @return bool
     */
    public function rejectMembershipRequest(): bool
    {
        return $this->processMembershipRequest(false);
    }

    /**
     * Leave a group
     *
     * @return bool
     */
    public function leaveGroup(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $groupMember = GroupMember::findOne([
                'group_id' => $this->group_id,
                'user_id' => $this->user_id,
            ]);

            if (!$groupMember) {
                $this->addError('user_id', 'You are not a member of this group.');
                return false;
            }

            if (!$groupMember->delete()) {
                $this->addErrors($groupMember->errors);
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error('Group leave error: ' . $e->getMessage());
            $this->addError('user_id', 'An error occurred while leaving the group.');
            return false;
        }
    }

    /**
     * Process membership request (accept or decline)
     *
     * @param bool $accepted
     * @return bool
     */
    private function processMembershipRequest(bool $accepted): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Find the existing membership request
            $groupJoinRequest = GroupJoinRequest::findOne([
                'group_id' => $this->group_id,
                'user_id' => $this->user_id,
                'pending' => true,
            ]);

            if (!$groupJoinRequest) {
                $this->addError('user_id', 'Membership request not found.');
                return false;
            }

            $groupJoinRequest->pending = (int)false;
            // Update the status
            if ($accepted) {
                $groupJoinRequest->accepted = (int)true;
            } else {
                $groupJoinRequest->declined = (int)true;
            }
 
            
            if (!$groupJoinRequest->save()) {
                $this->addErrors($groupJoinRequest->errors);
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error('Group membership process error: ' . $e->getMessage());
            $this->addError('user_id', 'An error occurred while processing the membership request.');
            return false;
        }
    }
}
