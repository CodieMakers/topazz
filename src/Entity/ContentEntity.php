<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Entity;


use Topazz\Data\Collections\ListInterface;
use Topazz\Database\Statement\Statement;
use Topazz\TopazzApplicationException;

abstract class ContentEntity extends Entity {

    const STATUS_PUBLISHED = 0;
    const STATUS_PRIVATE = 1;
    const STATUS_FOR_REVIEW = 2;

    protected static $authorsTable;
    protected static $authorsContentColumnName;

    public $status = self::STATUS_FOR_REVIEW;
    public $create_time;
    public $update_time;

    public $author_ids = [];

    public function __construct() {
        parent::__construct();
        if (!is_null($this->id)) {
            $this->refreshAuthors();
        }
    }

    public function refreshAuthors() {
        $this->author_ids = Statement::select('user_id')
            ->distinct()
            ->from(static::$authorsTable)
            ->where(static::$authorsContentColumnName, $this->id)
            ->prepare()->execute()->all()->map(function ($item) {
                return $item->user_id;
            })->toArray();
    }

    public function addAuthor(int $userId) {
        if (in_array($userId, $this->author_ids)) {
            throw new TopazzApplicationException("This user is already author of this content.");
        }
        $insertedRows = Statement::insert('user_id', static::$authorsContentColumnName)
            ->into(static::$authorsTable)
            ->values($userId, $this->id)->prepare()->execute()->count();
        if ($insertedRows !== 1) {
            throw new TopazzApplicationException("Some error occurred during adding author.");
        }
        $this->refreshAuthors();
    }

    public function removeAuthor(int $userId) {
        $result = Statement::delete(static::$authorsTable)
            ->where('user_id', $userId)
            ->where(static::$authorsContentColumnName, $this->id)
            ->prepare()->inTransaction()->execute();
        $deletedRows = $result->count();
        if ($deletedRows !== 1) {
            throw new TopazzApplicationException("Some error occurred during removing author.");
        }
        $result->commit();
        $this->refreshAuthors();
    }

    public static function published(): ListInterface {
        return static::find("status", self::STATUS_PUBLISHED);
    }
}