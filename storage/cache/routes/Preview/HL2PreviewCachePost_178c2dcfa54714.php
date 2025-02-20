<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2PreviewCachePost_178c2dcfa54714
{
    /**
    * @internal
    */
    private static array $data =[
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
          'a' => 'search/web/url',
          'k' => 598,
          'f' => 'search',
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
          'a' => 'search/api',
          'k' => 670,
          'f' => 'search',
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
