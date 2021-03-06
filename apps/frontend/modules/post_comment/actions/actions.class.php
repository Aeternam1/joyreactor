<?

/**
 * post_comment actions.
 *
 * @package    jobeet
 * @subpackage post_comment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class post_commentActions extends sfActions {
    public function executeDelete(sfWebRequest $request) {
        $this->comment = Doctrine::getTable('PostComment')->find(array($request->getParameter('id')));
        $this->post = $this->comment->getPost();
        $this->forward404Unless($this->comment && !$this->comment->isOld() && $this->comment->getUser() == $this->getUser()->getGuardUser());
        $this->comment->delete();
        $this->redirect('post/show?id='.$this->post->getId());
    }

    /**
     * Executes create action
     *
     * @param sfRequest $request A request object
     */
    public function executeCreate(sfWebRequest $request) {
        $this->post = Doctrine::getTable('Post')->find(array($request->getParameter('post_id')));
        $this->parent = Doctrine::getTable('PostComment')->find(array($request->getParameter('parent_id')));
        if($this->getUser()->isAuthenticated() && $this->post) {
          $this->AddComment($request);
        }
        sfApplicationConfiguration::getActive()->loadHelpers('I18N');
        if(!$this->getUser()->isAuthenticated()){
            if($request->getParameter('noajax')) {
                return $this->forward("sfGuardAuth", "signin");
            }
            echo __("Залогиньтесь и попробуйте еще раз, вот Ваш комментарий").":<br/>\n".$request->getParameter('comment_text');
        }

        if($request->getParameter('noajax')) {
            if(isset($comment))
                $this->redirect('post/show?id='.$this->post->getId()."#comment".$comment->getId());
            else
                $this->redirect('post/show?id='.$this->post->getId());
        }
        if($this->parent)
            return $this->renderPartial('post_comment/commentList', array('comments' => $this->parent->getComments()));
        else
            return $this->renderPartial('post_comment/commentList', array('comments' => $this->post->getComments()));
    }

    private function AddComment(sfWebRequest $request)
    {
        $text = $request->getParameter('comment_text');
        if($request->isMethod("GET"))
          $text = urldecode($text);

        if($this->getRequestParameter('comment_picture_url')) {
            $filename = jrFileUploader::UploadRemote($request->getParameter('comment_picture_url'));
            if($text)
              $text .= "<br/>";
            $text .= "<img src='http://".$request->getHost()."/uploads/" . $filename . "' />";
        }

        if(!trim($text))
        {
          return;
        }

        sfApplicationConfiguration::getActive()->loadHelpers(array('Parse','Text','Tag', 'I18N','Url'));

        $user = $this->getUser()->getGuardUser();
        $comment = new PostComment();
        $comment->setUser($user);
        $comment->setPost($this->post);
        if($this->parent)
            $comment->setParent($this->parent);
        $comment->setComment(parsetext($text));
        $comment->setCommentOriginal($text);
        $comment->save();

        $this->curUser = $this->getUser()->getGuardUser();
        if($this->curUser) {
            Cookie::setCookie($this->curUser, "comments".$this->post->getId(), $this->post->getAllComments('count'), time() + 24 * 60 * 60);
            Cookie::setCookie($this->curUser, "comments".$this->post->getId()."Time", date("Y-m-d H:i:s"), time() + 24 * 60 * 60);
        }
    }
}
