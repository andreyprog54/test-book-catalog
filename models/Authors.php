<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "authors".
 *
 * @property int $id
 * @property string $name
 *
 * @property BookAuthor[] $bookAuthors
 */
class Authors extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors';
    }

    public $bookIds = [];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            ['bookIds', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->bookIds = $this->getBooks()->select('id')->column();
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        // Удаляем старые связи
        BookAuthor::deleteAll(['author_id' => $this->id]);
        
        // Добавляем новые связи
        if (!empty($this->bookIds)) {
            $rows = [];
            foreach ($this->bookIds as $bookId) {
                $rows[] = [$bookId, $this->id];
            }
            Yii::$app->db->createCommand()
                ->batchInsert('book_author', ['book_id', 'author_id'], $rows)
                ->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthor()
    {
        return $this->hasMany(BookAuthor::class, ['author_id' => 'id']);
    }

    public function getBooks()
    {
        return $this->hasMany(Books::class, ['id' => 'book_id'])
            ->via('bookAuthor');
    }

}
