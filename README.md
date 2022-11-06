# Tiktok Api Full Data By TikWM

* Download video no watermark
* Get trending video
* Get info video
* Get info user
* Get info music
* Get info hashtag
* Search hashtag,video,music,..
* Anymore...

**Details about API in src/TikTok.php, you can get endpoint and customize it.Do not update this package only if the api is changed**

## Installation

Install the package (If you use Laravel):

```php
composer require truongbo/tiktok-api
```

Use:
```php
$trending = app('tiktok-api')
            ->getTrendingVideo(method: 'GET', region: 'VN', count: 1);
```

Result:
```php
  0 => array:13 [
    "video_id" => "7142393816136437018"
    "region" => "VN"
    "title" => "Đỉnh cao tóp mỡ mắm tỏi #phuongoanhdaily #reviewanngon #ancungtiktok "
    "duration" => 59
    "wmplay" => "https://v16m-default.akamaized.net/dec592c87719ca1f2177ed31684d7931/6366c76e/video/tos/useast2a/tos-useast2a-pve-0037-aiso/2e7c8b072f724fec8d6f1c3c3963fc3a/?a=0&ch=0&cr=0&dr=0&lr=all&cd=0%7C0%7C0%7C0&cv=1&br=3628&bt=1814&cs=0&ds=3&ft=teSL~8hPobVD12NJ~Kz8-UxhQ-qJyF_ODS2&mime_type=video_mp4&qs=0&rc=NjU2NDxoaGY4NWU6NTQ0O0BpM2llNjo6ZjhoZjMzZjgzM0BjMV5hYTReNmMxMl81X2M2YSNwMS9ncjQwL2NgLS1kL2Nzcw%3D%3D&l=202211051427310102170940960DB96DE7&btag=80000"
    "music_info" => array:8 [
      "id" => "7142393838903102234"
      "title" => "original sound - phuongoanh.daily"
      "play" => "https://sf16-ies-music.tiktokcdn.com/obj/ies-music-aiso/7142393882486033178.mp3"
      "cover" => "https://p16-sign-sg.tiktokcdn.com/aweme/1080x1080/tos-alisg-avt-0068/5f46bfa03ee3fdc18d2af9e36e6c89aa.jpeg?x-expires=1667743200&x-signature=38uiU9pjTvZMTUyaJBEdF8F8B1s%3D"
      "author" => "Phuongoanh.daily"
      "original" => true
      "duration" => 59
      "album" => ""
    ]
    "play_count" => 5191837
    "digg_count" => 267103
    "comment_count" => 1342
    "share_count" => 4173
    "download_count" => 2584
    "create_time" => 1662968159
    "author" => array:4 [
      "id" => "6902647445742830593"
      "unique_id" => "phuongoanh.daily"
      "nickname" => "Phuongoanh.daily"
      "avatar" => "https://p16-sign-sg.tiktokcdn.com/tos-alisg-avt-0068/5f46bfa03ee3fdc18d2af9e36e6c89aa~c5_300x300.jpeg?x-expires=1667743200&x-signature=fnUHDSNKPIfQ8rD93fe71YH26ig%3D"
    ]
  ]

```
