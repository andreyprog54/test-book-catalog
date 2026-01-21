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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
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

    /**
     * Возвращает список авторов в формате id => name для форм.
     *
     * @return array
     */
    public static function getList(): array
    {
        return static::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->column();
    }

    /**
     * Gets top N authors with most books for a specific year
     *
     * @param int|null $year The year to filter by (current year if null)
     * @param int $limit Number of authors to return
     * @return \yii\db\ActiveQuery
     */
    public static function getTopAuthorsByYear($year = null, $limit = 10)
    {
        $year = $year ? (int)$year : date('Y');

        return static::find()
            ->select([
                'authors.*',
                'COUNT(books.id) as book_count'
            ])
            ->joinWith(['books' => function($query) use ($year) {
                $query->andWhere(['books.year' => $year]);
            }])
            ->groupBy('authors.id')
            ->orderBy(['book_count' => SORT_DESC])
            ->limit($limit);
    }

}
