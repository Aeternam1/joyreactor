<?php

/**
 * BaseFavoriteBlog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $blog_id
 * @property integer $value
 * @property sfGuardUser $User
 * @property Blog $Blog
 * 
 * @method integer      getId()      Returns the current record's "id" value
 * @method integer      getUserId()  Returns the current record's "user_id" value
 * @method integer      getBlogId()  Returns the current record's "blog_id" value
 * @method integer      getValue()   Returns the current record's "value" value
 * @method sfGuardUser  getUser()    Returns the current record's "User" value
 * @method Blog         getBlog()    Returns the current record's "Blog" value
 * @method FavoriteBlog setId()      Sets the current record's "id" value
 * @method FavoriteBlog setUserId()  Sets the current record's "user_id" value
 * @method FavoriteBlog setBlogId()  Sets the current record's "blog_id" value
 * @method FavoriteBlog setValue()   Sets the current record's "value" value
 * @method FavoriteBlog setUser()    Sets the current record's "User" value
 * @method FavoriteBlog setBlog()    Sets the current record's "Blog" value
 * 
 * @package    Empaty
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFavoriteBlog extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('favorite_blog');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('blog_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('value', 'integer', 1, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 1,
             'length' => 1,
             ));


        $this->index('blog_id', array(
             'fields' => 
             array(
              0 => 'blog_id',
             ),
             'type' => NULL,
             ));
        $this->index('user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             'type' => NULL,
             ));
        $this->index('value', array(
             'fields' => 
             array(
              0 => 'value',
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
        parent::setUp();
        $this->hasOne('sfGuardUser as User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $this->hasOne('Blog', array(
             'local' => 'Blog_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}