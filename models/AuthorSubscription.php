<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "author_subscription".
 *
 * @property int $id
 * @property int $author_id
 * @property string $phone
 *
 * @property Authors $author
 */
class AuthorSubscription extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'author_subscription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'integer'],
            ['phone', 'match', 'pattern' => '/^7[0-9]{10}$/'],
            [['phone'], 'string', 'max' => 11],
            [['author_id', 'phone'], 'unique', 'targetAttribute' => ['author_id', 'phone']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Authors::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'phone' => 'Phone',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Authors::class, ['id' => 'author_id']);
    }

}
