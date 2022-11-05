<?php

namespace TruongBo\TiktokApi;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Log;

class Tiktok
{
    /**
     * @var string $host
     * Host api
     * */
    private string $host = "https://www.tikwm.com";

    /**
     * @var string $path_api_get_similar_users
     * Get similar users
     * */
    private string $path_api_get_similar_users = "api/user/discover";

    /**
     * @var string $path_api_get_video_by_hashtag
     * Get video list by challenge (hashtag)
     * */
    private string $path_api_get_video_by_hashtag = "api/challenge/posts";

    /**
     * @var string $path_api_get_hashtag_detail
     * Get video list by challenge (hashtag)
     * */
    private string $path_api_get_hashtag_detail = "api/challenge/info";

    /**
     * @var string $path_api_search_hashtag_by_keyword
     * Search challenge (hashtag) by keyword
     * */
    private string $path_api_search_hashtag_by_keyword = "api/challenge/search";

    /**
     * @var string $path_api_search_videos_by_keyword
     * Search videos by keyword
     * */
    private string $path_api_search_videos_by_keyword = "api/feed/search";

    /**
     * @var string $path_api_get_user_liked
     * Get user liked
     * */
    private string $path_api_get_user_liked = "api/user/favorite";

    /**
     * @var string $path_api_get_video_comment
     * Get video comment
     * */
    private string $path_api_get_video_comment = "api/comment/list";

    /**
     * @var string $path_api_get_regions
     * Get region list
     * */
    private string $path_api_get_regions = "api/region";

    /**
     * @var string $path_api_get_trending_video
     * Get trending video
     * */
    private string $path_api_get_trending_video = "api/feed/list";

    /**
     * @var string $path_api_get_music_detail
     * Get music detail
     * */
    private string $path_api_get_music_detail = "api/music/info";

    /**
     * @var string $path_api_get_music_feed_video
     * Get music feed video
     * */
    private string $path_api_get_music_feed_video = "api/music/posts";

    /**
     * @var string $path_api_get_user_following
     * Get user following
     * */
    private string $path_api_get_user_following = "api/user/following";

    /**
     * @var string $path_api_get_user_followers
     * Get user followers
     * */
    private string $path_api_get_user_followers = "api/user/followers";

    /**
     * @var string $path_api_get_user_feed_videos
     * Get user feed videos
     * */
    private string $path_api_get_user_feed_videos = "api/user/posts";

    /**
     * @var string $path_api_get_video_no_watermark
     * Get video without watermark
     * */
    private string $path_api_get_video_no_watermark = "api";

    /**
     * Construct class TikWMApi
     * @param ClientInterface $client
     * */
    public function __construct(private ClientInterface $client)
    {

    }

    /**
     * Get Similar Users, limit 1 req/ 10 sec, unique_id or user_id, count max 50
     *
     * @param string $method
     * @param string $unique_id
     * @param int $count
     * @return array
     * @throws GuzzleException
     */
    public function getSimilarUsers(string $method, string $unique_id, int $count = 10): array
    {
        try {
            $query = [
                'unique_id' => $unique_id,
                'count'     => $count,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_similar_users, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data'] as $key => $user) {
                    $_user_info = Arr::only($user['user'] ?? [], ['id', 'uniqueId', 'nickname', 'signature', 'verified']);
                    $_user_stats = $user['stats'] ?? [];
                    $contents['data'][$key] = array_merge($_user_info, $_user_stats);
                }
                return $contents['data'];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get Video List By Challenge(HashTag), limit 1 req/ 10 sec
     * @param string $method
     * @param int $challenge_id
     * @param int $count
     * @param int $cursor
     * @return array
     * * @throws GuzzleException
     */
    public function getVideoByHashTag(string $method, int $challenge_id, int $count = 10, int $cursor = 0): array
    {
        try {
            $query = [
                'challenge_id' => $challenge_id,
                'count'        => $count,
                'cursor'       => $cursor,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_video_by_hashtag, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data']["videos"] as $key => $video) {
                    $_video_info = Arr::only($video ?? [], ['video_id', 'region', 'duration', 'title', 'play_count', 'digg_count', 'comment_count', 'share_count', 'download_count', 'create_time', 'music_info', 'author']);
                    $contents['data']["videos"][$key] = $_video_info;
                }
                return $contents['data']["videos"];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get hashtag detail by name, limit 1 req/ 1 sec
     * @param string $method
     * @param string $challenge_name
     * @return array
     * @throws GuzzleException
     */
    public function getHashTagDetail(string $method, string $challenge_name): array
    {
        try {
            $query = [
                'challenge_name' => $challenge_name,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_hashtag_detail, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                return Arr::only($contents['data'], ['id', 'cha_name', 'user_count', 'view_count']);
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Search Challenge(HashTag) By Keywords, limit 1 req/ 10 sec
     * @param string $method
     * @param string $keyword
     * @param int $count
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getHashTagBYKeyword(string $method, string $keyword, int $count = 10, int $cursor = 0): array
    {
        try {
            $query = [
                'keywords' => $keyword,
                'count'    => $count,
                'cursor'   => $cursor,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_search_hashtag_by_keyword, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data']["challenge_list"] as $key => $challenge) {
                    $_challenge_info = Arr::only($challenge ?? [], ['id', 'cha_name', 'user_count', 'view_count']);
                    $contents['data']["challenge_list"][$key] = $_challenge_info;
                }
                return $contents['data']["challenge_list"];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Search Videos By Keywords, limit 1 req/ 10 sec
     * @param string $method
     * @param string $keyword
     * @param int $count
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getVideoByKeyword(string $method, string $keyword, int $count = 10, int $cursor = 0): array
    {
        try {
            $query = [
                'keywords' => $keyword,
                'count'    => $count,
                'cursor'   => $cursor,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_search_videos_by_keyword, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data']["videos"] as $key => $video) {
                    $_video_info = Arr::only($video ?? [], ['video_id', 'region', 'duration', 'title', 'play_count', 'digg_count', 'comment_count', 'share_count', 'download_count', 'create_time', 'music_info', 'author']);
                    $contents['data']["videos"][$key] = $_video_info;
                }
                return $contents['data']["videos"];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get User Liked, limit 1 req/ 10 sec
     *
     * @param string $method
     * @param string $unique_id
     * @param int $count
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getUserLiked(string $method, string $unique_id, int $count = 10, int $cursor = 0): array
    {
        try {
            $query = [
                'unique_id' => $unique_id,
                'count'     => $count,
                'cursor'    => $cursor,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_user_liked, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data']["videos"] as $key => $video) {
                    $_video_info = Arr::only($video ?? [], ['video_id', 'region', 'duration', 'title', 'play_count', 'digg_count', 'comment_count', 'share_count', 'download_count', 'create_time', 'music_info', 'author']);
                    $contents['data']["videos"][$key] = $_video_info;
                }
                return $contents['data']["videos"];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get comments of video
     *
     * @param string $method
     * @param string $video_url
     * @param int $count
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getCommentsVideo(string $method, string $video_url, int $count = 10, int $cursor = 0): array
    {
        try {
            $query = [
                'url'    => $video_url,
                'count'  => $count,
                'cursor' => $cursor,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_video_comment, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data']["comments"] as $key => $comment) {
                    $contents['data']["comments"][$key] = $comment;
                }
                return $contents['data']["comments"];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get region list
     * @param string $method
     * @return array
     * @throws GuzzleException
     */
    public function getRegions(string $method): array
    {
        try {
            $endpoint = build_external_url($this->host, $this->path_api_get_regions);
            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                return $contents["data"];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get trending feed, limit 1 req/ 10 sec
     * @param string $method
     * @param string $region
     * @param int $count
     * @return array
     * @throws GuzzleException
     */
    public function getTrendingVideo(string $method, string $region, int $count = 10): array
    {
        try {
            $query = [
                'region' => $region,
                'count'  => $count,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_trending_video, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data'] as $key => $video) {
                    $_video_info = Arr::only($video ?? [], ['video_id', 'wmplay', 'region', 'duration', 'title', 'play_count', 'digg_count', 'comment_count', 'share_count', 'download_count', 'create_time', 'music_info', 'author']);
                    $contents['data'][$key] = $_video_info;
                }
                return $contents['data'];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get music detail, limit 1 req/ 1 sec
     * @param string $method
     * @param string $music_url
     * @return array
     * @throws GuzzleException
     */
    public function getMusicDetail(string $method, string $music_url): array
    {
        try {
            $query = [
                'url' => $music_url,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_music_detail, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                return Arr::only($contents['data'], ['id', 'title', 'duration', 'video_count']);
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get music feed videos, limit 1 req/ 10 sec
     * @param string $method
     * @param int $music_id
     * @param int $count
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getVideosByMusicId(string $method, int $music_id, int $count = 10, int $cursor = 0): array
    {
        try {
            $query = [
                'music_id' => $music_id,
                'count'    => $count,
                'cursor'   => $cursor,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_music_feed_video, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data']["videos"] as $key => $video) {
                    $_video_info = Arr::only($video ?? [], ['video_id', 'region', 'duration', 'title', 'play_count', 'digg_count', 'comment_count', 'share_count', 'download_count', 'create_time', 'music_info', 'author']);
                    $contents['data']["videos"][$key] = $_video_info;
                }
                return $contents['data']["videos"];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get user following, limit 1 req/ 10 sec, unique_id, count max 50
     *
     * @param string $method
     * @param int $user_id
     * @param int $count
     * @param int $time
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getUserFollowing(string $method, int $user_id, int $count = 50, int $time = 0, int $cursor = 0): array
    {
        try {
            $query = [
                'user_id' => $user_id,
                'count'   => $count,
                'cursor'  => $cursor,
                'time'    => $time,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_user_following, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data']['followings'] as $key => $user) {
                    $contents['data']['followings'][$key] = Arr::only($user ?? [], ['id', 'unique_id', 'nickname', 'signature', 'verified']);
                }
                return $contents['data']['followings'];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get user follower, limit 1 req/ 10 sec, unique_id, count max 50
     *
     * @param string $method
     * @param int $user_id
     * @param int $count
     * @param int $time
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getUserFollowers(string $method, int $user_id, int $count = 50, int $time = 0, int $cursor = 0): array
    {
        try {
            $query = [
                'user_id' => $user_id,
                'count'   => $count,
                'cursor'  => $cursor,
                'time'    => $time,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_user_followers, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data']['followers'] as $key => $user) {
                    $contents['data']['followers'][$key] = Arr::only($user ?? [], ['id', 'unique_id', 'nickname', 'signature', 'verified']);
                }
                return $contents['data']['followers'];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get user feed videos, limit 1 req/ 10 sec
     * @param string $method
     * @param string $unique_id
     * @param int $count
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getVideosByUser(string $method, string $unique_id, int $count = 10, int $cursor = 0): array
    {
        try {
            $query = [
                'unique_id' => $unique_id,
                'count'     => $count,
                'cursor'    => $cursor,
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_user_feed_videos, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                foreach ($contents['data']["videos"] as $key => $video) {
                    $_video_info = Arr::only($video ?? [], ['video_id', 'region', 'duration', 'title', 'play_count', 'digg_count', 'comment_count', 'share_count', 'download_count', 'create_time', 'music_info', 'author']);
                    $contents['data']["videos"][$key] = $_video_info;
                }
                return $contents['data']["videos"];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get video without watermark
     * @param string $method
     * @param string $tiktok_url
     * @param int $hd | 1 : hd , 0 : hd
     * @return array
     * @throws GuzzleException
     */
    public function getVideoNoWaterMark(string $method, string $tiktok_url, int $hd = 1): array
    {
        try {
            if ($hd != 0 && $hd != 1) $hd = 1;
            $query = [
                'url' => $tiktok_url,
                'hd'  => $hd
            ];
            $endpoint = build_external_url($this->host, $this->path_api_get_video_no_watermark, $query);

            $response = $this->client->request($method, $endpoint);
            $contents = json_decode($response->getBody()->getContents(), true);
            if ($contents["msg"] == "success") {
                return $contents['data'];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }
}
