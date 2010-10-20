<?php

/**
 * BasePostComment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $post_id
 * @property integer $parent_id
 * @property string $comment
 * @property integer $power
 * @property boolean $isNew
 * @property sfGuardUser $User
 * @property Post $Post
 * @property PostComment $Parent
 * @property Doctrine_Collection $Comments
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 5845 2009-06-09 07:36:57Z jwage $
 */
abstract class BasePostComment extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('post_comment');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => '4',
             ));
        $this->hasColumn('post_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => '4',
             ));
        $this->hasColumn('parent_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => '4',
             ));
        $this->hasColumn('comment', 'string', 2147483647, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '2147483647',
             ));
        $this->hasColumn('power', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             'length' => '4',
             ));
        $this->hasColumn('isNew', 'boolean', null, array(
             'type' => 'boolean',
             'default' => true,
             ));


        $this->index('user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             'type' => NULL,
             ));
        $this->index('post_id', array(
             'fields' => 
             array(
              0 => 'post_id',
             ),
             'type' => NULL,
             ));
        $this->index('parent_id', array(
             'fields' => 
             array(
              0 => 'parent_id',
             ),
             'type' => NULL,
             ));
        $this->index('isNew', array(
             'fields' => 
             array(
              0 => 'isNew',
             ),
             'type' => NULL,
             ));
        $this->index('created_at', array(
             'fields' => 
             array(
              0 => 'created_at',
             ),
             'type' => NULL,
             ));
        $this->index('updated_at', array(
             'fields' => 
             array(
              0 => 'updated_at',
             ),
             'type' => NULL,
             ));
        $this->option('type', 'MYISAM');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        $this->hasOne('sfGuardUser as User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $this->hasOne('Post', array(
             'local' => 'post_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $this->hasOne('PostComment as Parent', array(
             'local' => 'parent_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $this->hasMany('PostComment as Comments', array(
             'local' => 'id',
             'foreign' => 'parent_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}