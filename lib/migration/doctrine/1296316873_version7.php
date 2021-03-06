<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version7 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('post_comment_attribute', 'post_comment_attribute_post_comment_id_post_comment_id', array(
             'name' => 'post_comment_attribute_post_comment_id_post_comment_id',
             'local' => 'post_comment_id',
             'foreign' => 'id',
             'foreignTable' => 'post_comment',
             'onUpdate' => '',
             'onDelete' => 'cascade',
             ));
        $this->addIndex('post_comment_attribute', 'post_comment_attribute_post_comment_id', array(
             'fields' => 
             array(
              0 => 'post_comment_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('post_comment_attribute', 'post_comment_attribute_post_comment_id_post_comment_id');
        $this->removeIndex('post_comment_attribute', 'post_comment_attribute_post_comment_id', array(
             'fields' => 
             array(
              0 => 'post_comment_id',
             ),
             ));
    }
}