<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $cover_image
 *
 * @property BookAuthor[] $bookAuthors
 */
class Books extends \yii\db\ActiveRecord
{
    public $authorIds = [];

    /**
     * @var \yii\web\UploadedFile|null
     */
    public $coverFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'cover_image'], 'default', 'value' => null],
            [['title', 'year', 'isbn'], 'required'],
            [['year'], 'integer'],
            [['description'], 'string'],
            [['title', 'cover_image'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            ['authorIds', 'each', 'rule' => ['integer']],
            ['coverFile', 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'webp', 'gif'], 'maxSize' => 5 * 1024 * 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->authorIds = $this->getAuthors()->select('id')->column();
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            // Текущие связи книги с авторами в БД
            $oldAuthorIds = BookAuthor::find()
                ->select('author_id')
                ->where(['book_id' => $this->id])
                ->column();

            // Новые выбранные авторы из формы
            $newAuthorIds = $this->authorIds ?: [];

            // Что удалить и что добавить
            $toDelete = array_diff($oldAuthorIds, $newAuthorIds);
            $toInsert = array_diff($newAuthorIds, $oldAuthorIds);

            if (!empty($toDelete)) {
                BookAuthor::deleteAll([
                    'book_id' => $this->id,
                    'author_id' => $toDelete,
                ]);
            }

            if (!empty($toInsert)) {
                $rows = [];
                foreach ($toInsert as $authorId) {
                    $rows[] = [$this->id, $authorId];
                }
                $db->createCommand()
                    ->batchInsert('book_author', ['book_id', 'author_id'], $rows)
                    ->execute();
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'year' => 'Year',
            'description' => 'Description',
            'isbn' => 'Isbn',
            'cover_image' => 'Cover Image',
        ];
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthor()
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id']);
    }

    public function getAuthors()
    {
        return $this->hasMany(Authors::class, ['id' => 'author_id'])
            ->via('bookAuthor');
    }

    /**
     * Возвращает список книг в формате id => title для форм.
     *
     * @return array
     */
    public static function getList(): array
    {
        return static::find()
            ->select(['title', 'id'])
            ->indexBy('id')
            ->column();
    }

}
