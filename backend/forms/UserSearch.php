<?php

namespace backend\forms;

use core\entities\User\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form of `core\entities\User\User`.
 */
class UserSearch extends Model
{
    public $id;
    public $username;
    public $status;
    public $email;
    public $created_from;
    public $created_to;
    public $updated_from;
    public $updated_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'email'], 'safe'],
            [['created_from', 'created_to', 'updated_from', 'updated_to'], 'date', 'format' => 'php:d.m.Y']
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['>=', 'created_at', $this->created_from ? strtotime($this->created_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->created_to ? strtotime($this->created_to . ' 23:59:59') : null])
            ->andFilterWhere(['>=', 'updated_at', $this->updated_from ? strtotime($this->updated_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'updated_at', $this->updated_to ? strtotime($this->updated_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
