<?php

namespace app\models\search;

use app\enums\GroupType;
use app\models\Group;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;

/**
 * UserGroupSearch represents the model behind the search form of `app\models\Group`.
 */
class UserGroupSearch extends Model
{
    private ?string $formName = null;

    public bool $newest = true;

    public bool $oldest = false;

    public ?string $keyword = null;

    /**
     * {@inheritDoc}
     *
     * @param string|null $formName
     * @param array<string,mixed> $config
     */
    public function __construct(?string $formName = null, $config = [])
    {
        if ($formName !== null) {
            $this->formName = $formName;
        }
        parent::__construct($config);
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['newest', 'oldest'], 'boolean'],
            ['keyword', 'string'],
            ['keyword', 'trim'],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string,string>
     */
    public function attributeLabels(): array
    {
        return [
            'keyword' => Yii::t('app/model', 'Search'),
            'newest' => Yii::t('app/model', 'Newest'),
            'oldest' => Yii::t('app/model', 'Oldest'),
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array<string,string> $params
     * @param GroupType $type
     * @return ActiveDataProvider
     */
    public function search($params, GroupType $type = GroupType::PUBLIC): ActiveDataProvider
    {
        $query = $this->createQueryByType($type);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->keyword)) {
            $query->andWhere(['or',
                ['like', 'name', $this->keyword],
                ['like', 'description', $this->keyword]
            ]);
        }

        if ($this->oldest) {
            $query->orderBy(['created_at' => SORT_ASC]);
            return $dataProvider;
        }

        $query->orderBy(['created_at' => SORT_DESC]);
        return $dataProvider;
    }

    /**
     * Creates a query based on the group type
     *
     * @param GroupType $type
     * @return ActiveQuery
     */
    private function createQueryByType(GroupType $type): ActiveQuery
    {
        $user = $this->getCurrentUser();

          return match ($type) {
              GroupType::PUBLIC => Group::find()
                ->active()
                ->deleted(false)
                ->banned(false)
                ->with('owner'),
                
            GroupType::OWNED => $user !== null ? $user->getCreatedGroups()
                ->deleted(false)
                ->banned(false)
                ->with('owner') : Group::find()->where('1=0'),
                
              GroupType::JOINED => $user !== null ? $user->getJoinedGroups()
                ->active()
                ->deleted(false)
                ->banned(false)
                ->andWhere(['NOT IN', 'group.owner_id', $user->id])
                ->with('owner') : Group::find()->where('1=0'),
          };
    }

    /**
     * Get the current user.
     *
     * @return User|null
     * @throws NotFoundHttpException
     */
    private function getCurrentUser(): User|null
    {
        if (Yii::$app->user->isGuest) {
            return null;
        }

        $user = User::findOne(Yii::$app->user->id);
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }
        return $user;
    }

    public function formName(): string
    {
        return $this->formName ?? parent::formName();
    }
}
