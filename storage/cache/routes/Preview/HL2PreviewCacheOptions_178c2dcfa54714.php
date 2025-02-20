<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2PreviewCacheOptions_178c2dcfa54714
{
    /**
    * @internal
    */
    private static array $data =[
       [
          'a' => '/',
          'k' => 1,
          'f' => '/',
          'i' => 'home',
          's' => 1,
      ],
       [
          'a' => 'questions',
          'k' => 4,
          'f' => 'questions',
          'i' => 'main.questions',
          's' => 1,
      ],
       [
          'a' => 'posts',
          'k' => 7,
          'f' => 'posts',
          'i' => 'main.posts',
          's' => 1,
      ],
       [
          'a' => 'all',
          'k' => 10,
          'f' => 'all',
          'i' => 'main.all',
          's' => 1,
      ],
       [
          'a' => 'blogs',
          'k' => 13,
          'f' => 'blogs',
          'i' => 'blogs.all',
          's' => 1,
      ],
       [
          'a' => 'blogs/new',
          'k' => 16,
          'f' => 'blogs',
          'i' => 'blogs.new',
      ],
       [
          'a' => 'topics',
          'k' => 19,
          'f' => 'topics',
          'i' => 'topics.all',
          's' => 1,
      ],
       [
          'a' => 'topics/new',
          'k' => 22,
          'f' => 'topics',
          'i' => 'topics.new',
      ],
       [
          'a' => 'users',
          'k' => 25,
          'f' => 'users',
          'i' => 'users.all',
          's' => 1,
      ],
       [
          'a' => 'users/new',
          'k' => 28,
          'f' => 'users',
          'i' => 'users.new',
      ],
       [
          'a' => 'comments',
          'k' => 31,
          'f' => 'comments',
          'i' => 'comments',
          's' => 1,
      ],
       [
          'a' => 'backend/upload/{type}/{id}',
          'k' => 36,
          'f' => 'backend',
          'w' =>  [
              'type' => '[a-z-]+',
              'id' => '[0-9]+',
          ],
          'd' => 1,
      ],
       [
          'a' => 'logout',
          'k' => 39,
          'f' => 'logout',
          'i' => 'logout',
          's' => 1,
      ],
       [
          'a' => 'favorites',
          'k' => 42,
          'f' => 'favorites',
          'i' => 'favorites',
          's' => 1,
      ],
       [
          'a' => 'search/select/{type}',
          'k' => 45,
          'f' => 'search',
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'd' => 1,
      ],
       [
          'a' => 'post/grabtitle',
          'k' => 48,
          'f' => 'post',
      ],
       [
          'a' => 'status/action',
          'k' => 50,
          'f' => 'status',
      ],
       [
          'a' => 'blogs/my',
          'k' => 52,
          'f' => 'blogs',
          'i' => 'blogs.my',
      ],
       [
          'a' => 'topics/my',
          'k' => 55,
          'f' => 'topics',
          'i' => 'topics.my',
      ],
       [
          'a' => 'post/scroll/{type}',
          'k' => 58,
          'f' => 'post',
          'd' => 1,
      ],
       [
          'a' => 'setting',
          'k' => 60,
          'f' => 'setting',
          'i' => 'setting',
          's' => 1,
      ],
       [
          'a' => 'setting/avatar',
          'k' => 63,
          'f' => 'setting',
          'w' =>  [
              'type' => '[a-z_]+',
          ],
          'i' => 'setting.avatar',
      ],
       [
          'a' => 'setting/ignored',
          'k' => 67,
          'f' => 'setting',
          'w' =>  [
              'type' => '[a-z_]+',
          ],
          'i' => 'setting.ignored',
      ],
       [
          'a' => 'setting/security',
          'k' => 71,
          'f' => 'setting',
          'w' =>  [
              'type' => '[a-z_]+',
          ],
          'i' => 'setting.security',
      ],
       [
          'a' => 'setting/notifications',
          'k' => 75,
          'f' => 'setting',
          'w' =>  [
              'type' => '[a-z_]+',
          ],
          'i' => 'setting.notification',
      ],
       [
          'a' => 'setting/preferences',
          'k' => 79,
          'f' => 'setting',
          'w' =>  [
              'type' => '[a-z_]+',
          ],
          'i' => 'setting.preferences',
      ],
       [
          'a' => 'setting/notifications',
          'k' => 83,
          'f' => 'setting',
          'w' =>  [
              'type' => '[a-z_]+',
          ],
          'i' => 'setting.deletion',
      ],
       [
          'a' => 'messages',
          'k' => 87,
          'f' => 'messages',
          'i' => 'messages',
          's' => 1,
      ],
       [
          'a' => 'messages/{id}',
          'k' => 90,
          'f' => 'messages',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'dialogues',
          'd' => 1,
      ],
       [
          'a' => 'messages/@{login}',
          'k' => 94,
          'f' => 'messages',
          'w' =>  [
              'login' => '[A-Za-z0-9-]+',
          ],
          'i' => 'send.messages',
          'd' => 1,
      ],
       [
          'a' => 'notifications',
          'k' => 98,
          'f' => 'notifications',
          'i' => 'notifications',
          's' => 1,
      ],
       [
          'a' => 'notification/{id}',
          'k' => 101,
          'f' => 'notification',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'notif.read',
          'd' => 1,
      ],
       [
          'a' => 'notifications/delete',
          'k' => 105,
          'f' => 'notifications',
          'i' => 'notif.remove',
      ],
       [
          'a' => 'invitations',
          'k' => 108,
          'f' => 'invitations',
          'i' => 'invitations',
          's' => 1,
      ],
       [
          'a' => 'read',
          'k' => 111,
          'f' => 'read',
          'i' => 'read',
          's' => 1,
      ],
       [
          'a' => 'drafts',
          'k' => 114,
          'f' => 'drafts',
          'i' => 'drafts',
          's' => 1,
      ],
       [
          'a' => 'polls',
          'k' => 117,
          'f' => 'polls',
          'i' => 'polls',
          's' => 1,
      ],
       [
          'a' => 'poll/{id}',
          'k' => 120,
          'f' => 'poll',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'poll',
          'd' => 1,
      ],
       [
          'a' => 'subscribed',
          'k' => 124,
          'f' => 'subscribed',
          'i' => 'subscribed',
          's' => 1,
      ],
       [
          'a' => 'favorites/folders',
          'k' => 127,
          'f' => 'favorites',
          'i' => 'favorites.folders',
      ],
       [
          'a' => 'favorites/folders/{id}',
          'k' => 130,
          'f' => 'favorites',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'favorites.folder.id',
          'd' => 1,
      ],
       [
          'a' => 'add/post',
          'k' => 134,
          'f' => 'add',
          'i' => 'post.form.add',
      ],
       [
          'a' => 'add/post/{facet_id}',
          'k' => 137,
          'f' => 'add',
          'w' =>  [
              'facet_id' => '[0-9]+',
          ],
          'd' => 1,
      ],
       [
          'a' => 'add/poll',
          'k' => 140,
          'f' => 'add',
          'i' => 'poll.form.add',
      ],
       [
          'a' => 'add/facet/{type}',
          'k' => 143,
          'f' => 'add',
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'facet.form.add',
          'd' => 1,
      ],
       [
          'a' => 'edit/post/{id}',
          'k' => 147,
          'f' => 'edit',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'post.form.edit',
          'd' => 1,
      ],
       [
          'a' => 'edit/page/{id}',
          'k' => 151,
          'f' => 'edit',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'page.form.edit',
          'd' => 1,
      ],
       [
          'a' => 'edit/comment/{id}',
          'k' => 155,
          'f' => 'edit',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'comment.form.edit',
          'd' => 1,
      ],
       [
          'a' => 'edit/poll/{id}',
          'k' => 159,
          'f' => 'edit',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'poll.form.edit',
          'd' => 1,
      ],
       [
          'a' => 'edit/facet/{type}/{id}',
          'k' => 163,
          'f' => 'edit',
          'w' =>  [
              'type' => '[a-z]+',
              'id' => '[0-9]+',
          ],
          'i' => 'facet.form.edit',
          'd' => 1,
      ],
       [
          'a' => 'edit/facet/logo/{type}/{id}',
          'k' => 167,
          'f' => 'edit',
          'w' =>  [
              'type' => '[a-z]+',
              'id' => '[0-9]+',
          ],
          'i' => 'facet.form.logo.edit',
          'd' => 1,
      ],
       [
          'a' => 'team/edit/{type}/{id}',
          'k' => 171,
          'f' => 'team',
          'w' =>  [
              'type' => '[a-z]+',
              'id' => '[0-9]+',
          ],
          'i' => 'team.form.edit',
          'd' => 1,
      ],
       [
          'a' => 'redirect/facet/{id}',
          'k' => 175,
          'f' => 'redirect',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'redirect.facet',
          'd' => 1,
      ],
       [
          'a' => 'post/img/{id}/remove',
          'k' => 179,
          'f' => 'post',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'delete.post.cover',
          'd' => 1,
      ],
       [
          'a' => 'cover/img/{id}/remove',
          'k' => 183,
          'f' => 'cover',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'delete.user.cover',
          'd' => 1,
      ],
       [
          'a' => 'favorite',
          'k' => 189,
          'f' => 'favorite',
          'p' =>  [
              'CSRF',
          ],
          's' => 1,
      ],
       [
          'a' => 'votes',
          'k' => 191,
          'f' => 'votes',
          'p' =>  [
              'CSRF',
          ],
          's' => 1,
      ],
       [
          'a' => 'flag/repost',
          'k' => 193,
          'f' => 'flag',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'post/profile',
          'k' => 195,
          'f' => 'post',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'post/recommend',
          'k' => 197,
          'f' => 'post',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'folder/content/del',
          'k' => 199,
          'f' => 'folder',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'folder/del',
          'k' => 201,
          'f' => 'folder',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'folder/content/save',
          'k' => 203,
          'f' => 'folder',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'focus',
          'k' => 205,
          'f' => 'focus',
          'p' =>  [
              'CSRF',
          ],
          's' => 1,
      ],
       [
          'a' => 'post/recommend',
          'k' => 207,
          'f' => 'post',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'ignored',
          'k' => 209,
          'f' => 'ignored',
          'p' =>  [
              'CSRF',
          ],
          's' => 1,
      ],
       [
          'a' => 'best',
          'k' => 211,
          'f' => 'best',
          'p' =>  [
              'CSRF',
          ],
          's' => 1,
      ],
       [
          'a' => 'poll/option/del',
          'k' => 213,
          'f' => 'poll',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'poll',
          'k' => 215,
          'f' => 'poll',
          'p' =>  [
              'CSRF',
          ],
          's' => 1,
      ],
       [
          'a' => 'new/email',
          'k' => 217,
          'f' => 'new',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'notif',
          'k' => 220,
          'f' => 'notif',
          's' => 1,
      ],
       [
          'a' => 'device',
          'k' => 222,
          'f' => 'device',
          's' => 1,
      ],
       [
          'a' => 'user/edit/profile',
          'k' => 227,
          'f' => 'user',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'setting.edit.profile',
      ],
       [
          'a' => 'user/edit/avatar',
          'k' => 231,
          'f' => 'user',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'setting.edit.avatar',
      ],
       [
          'a' => 'user/edit/security',
          'k' => 235,
          'f' => 'user',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'setting.edit.security',
      ],
       [
          'a' => 'user/edit/preferences',
          'k' => 239,
          'f' => 'user',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'setting.edit.preferences',
      ],
       [
          'a' => 'user/edit/notification',
          'k' => 243,
          'f' => 'user',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'setting.edit.notification',
      ],
       [
          'a' => 'team/edit/{type}/{id}',
          'k' => 247,
          'f' => 'team',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
              'id' => '[0-9]+',
          ],
          'i' => 'team.edit',
          'd' => 1,
      ],
       [
          'a' => 'edit/content/{type}',
          'k' => 251,
          'f' => 'edit',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'edit.post',
          'd' => 1,
      ],
       [
          'a' => 'edit/comment',
          'k' => 255,
          'f' => 'edit',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'edit.comment',
      ],
       [
          'a' => 'edit/facet/{type}',
          'k' => 258,
          'f' => 'edit',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'edit.facet',
          'd' => 1,
      ],
       [
          'a' => 'edit/facet/logo/{type}/{facet_id}',
          'k' => 262,
          'f' => 'edit',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
              'facet_id' => '[0-9]+',
          ],
          'i' => 'edit.logo.facet',
          'd' => 1,
      ],
       [
          'a' => 'edit/poll',
          'k' => 266,
          'f' => 'edit',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'edit.poll',
      ],
       [
          'a' => 'edit/message',
          'k' => 269,
          'f' => 'edit',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'edit.message',
      ],
       [
          'a' => 'add/folder',
          'k' => 272,
          'f' => 'add',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'add.folder',
      ],
       [
          'a' => 'add/content/{type}',
          'k' => 275,
          'f' => 'add',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'add.post',
          'd' => 1,
      ],
       [
          'a' => 'add/comment',
          'k' => 279,
          'f' => 'add',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'add.comment',
      ],
       [
          'a' => 'add/facet/{type}',
          'k' => 282,
          'f' => 'add',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'add.facet',
          'd' => 1,
      ],
       [
          'a' => 'add/poll',
          'k' => 286,
          'f' => 'add',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'add.poll',
      ],
       [
          'a' => 'add/message',
          'k' => 289,
          'f' => 'add',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'add.message',
      ],
       [
          'a' => 'add/invitation',
          'k' => 292,
          'f' => 'add',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'add.invitation',
      ],
       [
          'a' => 'recover/send',
          'k' => 301,
          'f' => 'recover',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'recover.send',
      ],
       [
          'a' => 'recover/send/pass',
          'k' => 304,
          'f' => 'recover',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'new.pass',
      ],
       [
          'a' => 'register/add',
          'k' => 307,
          'f' => 'register',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'register.add',
      ],
       [
          'a' => 'login',
          'k' => 310,
          'f' => 'login',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'authorization',
          's' => 1,
      ],
       [
          'a' => 'invite',
          'k' => 314,
          'f' => 'invite',
          'i' => 'invite',
          's' => 1,
      ],
       [
          'a' => 'register',
          'k' => 317,
          'f' => 'register',
          'i' => 'register',
          's' => 1,
      ],
       [
          'a' => 'register/invite/{code}',
          'k' => 320,
          'f' => 'register',
          'w' =>  [
              'code' => '[a-z0-9-]+',
          ],
          'i' => 'invite.reg',
          'd' => 1,
      ],
       [
          'a' => 'recover',
          'k' => 324,
          'f' => 'recover',
          'i' => 'recover',
          's' => 1,
      ],
       [
          'a' => 'recover/remind/{code}',
          'k' => 327,
          'f' => 'recover',
          'w' =>  [
              'code' => '[A-Za-z0-9-]+',
          ],
          'i' => 'recover.code',
          'd' => 1,
      ],
       [
          'a' => 'email/activate/{code}',
          'k' => 331,
          'f' => 'email',
          'w' =>  [
              'code' => '[A-Za-z0-9-]+',
          ],
          'i' => 'activate.code',
          'd' => 1,
      ],
       [
          'a' => 'login',
          'k' => 335,
          'f' => 'login',
          'i' => 'login',
          's' => 1,
      ],
       [
          'a' => 'activatingform/addcomment',
          'k' => 341,
          'f' => 'activatingform',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'activatingform/editmessage',
          'k' => 343,
          'f' => 'activatingform',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'activatingnatifpopup',
          'k' => 345,
          'f' => 'activatingnatifpopup',
          'p' =>  [
              'CSRF',
          ],
          's' => 1,
      ],
       [
          'a' => 'domain/{domain}',
          'k' => 348,
          'f' => 'domain',
          'w' =>  [
              'domain' => '[a-z0-9-.]+',
          ],
          'i' => 'domain',
          'd' => 1,
      ],
       [
          'a' => 'post/{id}',
          'k' => 352,
          'f' => 'post',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'post.id',
          'd' => 1,
      ],
       [
          'a' => 'post/{id}/{slug}',
          'k' => 356,
          'f' => 'post',
          'w' =>  [
              'id' => '[0-9]+',
              'slug' => '[A-Za-z0-9-_]+',
          ],
          'i' => 'post',
          'd' => 1,
      ],
       [
          'a' => '{facet_slug}/article/{slug}',
          'k' => 360,
          'f' => '{facet_slug}',
          'w' =>  [
              'facet_slug' => '[A-Za-z0-9-_]+',
              'slug' => '[A-Za-z0-9-_]+',
          ],
          'i' => 'facet.article',
          'd' => 1,
          'n' => 1,
      ],
       [
          'a' => '@{login}',
          'k' => 364,
          'f' => '@{login}',
          'w' =>  [
              'login' => '[A-Za-z0-9-]+',
          ],
          'i' => 'profile',
          'd' => 1,
          's' => 1,
          'n' => 1,
      ],
       [
          'a' => '@{login}/posts',
          'k' => 368,
          'f' => '@{login}',
          'w' =>  [
              'login' => '[A-Za-z0-9-]+',
          ],
          'i' => 'profile.posts',
          'd' => 1,
          'n' => 1,
      ],
       [
          'a' => '@{login}/comments',
          'k' => 372,
          'f' => '@{login}',
          'w' =>  [
              'login' => '[A-Za-z0-9-]+',
          ],
          'i' => 'profile.comments',
          'd' => 1,
          'n' => 1,
      ],
       [
          'a' => 'topic/{slug}/recommend',
          'k' => 376,
          'f' => 'topic',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'topic.recommend',
          'd' => 1,
      ],
       [
          'a' => 'topic/{slug}/questions',
          'k' => 380,
          'f' => 'topic',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'topic.questions',
          'd' => 1,
      ],
       [
          'a' => 'topic/{slug}/top',
          'k' => 384,
          'f' => 'topic',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'topic.top',
          'd' => 1,
      ],
       [
          'a' => 'topic/{slug}/posts',
          'k' => 388,
          'f' => 'topic',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'topic.posts',
          'd' => 1,
      ],
       [
          'a' => 'topic/{slug}/info',
          'k' => 392,
          'f' => 'topic',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'topic.info',
          'd' => 1,
      ],
       [
          'a' => 'topic/{slug}/writers',
          'k' => 396,
          'f' => 'topic',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'topic.writers',
          'd' => 1,
      ],
       [
          'a' => 'topic/{slug}',
          'k' => 400,
          'f' => 'topic',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'topic',
          'd' => 1,
      ],
       [
          'a' => 'blog/{slug}/questions',
          'k' => 404,
          'f' => 'blog',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'blog.questions',
          'd' => 1,
      ],
       [
          'a' => 'blog/{slug}/posts',
          'k' => 408,
          'f' => 'blog',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'blog.posts',
          'd' => 1,
      ],
       [
          'a' => 'blog/{slug}/read',
          'k' => 412,
          'f' => 'blog',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'blog.read',
          'd' => 1,
      ],
       [
          'a' => 'blog/{slug}',
          'k' => 416,
          'f' => 'blog',
          'w' =>  [
              'slug' => '[a-z0-9-]+',
          ],
          'i' => 'blog',
          'd' => 1,
      ],
       [
          'a' => 'sitemap.xml',
          'k' => 420,
          'f' => 'sitemap.xml',
          's' => 1,
      ],
       [
          'a' => 'rss/all/posts',
          'k' => 422,
          'f' => 'rss',
      ],
       [
          'a' => 'turbo-feed/topic/{slug}',
          'k' => 424,
          'f' => 'turbo-feed',
          'w' =>  [
              'slug' => '[A-Za-z0-9-]+',
          ],
          'd' => 1,
      ],
       [
          'a' => 'rss-feed/topic/{slug}',
          'k' => 427,
          'f' => 'rss-feed',
          'w' =>  [
              'slug' => '[A-Za-z0-9-]+',
          ],
          'd' => 1,
      ],
       [
          'a' => 'og-image/{id}',
          'k' => 430,
          'f' => 'og-image',
          'w' =>  [
              'id' => '[0-9-]+',
          ],
          'i' => 'og.image',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin',
          'k' => 437,
          'f' => 'mod',
          'i' => 'admin',
      ],
       [
          'a' => 'mod/admin/test/mail',
          'k' => 440,
          'f' => 'mod',
          'i' => 'admin.test.mail',
      ],
       [
          'a' => 'mod/admin/user/ban',
          'k' => 443,
          'f' => 'mod',
      ],
       [
          'a' => 'mod/admin/word/ban',
          'k' => 445,
          'f' => 'mod',
      ],
       [
          'a' => 'mod/admin/audit/status',
          'k' => 447,
          'f' => 'mod',
      ],
       [
          'a' => 'mod/admin/reports/saw',
          'k' => 449,
          'f' => 'mod',
      ],
       [
          'a' => 'mod/admin/topic/ban',
          'k' => 451,
          'f' => 'mod',
      ],
       [
          'a' => 'mod/admin/badge/remove',
          'k' => 453,
          'f' => 'mod',
      ],
       [
          'a' => 'mod/admin/manual/update',
          'k' => 455,
          'f' => 'mod',
      ],
       [
          'a' => 'mod/admin/badge/user/create',
          'k' => 459,
          'f' => 'mod',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'admin.user.badge.create',
      ],
       [
          'a' => 'mod/admin/badge/create',
          'k' => 462,
          'f' => 'mod',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'admin.badge.create',
      ],
       [
          'a' => 'mod/admin/badge/edit/{id}',
          'k' => 465,
          'f' => 'mod',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'admin.badge.edit',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/word/create',
          'k' => 469,
          'f' => 'mod',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'admin.word.create',
      ],
       [
          'a' => 'mod/admin/user/edit/{id}',
          'k' => 472,
          'f' => 'mod',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'admin.user.edit',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/setting/edit',
          'k' => 476,
          'f' => 'mod',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'admin.setting.edit',
      ],
       [
          'a' => 'mod/admin/users/search/go',
          'k' => 479,
          'f' => 'mod',
          'p' =>  [
              'CSRF',
          ],
          'w' =>  [
              'type' => '[a-zA-Z0-9]+',
          ],
          'i' => 'admin.user.search',
      ],
       [
          'a' => 'mod/admin/users',
          'k' => 484,
          'f' => 'mod',
          'i' => 'admin.users',
      ],
       [
          'a' => 'mod/admin/users/ban',
          'k' => 487,
          'f' => 'mod',
          'i' => 'admin.users.ban',
      ],
       [
          'a' => 'mod/admin/users/search',
          'k' => 490,
          'f' => 'mod',
          'i' => 'admin.users.search',
      ],
       [
          'a' => 'mod/admin/facets',
          'k' => 493,
          'f' => 'mod',
          'i' => 'admin.facets.all',
      ],
       [
          'a' => 'mod/admin/tools',
          'k' => 496,
          'f' => 'mod',
          'i' => 'admin.tools',
      ],
       [
          'a' => 'mod/admin/setting',
          'k' => 499,
          'f' => 'mod',
          'i' => 'admin.settings.general',
      ],
       [
          'a' => 'mod/admin/setting/interface',
          'k' => 502,
          'f' => 'mod',
          'i' => 'admin.settings.interface',
      ],
       [
          'a' => 'mod/admin/setting/advertising',
          'k' => 505,
          'f' => 'mod',
          'i' => 'admin.settings.advertising',
      ],
       [
          'a' => 'mod/admin/edit/comment/transfer/{id}',
          'k' => 508,
          'f' => 'mod',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'admin.comment.transfer.form.edit',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/audits',
          'k' => 512,
          'f' => 'mod',
          'i' => 'admin.audits',
      ],
       [
          'a' => 'mod/admin/audits/approved',
          'k' => 515,
          'f' => 'mod',
          'i' => 'admin.audits.ban',
      ],
       [
          'a' => 'mod/admin/report',
          'k' => 518,
          'f' => 'mod',
          'i' => 'admin.reports',
      ],
       [
          'a' => 'mod/admin/invitations',
          'k' => 521,
          'f' => 'mod',
          'i' => 'admin.invitations',
      ],
       [
          'a' => 'mod/admin/logs/search',
          'k' => 524,
          'f' => 'mod',
          'i' => 'admin.logs.search',
      ],
       [
          'a' => 'mod/admin/logs',
          'k' => 527,
          'f' => 'mod',
          'i' => 'admin.logs',
      ],
       [
          'a' => 'mod/admin/words',
          'k' => 530,
          'f' => 'mod',
          'i' => 'admin.words',
      ],
       [
          'a' => 'mod/admin/badges',
          'k' => 533,
          'f' => 'mod',
          'i' => 'admin.badges',
      ],
       [
          'a' => 'mod/admin/css',
          'k' => 536,
          'f' => 'mod',
          'i' => 'admin.css',
      ],
       [
          'a' => 'mod/admin/words/add',
          'k' => 539,
          'f' => 'mod',
          'i' => 'words.add',
      ],
       [
          'a' => 'mod/admin/users/{id}/edit',
          'k' => 542,
          'f' => 'mod',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'admin.user.edit.form',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/users/{id}/history',
          'k' => 546,
          'f' => 'mod',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'admin.user.history',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/logip/{item}',
          'k' => 550,
          'f' => 'mod',
          'w' =>  [
              'item' => '[0-9].+',
          ],
          'i' => 'admin.logip',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/regip/{item}',
          'k' => 554,
          'f' => 'mod',
          'w' =>  [
              'item' => '[0-9].+',
          ],
          'i' => 'admin.regip',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/deviceid/{item}',
          'k' => 558,
          'f' => 'mod',
          'w' =>  [
              'item' => '[0-9].+',
          ],
          'i' => 'admin.device',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/badges/add',
          'k' => 562,
          'f' => 'mod',
          'i' => 'admin.badges.add',
      ],
       [
          'a' => 'mod/admin/badges/{id}/edit',
          'k' => 565,
          'f' => 'mod',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'admin.badges.edit',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/badges/user/add/{id}',
          'k' => 569,
          'f' => 'mod',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'admin.badges.user.add',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/facets/{type}',
          'k' => 573,
          'f' => 'mod',
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'admin.facets.type',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/facets/ban/{type}',
          'k' => 577,
          'f' => 'mod',
          'w' =>  [
              'type' => '[a-z]+',
          ],
          'i' => 'admin.facets.ban.type',
          'd' => 1,
      ],
       [
          'a' => 'mod/admin/deleted',
          'k' => 581,
          'f' => 'mod',
          'i' => 'main.deleted',
      ],
       [
          'a' => 'mod/admin/comments/deleted',
          'k' => 584,
          'f' => 'mod',
          'i' => 'comments.deleted',
      ],
       [
          'a' => 'web',
          'k' => 588,
          'f' => 'web',
          'i' => 'web',
          's' => 1,
      ],
       [
          'a' => 'web/dir/{sort}/{slug}',
          'k' => 591,
          'f' => 'web',
          'i' => 'category',
          'd' => 1,
      ],
       [
          'a' => 'web/website/{id}/{slug?}',
          'k' => 594,
          'f' => 'web',
          'w' =>  [
              'id' => '[0-9]+',
              'slug' => '[a-z0-9-.]+',
          ],
          'i' => 'website',
          'd' => 1,
          'v' => 1,
      ],
       [
          'a' => 'search/web/url',
          'k' => 598,
          'f' => 'search',
      ],
       [
          'a' => 'add/item/{id?}',
          'k' => 600,
          'f' => 'add',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'item.form.add',
          'd' => 1,
          'v' => 1,
      ],
       [
          'a' => 'add/category',
          'k' => 604,
          'f' => 'add',
          'i' => 'category.form.add',
      ],
       [
          'a' => 'edit/item/{id}',
          'k' => 607,
          'f' => 'edit',
          'w' =>  [
              'id' => '[0-9]+',
          ],
          'i' => 'item.form.edit',
          'd' => 1,
      ],
       [
          'a' => 'add/reply',
          'k' => 613,
          'f' => 'add',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'add.reply',
      ],
       [
          'a' => 'add/item',
          'k' => 616,
          'f' => 'add',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'add.item',
      ],
       [
          'a' => 'edit/item',
          'k' => 619,
          'f' => 'edit',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'edit.item',
      ],
       [
          'a' => 'edit/reply',
          'k' => 622,
          'f' => 'edit',
          'p' =>  [
              'CSRF',
          ],
          'i' => 'edit.reply',
      ],
       [
          'a' => 'activatingform/addreply',
          'k' => 625,
          'f' => 'activatingform',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'activatingform/editreply',
          'k' => 627,
          'f' => 'activatingform',
          'p' =>  [
              'CSRF',
          ],
      ],
       [
          'a' => 'web/deleted',
          'k' => 632,
          'f' => 'web',
          'i' => 'web.deleted',
      ],
       [
          'a' => 'web/audits',
          'k' => 635,
          'f' => 'web',
          'i' => 'web.audits',
      ],
       [
          'a' => 'web/comments',
          'k' => 638,
          'f' => 'web',
          'i' => 'web.comments',
      ],
       [
          'a' => 'web/status/{code?}',
          'k' => 641,
          'f' => 'web',
          'w' =>  [
              'code' => '[0-9]+',
          ],
          'i' => 'web.status',
          'd' => 1,
          'v' => 1,
      ],
       [
          'a' => 'web/favicon/add',
          'k' => 648,
          'f' => 'web',
      ],
       [
          'a' => 'web/screenshot/add',
          'k' => 650,
          'f' => 'web',
      ],
       [
          'a' => 'web/status/update',
          'k' => 652,
          'f' => 'web',
      ],
       [
          'a' => 'web/bookmarks',
          'k' => 654,
          'f' => 'web',
          'i' => 'web.bookmarks',
      ],
       [
          'a' => 'web/my',
          'k' => 657,
          'f' => 'web',
          'i' => 'web.user.sites',
      ],
       [
          'a' => 'web/{grouping}/dir/{sort}/{slug}',
          'k' => 661,
          'f' => 'web',
          'i' => 'grouping.category',
          'd' => 1,
      ],
       [
          'a' => 'search/go',
          'k' => 664,
          'f' => 'search',
          'i' => 'search.go',
      ],
       [
          'a' => 'search/opensearch',
          'k' => 667,
          'f' => 'search',
          'i' => 'opensearch',
      ],
       [
          'a' => 'search/api',
          'k' => 670,
          'f' => 'search',
      ],
       [
          'a' => 'search',
          'k' => 672,
          'f' => 'search',
          'i' => 'search',
          's' => 1,
      ],
  ];


    /**
    * @internal
    */
    public static function getData(): array
    {
        return self::$data;
    }
}
