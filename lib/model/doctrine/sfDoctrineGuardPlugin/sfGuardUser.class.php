<?
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class sfGuardUser extends PluginsfGuardUser {
    public function isOnline() {
        return $this['last_login'] && time() - strtotime($this['last_login']) < sfConfig::get('app_user_online_period', 480);
    }

    public function getCookie($name) {
        return Cookie::getCookie($this, $name);
    }

    public function getLastStatus() {
        $query = Doctrine_Query::create()
            ->select('p.*')
            ->from('Post p')
            ->where('p.type != ?', "post")
            ->andWhere('p.user_id = ?', $this['id'])
            ->orderBy('p.created_at desc')
            ->limit(1)
            ->execute();
        if(count($query))
            return $query[0];
        else
            return null;
    }

    public function getFriend($user) {
        $query = Doctrine_Query::create()
            ->select('f.*')
            ->from('Friend f')
            ->where('f.user_id = ?', $this->getId())
            ->andWhere('f.friend_id = ?', $user->getId())
            ->execute();
        if(count($query))
            return $query[0];
        else
            return null;
    }

    public function hasFriend($user) {
        $query = Doctrine_Query::create()
            ->select('f.id')
            ->from('Friend f')
            ->where('f.user_id = ?', $this->getId())
            ->andWhere('f.friend_id = ?', $user->getId())
            ->execute();
        return count($query) > 0;
    }

    public function getHidden($post) {
        $query = Doctrine_Query::create()
            ->select('h.id')
            ->from('Hidden h')
            ->where('h.user_id = ?', $this->getId())
            ->andWhere('h.post_id = ?', $post->getId())
            ->execute();
        if(count($query))
            return $query[0];
        else
            return null;
    }

    public function getFavorite($post) {
        $query = Doctrine_Query::create()
            ->select('f.id')
            ->from('Favorite f')
            ->where('f.user_id = ?', $this->getId())
            ->andWhere('f.post_id = ?', $post->getId())
            ->execute();
        if(count($query))
            return $query[0];
        else
            return null;
    }

    public function getFavoriteBlog($blog) {
        $query = Doctrine_Query::create()
            ->select('f.id')
            ->from('FavoriteBlog f')
            ->where('f.user_id = ?', $this->getId())
            ->andWhere('f.blog_id = ?', $blog->getId())
            ->execute();
        if(count($query))
            return $query[0];
        else
            return null;
    }

    public function isHidden($post) {
        $hidden = $this->getHidden($post);
        $favBlog = 0;
        foreach($post->getBlogs() as $blog)
            if($this->getFavoriteBlogValue($blog) < 0) {
                $favBlog = -1;
                break;
            }
        return (($post->getRating() < sfConfig::get('app_post_worstpage_threshold') or $favBlog == -1) and (!$hidden or $hidden->getValue() > 0)) or ($hidden and $hidden->getValue() > 0);
    }

    public function isFavorite($post) {
        $query = Doctrine_Query::create()
            ->select('f.id')
            ->from('Favorite f')
            ->where('f.user_id = ?', $this->getId())
            ->andWhere('f.post_id = ?', $post->getId())
            ->execute();
        return count($query) > 0;
    }

    public function getFavoriteBlogValue($blog) {
        $fav = $this->getFavoriteBlog($blog);
        return $fav ? $fav->getValue() : 0;
    }

    public function inFriends($user) {
        $query = Doctrine_Query::create()
            ->select('f.id')
            ->from('Friend f')
            ->where('f.friend_id = ?', $this->getId())
            ->andWhere('f.user_id = ?', $user->getId())
            ->execute();
        return count($query) > 0;
    }

    public function getFriendsLine($page=1) {
        $query = Doctrine_Query::create()
            ->select('p.*')
            ->from('Post p, p.User u, u.InFriends f')
            ->where('f.user_id = ?', $this->getId())
            ->andWhere('p.type = ?', "post")
            ->orderBy('p.created_at desc');
        if($page !== 'count') {
            $query = new Doctrine_Pager($query,$page,sfConfig::get('app_posts_per_page', 10));
            return $query->execute();
        }
        else
            return $query->count();
    }

    public function getPersonalLine($page=1) {
        $query = Doctrine_Query::create()
        	->useResultCache(true, 300)
            ->select('p.*')
            ->from('Post p')
            ->leftJoin('p.User u')
            ->leftJoin('u.InFriends f')
            ->leftJoin('p.Blogs b')
            ->leftJoin('b.InFavorite fb')
            ->where('(p.rating  >= ? or f.user_id = ? or fb.value = ?)', array(sfConfig::get('app_post_mainpage_threshold'), $this->getId(), 1))
            ->andWhere("(fb.value is null or (fb.value != ? and fb.user_id = ?))", array(-1, $this->getId()))
            ->andWhere('p.type = ?', "post")
            ->orderBy('p.created_at desc');
        if($page !== 'count') {
            $query = new Doctrine_Pager($query,$page,sfConfig::get('app_posts_per_page', 10));
            return $query->execute();
        }
        else
            return $query->count();
    }

    public function getDiscussionLine($page=1) {
        $query = Doctrine_Query::create()
            ->select('p.*')
            ->from('Post p, p.Comments c')
            ->where('p.comments_count > 0')
            ->andWhere('p.type = ?', "post")
            ->andWhere('c.user_id != ?', $this['id'])
            ->orderBy('c.created_at desc');
        if($page !== 'count') {
            $query = new Doctrine_Pager($query,$page,sfConfig::get('app_posts_per_page', 10));
            return $query->execute();
        }
        else
            return $query->count();
    }

    public function getFriendsList($page=1) {
        $query = Doctrine_Query::create()
            ->select('u.*')
            ->from('sfGuardUser u, u.InFriends f')
            ->where('f.user_id = ?', $this->getId())
            ->orderBy('f.created_at desc');
        if($page !== 'count') {
            $query = new Doctrine_Pager($query,$page,sfConfig::get('app_posts_per_page', 10));
            return $query->execute();
        }
        else
            return $query->count();
    }

    public function getFavoriteBlogsList($value=1, $page=1) {
        $query = Doctrine_Query::create()
            ->select('b.*')
            ->from('Blog b, b.InFavorite f')
            ->where('f.user_id = ?', $this->getId())
            ->andWhere("f.value = ?", $value)
            ->orderBy('f.created_at desc');
        if($page !== 'count') {
            return $query->execute();
        }
        else
            return $query->count();
    }


    public function getFavoriteLine($page=1) {
        $query = Doctrine_Query::create()
            ->select('p.*')
            ->from('Post p, p.InFavorite u')
            ->where('u.user_id = ?', $this->getId())
            ->andWhere('p.type = ?', "post")
            ->orderBy('p.created_at desc');
        if(sfContext::getInstance()->getUser()->isAnonymous())
            $query->addWhere('p.rating  >= ?', sfConfig::get('app_post_worstpage_threshold'));
        if($page !== 'count') {
            $query = new Doctrine_Pager($query,$page,sfConfig::get('app_posts_per_page', 10));
            return $query->execute();
        }
        else
            return $query->count();
    }

    public function getJoyPlot() {
        $query = Doctrine_Query::create()
            ->useResultCache(true, 1800)
            ->select('avg(p.mood), date(p.created_at)')
            ->from('Post p')
            ->where('p.user_id = ?', $this->getId())
            ->andWhere('datediff(curdate(), date(p.created_at)) < 30')
            ->andWhere('p.type = ?', "post")
            ->orderBy('p.created_at')
            ->groupBy('date(p.created_at)')
            ->execute();
        $res = '[';
        foreach($query as $row) {
            $jstime = number_format(strtotime($row['date'])*1000, 0, '', '');
            $jsmood = $row['avg'];
            $res .= '['.$jstime.','.$jsmood.'],';
        }
        return $res.']';
    }

    public function getLine($page=1) {
        $query = Doctrine_Query::create()
            ->select('p.*')
            ->from('Post p')
            ->where('p.user_id = ?', $this->getId())
            ->andWhere('p.type = ?', "post")
            ->orderBy('p.created_at desc');
        if(sfContext::getInstance()->getUser()->isAnonymous())
            $query->addWhere("p.rating >= 0");
        if($page !== 'count') {
            $query = new Doctrine_Pager($query,$page,sfConfig::get('app_posts_per_page', 10));
            return $query->execute();
        }
        else
            return $query->count();
    }

    public function getDateLine($date, $page=1) {
        $query = Doctrine_Query::create()
            ->select('p.*')
            ->from('Post p')
            ->where('p.user_id = ?', $this->getId())
            ->andWhere('p.type = ?', "post")
            ->andWhere('date(p.created_at) = date(?)', $date)
            ->orderBy('p.created_at desc');
        if(sfContext::getInstance()->getUser()->isAnonymous())
            $query->addWhere('p.rating  >= ?', sfConfig::get('app_post_worstpage_threshold'));
        if($page !== 'count') {
            $query = new Doctrine_Pager($query,$page,sfConfig::get('app_posts_per_page', 10));
            return $query->execute();
        }
        else
            return $query->count();
    }

    public function getAveMood() {
        $query = Doctrine_Query::create()
            ->useResultCache(true, 3600)
            ->select('avg(p.mood)')
            ->from('Post p')
            ->where('p.user_id = ?', $this->getId())
            ->andWhere('datediff(curdate(), date(p.created_at)) < 7')
            ->andWhere('p.type = ?', "post")
            ->groupBy('p.user_id')
            ->execute();
        sfApplicationConfiguration::getActive()->loadHelpers('I18N');
        if(count($query)) {
            $query = $query[0];
            if($query['avg'] >= 0.5)
                return __('отличное');
            elseif($query['avg'] >= 0.2 && $query['avg'] < 0.5)
                return __('хорошее');
            elseif($query['avg'] >= -0.2 && $query['avg'] < 0.2)
                return __('нормальное');
            elseif($query['avg'] >= -0.5 && $query['avg'] < -0.2)
                return __('так себе');
            elseif($query['avg'] < -0.5)
                return __('плохое');
        }
        else
            return __('нормальное');
    }

    public function getTags() {
        sfApplicationConfiguration::getActive()->loadHelpers(array('I18N'));
        $query = Doctrine_Query::create()
            ->select('b.tag, count(p.id) cnt')
            ->from('Blog b, b.Posts p')
            ->where('p.type = ?', "post")
            ->andWhere('p.user_id = ?', $this["id"])
            ->orderBy('cnt desc')
            ->groupBy('b.id')
            ->limit(20)
            ->execute();
        $res = '';
        foreach($query as $row) {
            if($row['cnt'] > 0)
                $res .= '{tag: "'.$row['tag'].'", count: '.$row['cnt'].'},';
        }
        return substr($res, 0, strlen($res)-1);
    }

    //  *****Static Methods*****
    public static function getAdminUser() {
        return self::getUserByUsername("admin");
    }

    public static function getUserByUsername($username) {
        $query = Doctrine_Query::create()
            ->select('u.id')
            ->from('sfGuardUser u')
            ->where('u.username = ?', $username)
            ->execute();
        if(count($query))
            return $query[0];
        else
            return null;
    }

    public static function getList($page=1) {
        $query = Doctrine_Query::create()
            ->select('u.*')
            ->from('sfGuardUser u')
            ->orderBy('u.created_at desc');
        if($page !== 'count') {
            $query = new Doctrine_Pager($query,$page,sfConfig::get('app_users_per_page', 10));
            return $query->execute();
        }
        else
            return $query->count();
    }

    public static function getTopList($page=1) {
        $query = Doctrine_Query::create()
            ->useResultCache(true, 1800)
            ->select('u.*')
            ->from('sfGuardUser u, u.Profile p')
            ->orderBy('p.rating desc');
        if($page !== 'count') {
            $query = new Doctrine_Pager($query,$page,sfConfig::get('app_users_per_page', 10));
            return $query->execute();
        }
        else
            return $query->count();
    }

    public static function getOnlineList($page=1) {
        $query = Doctrine_Query::create()
            ->select('u.*')
            ->from('sfGuardUser u, u.Profile p')
            ->where('time_to_sec(timediff(now(), u.last_login)) < ?', sfConfig::get('app_user_online_period', 480))
            ->orderBy('time_to_sec(timediff(now(), u.last_login)) asc');
        if($page !== 'count') {
            $query = new Doctrine_Pager($query,$page,sfConfig::get('app_users_per_page', 10));
            return $query->execute();
        }
        else
            return $query->count();
    }
}
